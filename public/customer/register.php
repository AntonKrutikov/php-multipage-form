<!DOCTYPE html>

<html lang="en">
   <head>
      <style>
      <?php include '../stylesheets/custLoginRegist.css'; ?>
      </style>
      <meta charset="utf-8">
      <title>Customer Register</title>
      <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
   </head>
	<body>
		<?php include __DIR__.'/../Navbar.php'; ?>
		<div class="CustomerLoginRegist">
			<h1>Customer Account Register</h1>
			<form action="register_access.php" method="post" autocomplete="off">
				<label for="username">
					<i class="fas fa-user"></i>
				</label>
				<input type="text" name="username" placeholder="Username" id="username" required>
				<label for="password">
					<i class="fas fa-lock"></i>
				</label>
				<input type="password" name="password" placeholder="Password" id="password" required>
				<label for="email">
					<i class="fas fa-envelope"></i>
				</label>
				<input type="email" name="email" placeholder="Email" id="email" required>
				<input type="submit" value="Register">
				<li><a href="<?php echo url_for('/customer/login.php'); ?>">Back to Login</a></li>
			</form>
		</div>
	</body>
</html>