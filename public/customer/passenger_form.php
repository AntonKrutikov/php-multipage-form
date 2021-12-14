<?php
require_once(__DIR__ . '/../../private/initialize.php');
require_once(__DIR__ . '/../../private/functions.php');
require_once(__DIR__ . '/../../private/query_functions.php');
session_start();
//If user acces that page not from next pages (back button) than reset values
//This behaviour can be ommited if not needed
if (isset($_SESSION['referer']) and !in_array($_SESSION['referer'], array('passenger_form_2.php', 'passenger_form_3.php', 'passenger_form_4.php', 'passenger_form_5.php'))) {
  $_SESSION['values'] = [];
  $_SESSION['errors'] = [];
}
$_SESSION['referer'] = null;

$fields = array(
  'firstname' => array(
    'type' => 'text',
    'header' => 'First Name',
    'placeholder' => 'Passenger first name..'
  ),
  'middlename' => array(
    'type' => 'text',
    'header' => 'Middle Name',
    'placeholder' => 'Passenger middle name(if any)..'
  ),
  'lastname' => array(
    'type' => 'text',
    'header' => 'Last Name',
    'placeholder' => 'Passenger last name..'
  ),
  'birthdate' => array(
    'type' => 'text',
    'header' => 'Birth Date',
    'placeholder' => 'Passenger birthday(DD/MM/YYYY)..'
  ),
  'nationality' => array(
    'type' => 'text',
    'header' => 'Nationality',
    'placeholder' => 'Passenger nationality..'
  ),
  'gender' => array(
    'type' => 'text',
    'header' => 'Gender',
    'placeholder' => 'Passenger gender..'
  ),
  'passportno' => array(
    'type' => 'text',
    'header' => 'Passenger passport number',
    'placeholder' => 'Passenger passport number..'
  ),
  'passportexp' => array(
    'type' => 'text',
    'header' => 'Passenger passport expire date',
    'placeholder' => 'Passenger passport expire date(DD/MM/YYYY)..'
  )
);
$exists = (bool) get_customer_info($_SESSION['id'])->fetch_row();
if (!$exists) {
  $fields['type'] = array(
    'type' => 'radio',
    'header' => 'Passenger or Customer',
    'variants' => array(
      ['value' => 'C', 'header' => 'C'],
      ['value' => 'P', 'header' => 'P'],
    )
    );
} else {
  $_SESSION['values']['type'] = 'P';
}
?>
<!doctype html>

<html lang="en">

<head>
  <link href="style.css" rel="stylesheet" type="text/css">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
</head>

<body>
  <div class="container">
    <style>
      <?php include '../stylesheets/contact_page.css'; ?><?php include '../stylesheets/form.css'; ?>
    </style>
    <nav class="navtop">
      <div>
        <a href="../index.php">
          <h1>Safe Fly Management Excellence</h1>
        </a>
        <a href="profile.php"><i class="fas fa-user-circle"></i>Profile</a>
        <a href="logout.php"><i class="fas fa-sign-out-alt"></i>Logout</a>
      </div>
    </nav>
    <form action="passenger_form_2.php" method="post">
      <h1>Please Enter Passenger Information</h1>

      <?php displayForm($fields); ?>
      <button type="cancel" formaction="home.php">Cancel</button>
      <input type="submit" value="Next">


    </form>
  </div>
</body>

</html>