<?php 
session_start();
//If user acces that page not from next pages (back button) than reset values
//This behaviour can be ommited if not needed
if(!in_array($_SESSION['referer'], array('passenger_form_2.php','passenger_form_3.php'))){
  $_SESSION['values'] = [];
  $_SESSION['errors'] = [];
}
$_SESSION['referer'] = null;
?>
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
  <form action="passenger_form_2.php" method="post">
  	<h1>Please Enter Passenger Information</h1>
    <label for="firstname">First Name</label><span class="error"><?php echo($_SESSION['errors']['firstname']); ?></span>
    <input type="text" id="fname" name="firstname" placeholder="Passenger first name.." value="<?php echo($_SESSION['values']['firstname']); ?>">

    <label for="middlename">Middle Name</label><span class="error"><?php echo($_SESSION['errors']['middlename']); ?></span>
    <input type="text" id="mname" name="middlename" placeholder="Passenger middle name(if any).." value="<?php echo($_SESSION['values']['middlename']); ?>">

    <label for="lastname">Last Name</label><span class="error"><?php echo($_SESSION['errors']['lastname']); ?></span>
    <input type="text" id="lname" name="lastname" placeholder="Passenger last name.." value="<?php echo($_SESSION['values']['lastname']); ?>">

    <label for="birthdate">Birth Date</label><span class="error"><?php echo($_SESSION['errors']['birthdate']); ?></span>
    <input type="text" id="birthdate" name="birthdate" placeholder="Passenger birthday(DD/MM/YYYY).." value="<?php echo($_SESSION['values']['birthdate']); ?>">

    <label for="nationality">Nationality</label><span class="error"><?php echo($_SESSION['errors']['nationality']); ?></span>
    <input type="text" id="nationality" name="nationality" placeholder="Passenger nationality.." value="<?php echo($_SESSION['values']['nationality']); ?>">

    <label for="gender">Gender</label><span class="error"><?php echo($_SESSION['errors']['gender']); ?></span>
    <input type="text" id="gender" name="gender" placeholder="Passenger gender.." value="<?php echo($_SESSION['values']['gender']); ?>">

    <label for="passportno">Passenger passport number</label><span class="error"><?php echo($_SESSION['errors']['passportno']); ?></span>
    <input type="text" id="passportno" name="passportno" placeholder="Passenger passport number.." value="<?php echo($_SESSION['values']['passportno']); ?>">

    <label for="passportexp">Passenger passport expire date</label><span class="error"><?php echo($_SESSION['errors']['passportexp']); ?></span>
    <input type="text" id="passportexp" name="passportexp" placeholder="Passenger passport expire date(DD/MM/YYYY).." value="<?php echo($_SESSION['values']['passportexp']); ?>">  
    
    <label for="status">Passenger or Customer</label><span class="error"><?php echo($_SESSION['errors']['status']); ?></span>
    <div class="radio-group">
    <input type="radio" name="status" value="C" <?php if ($_SESSION['values']['status'] == "C") echo("checked"); ?>> Customer
    <input type="radio" name="status" value="P" <?php if ($_SESSION['values']['status'] == "P") echo("checked"); ?>> Passenger
    </div>

    <button type="cancel" formaction="home.php">Cancel</button>
    <input type="submit" value="Next">

    
  </form>
</div>
</html>