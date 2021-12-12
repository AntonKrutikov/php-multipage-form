<?php
require_once(__DIR__ . '/../../private/initialize.php');
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
    'passportexp'
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
//Select Insurance
$result = $con->query('SELECT ins_id, ins_name, "DESC", cost_per_pax FROM bxhs_ins');
if (!$result) {
    echo ($con->error);
};

//Back action
$back = 'passenger_form.php';

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
    <form action="passenger_form_3.php" method="post">
        <h1>Select Insurance</h1>
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $template = "<div class='insurance-row'><label for='insurance'>{$row['ins_name']}</label><span>{$row['cost_per_pax']}</span><input type='radio' name='insurance' value='{$row['ins_id']}'/></div>";
                echo ($template);
            }
        }
        ?>

        <button type="cancel" formaction="<?php echo($back); ?>">Back</button>
        <input type="submit" value="Next">

    </form>
</div>
</body>

</html>