<?php require_once(__DIR__.'/../private/initialize.php'); ?>

<!DOCTYPE html>
<html lang="en">

<head>
  <title>Navbar</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</head>
<body>
<style>
.navbar-inverse{
  margin-bottom: 0;
}
</style>
<nav class="navbar navbar-inverse">
  <div class="container-fluid">
    <div class="navbar-header">
      <a class="navbar-brand" href="<?php echo url_for('index.php'); ?>">Safe Fly Management Excellence</a>
    </div>
    <ul class="nav navbar-nav">
      <li <?php if ($_SERVER['SCRIPT_NAME'] == (url_for('index.php'))){?>class="active"<?php } ?>><a href="<?php echo url_for('index.php'); ?>">Home</a></li>
      <li <?php if ($_SERVER['SCRIPT_NAME'] == (url_for('aboutus.php'))){?>class="active"<?php } ?>><a href="<?php echo url_for('aboutus.php'); ?>">About Us</a></li>
      <li <?php if ($_SERVER['SCRIPT_NAME'] == (url_for('Contact.php'))){?>class="active"<?php } ?>><a href="<?php echo url_for('Contact.php'); ?>">Contact Info</a></li>
    </ul>
    <ul class="nav navbar-nav navbar-right">
      <li <?php if ($_SERVER['SCRIPT_NAME'] == (url_for('/customer/login.php'))) {?>class="active"<?php }?>><a href="<?php echo url_for('/customer/login.php'); ?>">Customer Portal</a></li>
      <li><a href="#">Employee Portal</a></li>
      <li><a href="#">Admin Portal</a></li>
    </ul>
  </div>
</nav>
</body>
</html>



