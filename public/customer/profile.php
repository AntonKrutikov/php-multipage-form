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
?>

<?php
	
	$cust_id = $_SESSION['id'];
	$cust_info = get_customer_info($cust_id);

?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Profile Page</title>
		<link href="style.css" rel="stylesheet" type="text/css">
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
	</head>
	<body class="loggedin">
		<nav class="navtop">
			<div>
				<a href="../index.php"><h1>Safe Fly Management Excellence</h1></a>
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
						<td><?=$_SESSION['name']?></td>
					</tr>
					<tr>
						<td>Email:</td>
						<td><?=$email?></td>
					</tr>
				</table>
			</div>
			<div>
				<?php ($cust = mysqli_fetch_assoc($cust_info)) ?>
				<p><u>Customer Information</u></p>
				<table>
					<tr>
						<td>Street:</td>
						<td><?php echo h(isset($cust['c_street']) ? $cust['c_street'] : ''); ?></td>
					</tr>
					<tr>
						<td>City:</td>
						<td><?php echo h(isset($cust['c_city']) ? $cust['c_city'] : '' ); ?></td>
					</tr>
					<tr>
						<td>Country:</td>
						<td><?php echo h(isset($cust['c_cntry'])) ? $cust['c_cntry'] : ''; ?></td>
					<tr>
					<tr>
						<td>Zipcode:</td>
						<td><?php echo h(isset($cust['c_zip'])) ? $cust['c_zip'] : ''; ?></td>
					</tr>
					<tr>
						<td>Email:</td>
						<td><?php echo h(isset($cust['c_email']) ? $cust['c_email'] : ''); ?></td>
					</tr>
					<tr>
						<td>Contact Number:</td>
						<td>+<?php echo h(isset($cust['c_ctrycode'])) ? $cust['c_ctrycode'] : ''; ?><?php echo h(isset($cust['c_contctno']) ? $cust['c_contctno'] : ''); ?></td>
					</tr>
					<tr>
						<td>Total Passenger(Count):</td>
						<td></td>
					</tr>
					<tr>
						<td>Emergency Contact First Name:</td>
						<td><?php echo h(isset($cust['c_emc_fname']) ? $cust['c_emc_fname'] : ''); ?></td>
					</tr>
					<tr>
						<td>Emergency Contact Last Name:</td>
						<td><?php echo h(isset($cust['c_emc_lname']) ? $cust['c_emc_lname'] : ''); ?></td>
					</tr>
					<tr>
						<td>Emergency Contact Number:</td>
						<td>+<?php echo h(isset($cust['c_emc_ctrycode']) ? $cust['c_emc_ctrycode'] : '' ); ?><?php echo h(isset($cust['c_emc_contctno']) ? $cust['c_emc_contctno'] : ''); ?></td>
					</tr>
				</table>
			</div>
		</div>
	</body>
</html>