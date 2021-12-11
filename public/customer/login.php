<!DOCTYPE html>

<html lang="en">
   <head>
      <style>
      <?php include '../stylesheets/custLoginRegist.css'; ?>
      </style>
      <meta charset="utf-8">
      <title>Customer Login</title>
      <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
   </head>
	<body>
		<?php include __DIR__.'/../Navbar.php'; ?>
		<div class="CustomerLoginRegist">
			<h1>Customer Login</h1>
			<form action="authenticate.php" method="post">
				<label for="username">
					<i class="fas fa-user"></i>
				</label>
				<input type="text" name="username" placeholder="Username" id="username" required>
				<label for="password">
					<i class="fas fa-lock"></i>
				</label>
				<input type="password" name="password" placeholder="Password" id="password" required>
				<input type="submit" value="Login">
				<li><a href="<?php echo url_for('/customer/register.php'); ?>">Don't have an account? Click here to register!</a></li>
			</form>
		</div>
	</body>
</html>