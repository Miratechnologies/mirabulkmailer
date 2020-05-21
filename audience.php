<?php

include_once 'scripts/auth.php';
include_once 'scripts/restrict_admin.php';

include 'scripts/dbmodel.php';
$model = new DBModel();
// session_start();
$_SESSION['USER.ACTIVITY'][] = "add template - " . date("d/m/Y h:i a");
include 'scripts/log.php';

$audiences = $model->getAllAudiences();
if ($audiences['flag'] == true) {
	$empty = false;
	$audience = $audiences['data'];
} else {
	$empty = true;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>Audience | BulkMailing</title>

	<link rel="shortcut icon" href="assets/imgs/miraicon.png" type="image/x-icon"><!-- Jquery -->
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
	<script src="assets/js/audience.js"></script>
	<link rel="stylesheet" href="assets/css/main.css">
</head>
<body class="bg-white" onload="startAudience();">

	<?php include 'header.php'; ?>

   <main class="row no-gutters">

		<?php $nav = "AUDIENCE"; include 'nav.php'; ?>

		<section class="col-lg-10 container-fluid">

			<div class="bg-white p-2 mx-0 my-0">
				<div class="mx-lg-5 mx-sm-auto text-dark display-4">
					Audience
				</div>
			</div>

			<div class="container">

				<div class="my-1 border p-1 rounded">
					<div class="form-row">
						<!-- filter section -->
						<div class="col-lg-3 col-sm-12 input-group my-1">
							<span class="h3 text-dark font-weight-normal px-2">Add New Audience</span>
						</div>

						<!-- Import Section -->
						<div class="col-lg-9 col-12 my-1">
							<label id="labelImportFromExcel" for="importFromExcel" class="m-0 btn btn-sm obejor-bg-dark text-light text-lg float-right cursor-pointer">
								<span class="fa fa-download"></span>
								Import Audience from Excel file
							</label>
							<div id="labelImporting" class="m-0 btn btn-sm obejor-bg-light text-light text-lg float-right cursor-notAllowed">
								<span class="fa fa-spinner fa-spin"></span>
								Importing...
							</div>
							<input type="file" id="importFromExcel" accept="" class="d-none" onchange="uploadExcelFile()">
						</div>
					</div>
					<!-- <br> -->
					
					<form action="api/register_audience.php" method="post">
						<div class="form-row">
							<div class="col-lg-3 col-sm-12 input-group my-1">
								<input type="text" placeholder="Firstname" name="firstname" class="form-control">
							</div>
							<div class="col-lg-3 col-sm-12 input-group my-1">
								<input type="text" placeholder="Lastname" name="lastname" class="form-control">
							</div>
							<div class="col-lg-3 col-sm-12 input-group my-1">
								<input type="text" placeholder="Email" name="email" class="form-control">
							</div>
							<div class="col-lg-3 col-sm-12 input-group my-1">
								<input type="text" placeholder="Telephone" name="telephone" class="form-control">
							</div>
							<input type="hidden" name="classification" value="CUSTOMER">
							<input type="hidden" name="request" value="Application">
						</div>

						<button type="submit" class="btn btn-sm obejor-bg-dark text-light my-2 float-right"><span class="fa fa-plus"></span> Add Audience</button>
					</form>
				
				</div>
				
				<div class="table-responsive">

				<?php 
					if (isset($_GET['succmsg'])) {
						echo "
						<div class='alert alert-success alert-dismissible fade show'>
							<button type='button' class='close alert-dismissible' data-dismiss='alert'>&times;</button>
							<strong>Success!</strong> {$_GET['succmsg']}
						</div>
						";
					} elseif (isset($_GET['errmsg'])) {
						echo "
						<div class='alert alert-danger alert-dismissible fade show'>
							<button type='button' class='close alert-dismissible' data-dismiss='alert'>&times;</button>
							<strong>Sorry!</strong> {$_GET['errmsg']}
						</div>
						";
					}
				?>

					<table class="table table-bordered text-center text-secondary">
						<thead class="obejor-text-dark">
							<tr>
								<th>#</th>
								<th>Firstname</th>
								<th>Lastname</th>
								<th>Email</th>
								<th>Telephone</th>
								<th>Classification</th>
								<th>Subscription&nbsp;Status</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody>
							<?php 
								if ($empty == false) {
									$count = 1;

									foreach ($audience as $aud) {
										echo "<tr class='text-dark'>
											<td>{$count}</td>
											<td>{$aud['firstname']}</td>
											<td>{$aud['lastname']}</td>
											<td>{$aud['email']}</td>
											<td>{$aud['telephone']}</td>
											<td>{$aud['classification']}</td>
											<td>{$aud['subscription_status']}</td>
											<td>
												<button class='btn btn-sm obejor-bg-dark text-light' onclick='removeAudience({$aud['audience_id']})'><span class='fa fa-trash'></span> Remove</button>
											</td>
										</tr>";

										$count++;
									}
								} else {
									echo '<tr>
										<td colspan="8">No Audience Exists</td>
									</tr>';
								} 
							?>
						</tbody>
					</table>
				</div>

			</div>

			
		</section>

	</main>

	<?php include 'footer.php'; ?>

</body>
</html>