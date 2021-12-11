<?php
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
    'status'
);
//Store provided values in session too, to return correct values to user back
foreach ($fields as $f) {
    unset($_SESSION['errors'][$f]); //clean errors
    if (isset($_POST[$f])) {
        $_SESSION['values'][$f] = $_POST[$f]; //renew saved
    }
}

//Using referer to check after redirect, to show saved session or cleanup
$_SESSION['referer'] = 'passenger_form_2.php';

function validateDate($date, $format = 'd/m/Y')
{
    $d = DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) == $date;
}

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
    }
}

//Check accepted values
if (empty($_SESSION['values']['gender'])) {
    $_SESSION['errors']['gender'] = 'Required';
} else if ($_SESSION['values']['gender'] != 'M' and $_SESSION['values']['gender'] != 'F') {
    $_SESSION['errors']['gender'] = 'Wrong value, please choose M or F';
}

if (empty($_SESSION['values']['status'])) {
    $_SESSION['errors']['status'] = 'Required';
} else if ($_SESSION['values']['status'] != 'P' and $_SESSION['values']['status'] != 'C') {
    $_SESSION['errors']['status'] = 'Wrong value, please select radiobutton P or C';
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

$page2_fields = array(
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
<div class="container">
    <style>
        <?php include '../stylesheets/contact_page.css'; ?><link href="style.css"rel="stylesheet"type="text/css"><link rel="stylesheet"href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
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
        <?php foreach ($page2_fields as $f => $opt) {
            if ($opt['type'] == 'text') {
                $template = <<<EOL
          <label for="$f">{$opt['header']}</label><span class="error">{$_SESSION['errors'][$f]}</span>
          <input type="text" id="$f" name="$f" placeholder="{$opt['placeholder']}" value="{$_SESSION['values'][$f]}">      
          EOL;
            } else if ($opt['type'] == 'radio') {
                $template = <<<EOL
                <label for="status">{$opt['header']}</label><span class="error">{$_SESSION['errors'][$f]}</span>
                <div class="radio-group">
                EOL;
                foreach ($opt['variants'] as $v) {
                    $template .= "<input type='radio' name='$f' value='{$v['value']}'";
                    if ($_SESSION['values'][$f] == $v['value']) {
                        $template .= "checked";
                    }
                    $template .= "> {$v['header']}";
                }
                $template .= '</div>';
            }
            echo ($template);
        }
        ?>

        <button type="cancel" formaction="passenger_form.php">Back</button>
        <input type="submit" value="Next">

    </form>
</div>

</html>