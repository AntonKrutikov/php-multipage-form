<?php
require_once(__DIR__ . '/../../private/functions.php');
session_start();
$fields = array(
    'street',
    'city',
    'country',
    'zipcode',
    'phone_country_code',
    'phone_number',
    'email',
    'em_firstname',
    'em_lasttname',
    'em_phone_country_code',
    'em_phone_number',
    'customer_type'
);
//Store provided values in session too, to return correct values to user back
foreach ($fields as $f) {
    unset($_SESSION['errors'][$f]);
    if (isset($_POST[$f])) {
        $_SESSION['values'][$f] = htmlspecialchars(strip_tags($_POST[$f])); //renew saved
    }
}
$_SESSION['referer'] = 'passenger_form_3.php';

//Check for all required
foreach ($fields as $f) {
    if (empty($_SESSION['values'][$f])) {
        $_SESSION['errors'][$f] = 'Required';
    }
}

//Check for numerics
$numeric_fields = array('zipcode', 'phone_country_code', 'em_phone_country_code');
foreach ($numeric_fields as $f) {
    if (!empty($_SESSION['values'][$f]) and !is_numeric($_SESSION['values'][$f])) {
        $_SESSION['errors'][$f] = 'Wrong format - numeric only';
    }
}

//Simple check for phone number
$phone_fields = array('phone_number', 'em_phone_number');
foreach ($phone_fields as $f) {
    $stripped = preg_replace("/\s+|[-]/", "", $_SESSION['values'][$f]);
    if (!is_numeric($stripped) or strlen($stripped) != 7) {
        $_SESSION['errors'][$f] = 'Wrong phone format';
    }
}

//Validate errors only for input on page2
$error_count = 0;
foreach ($_SESSION['errors'] as $e => $text) {
    if (in_array($e, $fields)) {
        $error_count++;
    }
}

//IF no valid - return to previous
if ($error_count  > 0) {
    header("Location: passenger_form_2.php");
    exit();
}

//Based on cutomer type - select form to show
if ($_SESSION['values']['customer_type'] == 'M') {
    $header = 'Member Info';
    $fields = array(
        'member_name' => array(
            'type' => 'text',
            'header' => 'Member Name',
            'placeholder' => ''
        ),
        'associated_airline' => array(
            'type' => 'text',
            'header' => 'Associated airline',
            'placeholder' => ''
        ),
        'membership_start_date' => array(
            'type' => 'text',
            'header' => 'Membership start date',
            'placeholder' => ''
        ),
        'membership_end_date' => array(
            'type' => 'text',
            'header' => 'Membership end date',
            'placeholder' => ''
        ),
    );
} else if ($_SESSION['values']['customer_type'] == 'B') {
    $header = 'Agent Info';
    $fields = array(
        'agent_name' => array(
            'type' => 'text',
            'header' => 'Agent name',
            'placeholder' => ''
        ),
        'web_address' => array(
            'type' => 'text',
            'header' => 'Web address',
            'placeholder' => ''
        ),
        'agent_contact_number' => array(
            'type' => 'text',
            'header' => 'Agent contact number',
            'placeholder' => ''
        ),
    );
} else if ($_SESSION['values']['customer_type'] == 'C') {
    header('Location: passenger_form_4.php');
    exit();
}
?>
<!doctype html>

<html lang="en">
<head>
<link href="style.css"rel="stylesheet"type="text/css">
      <link rel="stylesheet"href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
</head>
<body>
  <div class="container">
    <style>
      <?php include '../stylesheets/contact_page.css'; ?>
      <?php include '../stylesheets/form.css'; ?>      
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
    <form action="passenger_form_4.php" method="post">
        <h1><?php echo($header); ?></h1>
        <?php displayForm($fields); ?>
        <button type="cancel" formaction="passenger_form_2.php">Back</button>
        <input type="submit" value="Next">

    </form>
</div>
</body>
</html>