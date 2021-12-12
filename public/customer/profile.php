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

//UPDATE IF POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

	//TODO NEED TO VALIDATE INPUT HERE
	
	update_customer_info($cust_id, $_POST);
}


//GET customer info

$cust_info = get_customer_info($cust_id);

$cust = mysqli_fetch_assoc($cust_info);
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
]

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
					<?php
					foreach ($fields as $name => $title) {
						$template = "<tr><td>$title</td><td><input type='text' name='$name' value='{$cust[$name]}'/></td></tr>";
						echo ($template);
					}
					?>
				</table>
				<input type="submit" value="Update">
			</form>
		</div>
	</div>
</body>

</html>