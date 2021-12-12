<?php require_once('../../private/initialize.php'); ?>

<?php
// We need to use sessions, so you should always start sessions using the below code.
session_start();
// If the user is not logged in redirect to the login page...
if (!isset($_SESSION['loggedin'])) {
	header('Location: login.php');
	exit;
}

// We don't have the password or email info stored in sessions so instead we can get the results from the database.
$stmt = $con->prepare('SELECT password, email FROM accounts WHERE id = ?');
// In this case we can use the account ID to get the account info.
$stmt->bind_param('i', $_SESSION['id']);
$stmt->execute();
$stmt->bind_result($password, $email);
$stmt->fetch();
$stmt->close();

$cust_id = $_SESSION['id'];
$fields = [
	'c_street' => 'Street:',
	'c_city' => 'City:',
	'c_cntry' => 'Country:',
	'c_zip' => 'Zipcode:',
	'c_email' => 'Email:',
	'c_ctrycode' => 'Country Code:',
	'c_contctno' => 'Contact Number:',
	'c_emc_fname' => 'Emergency Contact First Name:',
	'c_emc_lname' => 'Emergency Contact Last Name:',
	'c_emc_ctrycode' => 'Emergency Country Code:',
	'c_emc_contctno' => 'Emergency Country Number:'
];

$member_fields = [
	'mbr_name' => 'Name of the membership:',
	'assc_al' => 'Associated airline:',
	'mbr_start_date' => 'Membership start date:',
	'mbr_end_date' => 'Membership end date:'
];

$agent_fields = [
	'ba_name' => 'Name of the agent:',
	'ba_country_code' => 'Country Code:',
	'ba_contctno' => 'Contact Number:',
	'web_addr' => 'Web address of the booking agent:'
];

$_SESSION['errors'] = [];

//UPDATE IF POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	//Validate
	//Check for all required
	foreach ($fields as $name => $header) {
		if (empty($_POST[$name])) {
			$_SESSION['errors'][$name] = 'Required';
		}
	}

	update_customer_info($cust_id, $_POST);
}


//GET customer info

$cust_info = get_customer_info($cust_id);

$cust = mysqli_fetch_assoc($cust_info);


?>

<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<title>Profile Page</title>
	<link href="style.css" rel="stylesheet" type="text/css">
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
	<style>
		<?php include '../stylesheets/contact_page.css'; ?><?php include '../stylesheets/form.css'; ?>
	</style>
</head>

<body class="loggedin">
	<nav class="navtop">
		<div>
			<a href="../index.php">
				<h1>Safe Fly Management Excellence</h1>
			</a>
			<a href="profile.php"><i class="fas fa-user-circle"></i>Profile</a>
			<a href="logout.php"><i class="fas fa-sign-out-alt"></i>Logout</a>
		</div>
	</nav>
	<div class="content">
		<h2>Profile Page</h2>
		<div>
			<p><u>Account Information</u></p>
			<table>
				<tr>
					<td>Username:</td>
					<td><?= $_SESSION['name'] ?></td>
				</tr>
				<tr>
					<td>Email:</td>
					<td><?= $email ?></td>
				</tr>
			</table>
		</div>
		<div>
			<p><u>Customer Information</u></p>
			<form method="POST" action="profile.php">
				<table>
					<tbody>
						<?php
						foreach ($fields as $name => $title) {
							$template = "<tr><td>$title</td><td><input type='text' name='$name' value='{$cust[$name]}'/></td><td class='error'>{$_SESSION['errors'][$name]}</td></tr>";
							echo ($template);
						}
						?>
						<tr>
							<td>Customer Type:</td>
							<td>
								<div class="radio-group">
									<input type="radio" name="c_type" value="C" <?php if ($cust['c_type'] == 'C') echo ('checked'); ?>><span>C</span>
									<input type="radio" name="c_type" value="B" <?php if ($cust['c_type'] == 'B') echo ('checked'); ?>><span>B</span>
									<input type="radio" name="c_type" value="M" <?php if ($cust['c_type'] == 'M') echo ('checked'); ?>><span>M</span>
								</div>
							</td>
							<td><?php $_SESSION['errors']['c_type']; ?></td>
						</tr>
					</tbody>
					<tbody id="form-member">
						<tr>
							<td class="section-title" colspan=3>Member</td>
						</tr>
						<?php
						foreach ($member_fields as $name => $title) {
							$template = "<tr><td>$title</td><td><input type='text' name='$name' value='{$cust[$name]}'/></td><td class='error'>{$_SESSION['errors'][$name]}</td></tr>";
							echo ($template);
						}
						?>
					</tbody>
					<tbody id="form-agent">
						<tr>
							<td class="section-title" colspan=3>Agent</td>
						</tr>
						<?php
						foreach ($agent_fields as $name => $title) {
							$template = "<tr><td>$title</td><td><input type='text' name='$name' value='{$cust[$name]}'/></td><td class='error'>{$_SESSION['errors'][$name]}</td></tr>";
							echo ($template);
						}
						?>
					</tbody>
				</table>
				<input type="submit" value="Update">
			</form>
		</div>
	</div>
	<script type="text/javascript">
		let memberForm = document.querySelector('#form-member');
		let agentForm = document.querySelector('#form-agent');
		memberForm.style.display = 'none';
		agentForm.style.display = 'none';
		document.querySelectorAll("input[name='c_type']").forEach((input) => {
			if (input.value == 'M' && input.checked == true) {
				memberForm.style.display = null
			}
			if (input.value == 'B' && input.checked == true) {
				agentForm.style.display = null
			}
			input.addEventListener('change', (e) => {
				memberForm.style.display = 'none';
				agentForm.style.display = 'none';
				if (e.target.value == 'M' && e.target.checked == true) {
					memberForm.style.display = null
				}
				if (e.target.value == 'B' && e.target.checked == true) {
					agentForm.style.display = null
				}
			});
		});
	</script>
</body>

</html>