<!doctype html>

<html lang="en">
<div class="container">
  <style>
  	<?php include '../stylesheets/contact_page.css'; ?>
  	<link href="style.css" rel="stylesheet" type="text/css">
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
  </style>
  <nav class="navtop">
	<div>
		<a href="../index.php"><h1>Safe Fly Management Excellence</h1></a>
		<a href="profile.php"><i class="fas fa-user-circle"></i>Profile</a>
		<a href="logout.php"><i class="fas fa-sign-out-alt"></i>Logout</a>
	</div>
  </nav>
  <form action="action_page.php" method="post">
  	<h1>Please Enter Passenger Information</h1>
    <label for="fname">First Name</label>
    <input type="text" id="fname" name="firstname" placeholder="Passenger first name..">

    <label for="lname">Middle Name</label>
    <input type="text" id="mname" name="middlename" placeholder="Passenger middle name(if any)..">

    <label for="lname">Last Name</label>
    <input type="text" id="lname" name="lastname" placeholder="Passenger last name..">

    <label for="lname">Birth Date</label>
    <input type="text" id="birthdate" name="birthdate" placeholder="Passenger birthday(DD/MM/YYYY)..">

    <label for="lname">Nationality</label>
    <input type="text" id="nationality" name="nationality" placeholder="Passenger nationality..">

    <label for="lname">Gender</label>
    <input type="text" id="gender" name="gender" placeholder="Passenger gender..">

    <label for="lname">Passenger passport number</label>
    <input type="text" id="passportno" name="passportno" placeholder="Passenger passport number..">

    <label for="lname">Passenger passport expire date</label>
    <input type="text" id="passportexp" name="passportexp" placeholder="Passenger passport expire date(DD/MM/YYYY)..">   

    <input type="submit" value="Submit">
    <button type="cancel" formaction="home.php">Cancel</button>
  </form>
</div>
</html>