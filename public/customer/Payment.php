<?php
require_once(__DIR__ . '/../../private/initialize.php');
require_once(__DIR__ . '/../../private/functions.php');
require_once(__DIR__ . '/../../private/query_functions.php');
session_start();
//If user acces that page not from next pages (back button) than reset values
//This behaviour can be ommited if not needed


$invoice = get_invoice($_SESSION['id'])->fetch_assoc();
$_SESSION['values']['inv_id'] = $invoice['inv_id'];
$_SESSION['values']['inv_amount'] = $invoice['inv_amount'];

$_SESSION['values']['Payment Date'] = date("d/m/Y");

$fields = array(
  /* ID is generated */
  // 'PaymentID' => array(
  //   'type' => 'text',
  //   'header' => 'Payment ID',
  //   'placeholder' => ''
  // ),
  // 'Payment Date' => array(
  //   'type' => 'text',
  //   'header' => 'Payment Date',
  //   'placeholder' => ''
  // ),
  // 'Payment amount' => array(
  //   'type' => 'text',
  //   'header' => 'Payment amount',
  //   'placeholder' => ''
  // ),
  'pmt_method' => array(
    'type' => 'select',
    'header' => 'Payment Method',
    'options' => [
      'Credit card' => 'Credit card',
      'Debit card' => 'Debit card'
    ]
  ),
  'pmt_cardno' => array(
    'type' => 'text',
    'header' => 'Card Number',
    'placeholder' => ''
  ),
  'card_fname' => array(
    'type' => 'text',
    'header' => 'First Name',
    'placeholder' => ''
  ),
  'card_lname' => array(
    'type' => 'text',
    'header' => 'Last Name',
    'placeholder' => ''
  ),
  'card_exp' => array(
    'type' => 'text',
    'header' => 'Card expire month',
    'placeholder' => 'MM'
  ),
  'card_exp_year' => array(
    'type' => 'text',
    'header' => 'Card expire year',
    'placeholder' => 'YYYY'
  )
);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  echo(var_dump($_POST));
  foreach ($fields as $f=>$value) {
    unset($_SESSION['errors'][$f]); //clean errors
    if (isset($_POST[$f])) {
      $_SESSION['values'][$f] = $_POST[$f]; //renew saved
    }
  }
  //validate INPUT
  //Check for all required
  foreach ($fields as $f=>$value) {
    if (empty($_SESSION['values'][$f])) {
      $_SESSION['errors'][$f] = 'Required';
    }
  }

  $numeric = ['pmt_cardno','card_exp','card_exp_year'];
  foreach ($numeric as $n) {
    if (!empty($_SESSION['values'][$n]) and !is_numeric($_SESSION['values'][$n])) {
      $_SESSION['errors'][$n] = 'Must be numeric';
    }
  }

  //Validate errors only for input on paymanet page
  $error_count = 0;
  foreach ($_SESSION['errors'] as $e => $text) {
    if (array_key_exists($e, $fields)) {
      $error_count++;
    }
  }

  //IF VALID - go home
  if ($error_count === 0) {
    $c_id = $_SESSION['id'];
    $values = $_SESSION['values'];
    //Make payment
    make_payment($c_id, $values);
    //clean values
    $_SESSION['values'] = [];
    $_SESSION['errors'] = [];
    //If no exceptions go home
    header("Location: home.php");
    exit();
  }
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
    <form action="Payment.php" method="post">
      <h1>Please Enter Payment Information</h1>

      <p>Your Invoice sum: <b><?php echo($invoice['inv_amount']); ?></b></p>

      <?php displayForm($fields); ?>
      <button type="cancel" formaction="home.php">Cancel</button>
      <input type="submit" value="Submit"/>
    </form>
  </div>
</body>

</html>