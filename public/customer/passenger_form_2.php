<?php
require_once(__DIR__ . '/../../private/functions.php');
session_start();
$fields = array(
    'firstname',
    'middlename',
    'lastname',
    'birthdate',
    'nationality',
    'gender',
    'passportno',
    'passportexp',
    'type'
);
//Store provided values in session too, to return correct values to user back
foreach ($fields as $f) {
    unset($_SESSION['errors'][$f]); //clean errors
    if (isset($_POST[$f])) {
        $_SESSION['values'][$f] = htmlspecialchars($_POST[$f]); //renew saved
    }
}

//Using referer to check after redirect, to show saved session or cleanup
$_SESSION['referer'] = 'passenger_form_2.php';



//Check for all required
foreach ($fields as $f) {
    if (empty($_SESSION['values'][$f])) {
        $_SESSION['errors'][$f] = 'Required';
    }
}

//Check dates
$date_fields = array('birthdate', 'passportexp');
foreach ($date_fields as $f) {
    if (!validateDate($_SESSION['values'][$f])) {
        $_SESSION['errors'][$f] = 'Wrong date format';
        break;
    }
    if($f == 'birthdate') {
        $now = new DateTime();
        $bd = DateTime::createFromFormat('d/m/Y', $_SESSION['values'][$f]);
        if ($bd > $now) {
            $_SESSION['errors'][$f] = "Date can't be greater than current date";
        }
    }
}

//Check accepted values
if (empty($_SESSION['values']['gender'])) {
    $_SESSION['errors']['gender'] = 'Required';
} else if ($_SESSION['values']['gender'] != 'M' and $_SESSION['values']['gender'] != 'F') {
    $_SESSION['errors']['gender'] = 'Wrong value, please choose M or F';
}

if (empty($_SESSION['values']['type'])) {
    $_SESSION['errors']['type'] = 'Required';
} else if ($_SESSION['values']['type'] != 'P' and $_SESSION['values']['type'] != 'C') {
    $_SESSION['errors']['type'] = 'Wrong value, please select radiobutton P or C';
}

//Validate errors only for input on page1
$error_count = 0;
foreach ($_SESSION['errors'] as $e => $text) {
    if (in_array($e, $fields)) {
        $error_count++;
    }
}

//IF no valid - return to previous
if ($error_count > 0) {
    header("Location: passenger_form.php");
    exit();
}

if ($_SESSION['values']['type'] == 'P') {
    header('Location: passenger_form_4.php');
}

$fields = array(
    'street' => array(
        'type' => 'text',
        'header' => 'Street',
        'placeholder' => ''
    ),
    'city' => array(
        'type' => 'text',
        'header' => 'City',
        'placeholder' => 'City'
    ),
    'country' => array(
        'type' => 'text',
        'header' => 'Country',
        'placeholder' => 'Country'
    ),
    'zipcode' => array(
        'type' => 'text',
        'header' => 'Zipcode',
        'placeholder' => ''
    ),
    'phone_country_code' => array(
        'type' => 'text',
        'header' => 'Phone country code',
        'placeholder' => ''
    ),
    'phone_number' => array(
        'type' => 'text',
        'header' => 'Phone number',
        'placeholder' => 'Enter your number'
    ),
    'email' => array(
        'type' => 'text',
        'header' => 'Email',
        'placeholder' => 'Enter your email'
    ),
    'em_firstname' => array(
        'type' => 'text',
        'header' => 'Emergency First Name',
        'placeholder' => ''
    ),
    'em_lasttname' => array(
        'type' => 'text',
        'header' => 'Emergency Last Name',
        'placeholder' => ''
    ),
    'em_phone_country_code' => array(
        'type' => 'text',
        'header' => 'Emergency phone country code',
        'placeholder' => ''
    ),
    'em_phone_number' => array(
        'type' => 'text',
        'header' => 'Emergency phone number',
        'placeholder' => ''
    ),
    'customer_type' => array(
        'type' => 'radio',
        'header' => 'Customer Type',
        'variants' => array(
            ['value' => 'C', 'header' => 'C'],
            ['value' => 'M', 'header' => 'M'],
            ['value' => 'B', 'header' => 'B'],
        )
    )
);
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
        <form action="passenger_form_3.php" method="post">
            <h1>Please Enter Additional Information</h1>
            
            <?php displayForm($fields); ?>

            <button type="cancel" formaction="passenger_form.php">Back</button>
            <input type="submit" value="Next">

        </form>
    </div>
</body>

</html>