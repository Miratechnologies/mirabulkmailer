<?php

session_start();
if (isset($_SESSION['USER.EMAIL'])) {
	exit(header("location: dashboard.php"));
}

$inputs = $_POST;

if (isset($inputs['forgot'])) {

   // Get Inputs
	$email = $inputs['email'];

	// validate the inputs
	include 'scripts/validation.php';
   $validation = new Validation();

	if ($validation->validateEmail($email,10,50) == true) {
      $email = $validation->sanitize($email);
	} else {
		exit(header('location: forgot.php?errmsg=Invalid Email'));
	}

	// generate new token
	$token = rand(0,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9);

	// get the auth detail from the database
	include 'scripts/dbmodel.php';
	$model = new DBModel();
	$update = $model->updateUserToken($email,$token);

	if ($update == true) {

		$sender = 'Obejor<bulk@nglohitech.com>';
		// send token to the user's email address
		mail($email, 'Password Reset Token', "Please use to the token below to verify your account.\r\n\r\n{$token}", 'From:'.$sender);
		// send email

		// redirect to the token verification page
		exit(header('location: verify.php?u='.$email));

	} else {
		exit(header('location: forgot.php?errmsg=Invalid Email Address'));
	}

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <meta http-equiv="X-UA-Compatible" content="ie=edge">
   <title>Forgot Password | BulkMailing</title>

   <link rel="shortcut icon" href="assets/imgs/favicon.png" type="image/x-icon"><!-- Jquery -->
   <script src="assets/js/jquery.min.js"></script>
   <!-- Popper -->
   <script src="assets/js/popper.min.js"></script>
   <!-- Bootstrap -->
   <script src="assets/js/bootstrap.min.js"></script>
   <link rel="stylesheet" href="assets/css/bootstrap.css">
   <!-- Font Awesome -->
	<link rel="stylesheet" href="assets/css/fontawesome.css">
   <!-- Custom -->
   <script src="assets/js/main.js"></script>
   <link rel="stylesheet" href="assets/css/main.css">
</head>
<body class="bg-white" onload="hidePassword();">

   <nav class="navbar navbar-expand-md navbar-dark obejor-bg-dark sticky-top">
		<a class="navbar-brand active ml-3 h1 mt-1" href="index.php"> <span class="fa fa-envelope-o"></span> Mira Bulk Mailing & SMS</a>
		<!-- <button class="navbar-toggler text-dark" type="button" data-toggle="collapse" data-target="#navbarText"
			aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
			<span class="navbar-toggler-icon"></span>
		</button> -->
	</nav>

   <section class="container-fluid my-5">

      <div class="display-4 text-center mt-5">Forgot Password</div>

		<div class="card col-md-3 mx-auto d-block mt-4">
			
			<div class="card-body">

				<?php 
					if (isset($_GET['errmsg']) == true) {
						echo "
						<div class='alert alert-danger alert-dismissible fade show'>
							<button type='button' class='close alert-dismissible' data-dismiss='alert'>&times;</button>
							<strong>Sorry!</strong> {$_GET['errmsg']}.
						</div>
						";
					}
				?>

				<form method="POST" action="forgot.php" autocomplete="off">
					<p class="text-center">Enter your email address below</p>
					<div class="input-group my-3">
						<div class="input-group-prepend">
							<span class="input-group-text obejor-bg-dark text-white">
								<i class="fa fa-envelope fa-sm"></i>
							</span>
						</div>
						<input type="text" class="form-control" placeholder="Email" name="email" required>
					</div>

					<div class="form-group">
						<button type="submit" name="forgot" class="btn btn-dark d-block mx-auto mt-3">
							Submit <i class="fa fa-lg fa-arrow-right"></i>
						</button>

						<p class="text-center mt-3"><a class="text-dark" href="index.php">Return to Login</a></p>
					</div>
				</form>

			</div>

		</div>

	</section>

	<?php include 'footer.php'; ?>

</body>
</html>