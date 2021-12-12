<?php
require_once(__DIR__ . '/../../private/functions.php');
session_start();
$_SESSION['values']['insurance'] = $_POST['insurance'];
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
    <form action="passenger_form_save.php" method="post">
        <h1>Check Information</h1>
       <?php
        foreach($_SESSION['values'] as $k=>$v) {
            $template = "<div class='final-row'><span class='final-row-name'>$k</span><span class='final-row-value'>$v</span></div>";
            echo($template);
        }
       ?>

        <button type="cancel" formaction="passenger_form_4.php">Back</button>
        <input type="submit" value="Save and Pay">

    </form>
</div>
</body>

</html>