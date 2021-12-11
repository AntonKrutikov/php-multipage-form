<?php
session_start();
$_SESSION['values']['insurance'] = $_POST['insurance'];
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
    <form action="passenger_form_5.php" method="post">
        <h1>Payment</h1>
       
        <code><?php echo(print_r($_SESSION)); ?></code>

        <button type="cancel" formaction="passenger_form_4.php">Back</button>
        <input type="submit" value="Next">

    </form>
</div>

</html>