<?php require_once('../../private/initialize.php'); ?>

<?php
// We need to use sessions, so you should always start sessions using the below code.
session_start();
// If the user is not logged in redirect to the login page...
if (!isset($_SESSION['loggedin'])) {
	header('Location: login.php');
	exit;
}
?>

<?php

	$pax_list = find_all_passengers();

?>

<!DOCTYPE html>

<html lang="en">
	<head>
		<meta charset="utf-8">
		<title>Customer Portal</title>
		<link href="style.css" rel="stylesheet" type="text/css">
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
		<style>
			#passengers {
				font-family: Arial, Helvetica, sans-serif;
				border-collapse: collapse;
  				width: 100%;
			}

			#passengers td, #passengers th {
				border: 1px solid #ddd;
				padding: 8px;
			}

			#passengers tr:nth-child(even){background-color: #f2f2f2;}

			#passengers tr:hover {background-color: #ddd;}

			#passengers th {
				padding-top: 12px;
				padding-bottom: 12px;
				text-align: left;
				background-color: #4a536e;
				color: white;
			}
		</style>
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
			<h2>Customer Portal</h2>
			<p>Welcome back, <?=$_SESSION['name']?>!</p>
    		<h3 style="float: left; color:#4a536e">Passenger List</h2>
    		<a href="passenger_form.php"><h4 style="float: right; color:blue; text-decoration: underline">Add Passenger</h3></a>
    						
			<table id="passengers">
				<tr>
					<th>First Name</th>
					<th>Middle Name</th>
					<th>Last Name</th>
					<th>Birth Date</th>
					<th>Nationality</th>
					<th>Gender</th>
					<th>Passport Number</th>
					<th>Passport Expire Date</th>
				</tr>
				<?php while($pax = mysqli_fetch_assoc($pax_list)) { ?>
					<tr>
						<td><?php echo h($pax['p_fname']); ?></td>
						<td><?php echo h($pax['p_mname']); ?></td>
						<td><?php echo h($pax['p_lname']); ?></td>
						<td><?php echo h($pax['p_bdate']); ?></td>
						<td><?php echo h($pax['p_nationality']); ?></td>
						<td><?php echo h($pax['p_gdr']); ?></td>
						<td><?php echo h($pax['p_passportno']); ?></td>
						<td><?php echo h($pax['p_passport_exp_date']); ?></td>
					</tr>
				<?php } ?>
			</table>
		</div>
	</body>
</html>