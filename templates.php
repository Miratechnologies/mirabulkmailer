<?php

include_once 'scripts/auth.php';

include 'scripts/dbmodel.php';
$model = new DBModel();
// session_start();
$_SESSION['USER.ACTIVITY'][] = "templates - " . date("d/m/Y h:i a");
include 'scripts/log.php';

$templates = $model->getAllTemplates();

if ($templates['flag'] == true) {
	$allEmpty = false;
	$allTemplates = $templates['data'];
} else {
	$allEmpty = true;
}

$templates = $model->getAllEmailTemplates();
if ($templates['flag'] == true) {
	$emailEmpty = false;
	$emailTemplates = $templates['data'];
} else {
	$emailEmpty = true;
}

$templates = $model->getAllSmsTemplates();
if ($templates['flag'] == true) {
	$smsEmpty = false;
	$smsTemplates = $templates['data'];
} else {
	$smsEmpty = true;
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>Templates | BulkMailing</title>

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
	<script src="assets/js/templates.js"></script>
	<link rel="stylesheet" href="assets/css/main.css">
</head>
<body class="bg-white" onload="showAllTemplates();">

	<?php include 'header.php'; ?>

   <main class="row no-gutters">

		<?php $nav = "TEMPLATES"; include 'nav.php'; ?>

		<section class="col-lg-10 container-fluid">

			<div class="p-2 mx-0 my-0">
				<div class="mx-lg-5 mx-sm-auto text-dark display-4">
					Templates
				</div>
			</div>

			<div class="container">
				<div class="my-1 border p-1 rounded">
					<div class="form-row">
						<!-- filter section -->
						<div class="col-lg-3 col-sm-12 input-group my-1">
							<div class="input-group-prepend">
								<span class="btn obejor-bg-dark text-light font-weight-bold">
									<span class="fa fa-filter"></span>
									Filter
								</span>
							</div>
							
							<select id="filter" onchange="changeTemplates();" class="input-group-append text-md form-control w-0 border-muted">
								<option value="ALL">All</option>
								<option value="EMAIL">Email</option>
								<option value="SMS">SMS</option>
							</select>

						</div>

						<!-- Import Section -->
						<div class="col-lg-9 col-12">
							<a href="add_template.php" class="text-decoration-none btn btn-sm obejor-bg-dark text-white text-md float-right my-1 mt-lg-2 mr-lg-2">
								<span class="fa fa-plus-circle"></span>
								Add Template
							</a>
						</div>
					</div>
				</div>
			
				<?php

					$inputs = $_GET;

					if (isset($inputs['addtemplate'])) {
						$addTemplateStatus = $inputs['addtemplate'];
						
						if ($addTemplateStatus == 'true') {
							echo "
							<div class='alert alert-success alert-dismissible'>
								<button type='button' class='close' data-dismiss='alert'>&times;</button>
								<strong>Success!</strong> Template was added successeccfully.
							</div>
							";
						} elseif ($addTemplateStatus == 'false') {
							echo "
								<div class='alert alert-danger alert-dismissible fade show'>
									<button type='button' class='close' data-dismiss='alert'>&times;</button>
									<strong>Sorry!</strong> Template was not added.
								</div>
							";
						}
					}

					if (isset($inputs['updatetemplate'])) {
						$updateTemplateStatus = $inputs['updatetemplate'];
						
						if ($updateTemplateStatus == 'true') {
							echo "
							<div class='alert alert-success alert-dismissible'>
								<button type='button' class='close' data-dismiss='alert'>&times;</button>
								<strong>Success!</strong> Template was updated successeccfully.
							</div>
							";
						} elseif ($updateTemplateStatus == 'false') {
							echo "
								<div class='alert alert-danger alert-dismissible'>
									<button type='button' class='close' data-dismiss='alert'>&times;</button>
									<strong>Sorry!</strong> Template was not updated.
								</div>
							";
						}
					}

					if (isset($inputs['deleteTemplate'])) {
						$deleteTemplateStatus = $inputs['deleteTemplate'];
						
						if ($deleteTemplateStatus == 'true') {
							echo "
							<div class='alert alert-success alert-dismissible'>
								<button type='button' class='close' data-dismiss='alert'>&times;</button>
								<strong>Success!</strong> Template was deleted successeccfully.
							</div>
							";
						} elseif ($deleteTemplateStatus == 'false') {
							echo "
								<div class='alert alert-danger alert-dismissible'>
									<button type='button' class='close' data-dismiss='alert'>&times;</button>
									<strong>Sorry!</strong> Template was not deleted.
								</div>
							";
						}
					}

				?>

				<!-- Display Template Section -->
				
				<div id="allTemplates">
					<div class="row no-gutters">
						<?php 
							$role = $_SESSION['USER.ROLE'];

							if ($allEmpty == false) {
								$count = 1;
								foreach ($allTemplates as $template) {
									$delete = ($role == "ADMIN") ? "<a href='scripts/delete_template.php?template={$template['template_id']}' class='btn btn-sm obejor-bg-dark border text-white' title='Delete'><span class='fa fa-trash'></span></a>" : "" ;
									$template_type = $template['template_type'];
									$description = $template['description'];
									echo <<<HTML
									<div class='col-lg-3 col-11 my-2 mx-4'>

										<div class='card'>
											<span class='position-absolute badge badge-info m-2 p-2'>{$template['template_type']}</span>
											<span class='fa fa-envelope-o fa-5x text-center m-5'></span>
											
											<div class='card-body bg-dark text-white'>
												<div class='card-text text-left'>
													{$template['description']}
												</div>
												
												<textarea id='all_code_body{$count}' class='d-none'>{$template['body']}</textarea>
												
												<div class='btn-group my-2'>
													<button class='btn btn-sm obejor-bg-dark border text-white' onclick='saveAsDraft("$template_type","$description","all_code_body{$count}");'>Save As Draft</button>

													<button class='btn btn-sm obejor-bg-dark border text-white' onclick='previewTemplateCode("all_code_body{$count}");' data-toggle='modal' data-target='#previewBody'>Preview</button>

													<a href="edit_template.php?template={$template['template_id']}" class='btn btn-sm obejor-bg-dark border text-white' title='Edit'><span class='fa fa-edit'></span></a>

													{$delete}
												</div>
											</div>
										</div>

									</div>
HTML;
									$count++;
								}
							} else {
								echo "
								<div class='alert alert-info w-100 '>
									No Templates available
								</div>
								";
							}
						?>
					</div>
				</div>

				<div id="emailTemplates">
					<div class="row no-gutters">
						<?php 

							$role = $_SESSION['USER.ROLE'];

							if ($emailEmpty == false) {
								$count = 1;
								foreach ($emailTemplates as $template) {
									$delete = ($role == "ADMIN") ? "<a href='scripts/delete_template.php?template={$template['template_id']}' class='btn btn-sm obejor-bg-dark border text-white' title='Delete'><span class='fa fa-trash'></span></a>" : "" ;
									$template_type = $template['template_type'];
									$description = $template['description'];
									echo <<<HTML
									<div class='col-lg-3 col-11 my-2 mx-4'>

										<div class='card'>
											<span class='position-absolute badge badge-info m-2 p-2'>{$template['template_type']}</span>
											<span class='fa fa-envelope-o fa-5x text-center m-5'></span>
											
											<div class='card-body bg-dark text-white'>
												<div class='card-text text-left'>
													{$template['description']}
												</div>
												
												<textarea id='email_code_body{$count}' class='d-none'>{$template['body']}</textarea>
												
												<div class='btn-group my-2'>
													<button class='btn btn-sm obejor-bg-dark border text-white' onclick='saveAsDraft("$template_type","$description","email_code_body{$count}");'>Save As Draft</button>

													<button class='btn btn-sm obejor-bg-dark border text-white' onclick='previewTemplateCode("email_code_body{$count}");' data-toggle='modal' data-target='#previewBody'>Preview</button>

													<a href="edit_template.php?template={$template['template_id']}" class='btn btn-sm obejor-bg-dark border text-white' title='Edit'><span class='fa fa-edit'></span></a>

													{$delete}
												</div>

											</div>
										</div>

									</div>
HTML;
									$count++;
								}
							} else {
								echo "
								<div class='alert alert-info w-100 '>
									No Templates available
								</div>
								";
							}
						?>
					</div>
				</div>

				<div id="smsTemplates">
					<div class="row no-gutters">
						<?php 

							$role = $_SESSION['USER.ROLE'];

							if ($smsEmpty == false) {
								$count = 1;
								foreach ($smsTemplates as $template) {
									$delete = ($role == "ADMIN") ? "<a href='scripts/delete_template.php?template={$template['template_id']}' class='btn btn-sm obejor-bg-dark border text-white' title='Delete'><span class='fa fa-trash'></span></a>" : "" ;
									$template_type = $template['template_type'];
									$description = $template['description'];
									echo <<<HTML
									<div class='col-lg-3 col-11 my-2 mx-4'>

										<div class='card'>
											<span class='position-absolute badge badge-info m-2 p-2'>{$template['template_type']}</span>
											<span class='fa fa-envelope-o fa-5x text-center m-5'></span>
											
											<div class='card-body bg-dark text-white'>
												<div class='card-text text-left'>
													{$template['description']}
												</div>
												
												<textarea id='sms_code_body{$count}' class='d-none'>{$template['body']}</textarea>
												
												<div class='btn-group my-2'>
													<button class='btn btn-sm obejor-bg-dark border text-white' onclick='saveAsDraft("$template_type","$description","sms_code_body{$count}");'>Save As Draft</button>

													<button class='btn btn-sm obejor-bg-dark border text-white' onclick="previewTemplateCode('sms_code_body{$count}');" data-toggle='modal' data-target='#previewBody'>Preview</button>

													<a href="edit_template.php?template={$template['template_id']}" class='btn btn-sm obejor-bg-dark border text-white' title='Edit'><span class='fa fa-edit'></span></a>

													{$delete}
												</div>

											</div>
										</div>

									</div>
HTML;
									$count++;
								}
							} else {
								echo "
								<div class='alert alert-info w-100 '>
									No Templates available
								</div>
								";
							}
						?>
					</div>
				</div>
					

				<!-- Modal -->
            <div class="modal fade" id="previewBody" tabindex="-1" role="dialog" aria-hidden="true">
               <div class="modal-dialog modal-xl" role="document">
                  <div class="modal-content">
                     <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">Preview Body</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                           <span aria-hidden="true">&times;</span>
                        </button>
                     </div>

                     <div class="modal-body table-responsive w-100" id="bodyPreview" style="height: 1000px;" >
                     </div>

                  </div>
               </div>
            </div>

			</div>
			
		</section>

	</main>

	<?php include 'footer.php'; ?>

</body>
</html>