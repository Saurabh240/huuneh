<?php
// *************************************************************************
// *                                                                       *
// * DEPRIXA PRO -  Integrated Web Shipping System                         *
// * Copyright (c) JAOMWEB. All Rights Reserved                            *
// *                                                                       *
// *************************************************************************
// *                                                                       *
// * Email: support@jaom.info                                              *
// * Website: http://www.jaom.info                                         *
// *                                                                       *
// *************************************************************************
// *                                                                       *
// * This software is furnished under a license and may be used and copied *
// * only  in  accordance  with  the  terms  of such  license and with the *
// * inclusion of the above copyright notice.                              *
// * If you Purchased from Codecanyon, Please read the full License from   *
// * here- http://codecanyon.net/licenses/standard                         *
// *                                                                       *
// *************************************************************************



require_once('helpers/querys.php');
$userData = $user->cdp_getUserData();

if (isset($_GET['id'])) {

	$row = cdp_getEmailTemplatesdg1i4($_GET['id']);
}


if (!isset($_GET['id']) or $row == null) {

	cdp_redirect_to("templates_email.php");
}


?>


<!DOCTYPE html>
<html dir="<?php echo $direction_layout; ?>" lang="en">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<!-- Tell the browser to be responsive to screen width -->
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="">
	<meta name="author" content="">
	<!-- Favicon icon -->
	<link rel="icon" type="image/png" sizes="16x16" href="assets/<?php echo $core->favicon ?>">
	<title><?php echo $lang['tools-config61'] ?> | <?php echo $core->site_name ?></title>
	<?php include 'views/inc/head_scripts.php'; ?>
	<link href="assets/template/assets/libs/summernote/dist/summernote-bs4.css" rel="stylesheet">

</head>

<body>

	<!-- ============================================================== -->
	<!-- Preloader - style you can find in spinners.css -->
	<!-- ============================================================== -->


	<?php include 'views/inc/preloader.php'; ?>
	<!-- ============================================================== -->
	<!-- Main wrapper - style you can find in pages.scss -->
	<!-- ============================================================== -->
	<div id="main-wrapper">
		<!-- ============================================================== -->
		<!-- Topbar header - style you can find in pages.scss -->
		<!-- ============================================================== -->

		<!-- ============================================================== -->
		<!-- Preloader - style you can find in spinners.css -->
		<!-- ============================================================== -->

		<?php include 'views/inc/topbar.php'; ?>

		<!-- End Topbar header -->


		<!-- Left Sidebar - style you can find in sidebar.scss  -->

		<?php include 'views/inc/left_sidebar.php'; ?>


		<!-- End Left Sidebar - style you can find in sidebar.scss  -->

		<!-- Page wrapper  -->
		<!-- ============================================================== -->
		<div class="page-wrapper">

			<!-- ============================================================== -->
			<!-- Start Page Content -->
			<!-- ============================================================== -->
			<div class="email-app mt-3">
				<!-- ============================================================== -->
				<!-- Left Part menu -->
				<!-- ============================================================== -->

				<?php include 'views/inc/left_part_menu.php'; ?>

				<div class="right-part mail-list bg-white ">

					<!-- Button group part -->
					<div class="bg-light">
						<div class="row justify-content-center">
							<div class="col-md-12">
								<div class="row">
									<div class="col-12">
										<!-- <div id="loader" style="display:none"></div> -->
										<div id="resultados_ajax"></div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<!-- Action part -->

					<div class="row justify-content-center">
						<div class="col-md-12">
							<div class="row">
								<!-- Column -->
								<div class="col-12">
									<div class="card-body">
										<div class="d-md-flex align-items-center">
						                    <div>
						                        <h3 class="card-title"><span><?php echo $lang['tools-template2'] ?> <i class="icon-double-angle-right"></i> <?php echo $row->name; ?></span></h3>
						                    </div>
						                </div>
						                <div><hr><br></div>


										<form class="form-horizontal form-material" id="save_data" name="save_data" method="post">
											
											<section>
												<div class="row">
													<div class="col-md-6">
														<div class="form-group">
															<label for="firstName1"><?php echo $lang['tools-template3'] ?></label>
															<input type="text" class="form-control required" name="name" id="name" value="<?php echo $row->name; ?>" placeholder="<?php echo $lang['tools-template3'] ?>">
														</div>
													</div>
													<div class="col-md-6">
														<div class="form-group">
															<label for="lastName1"><?php echo $lang['tools-template4'] ?></label>
															<input type="text" class="form-control required" name="subject" id="subject" value="<?php echo $row->subject; ?>" placeholder="<?php echo $lang['tools-template4'] ?>">
														</div>
													</div>
												</div>
												<hr>
												<div class="row">
													<div class="col-md-12">
														<div class="form-group">
															<label for="lastName1"><?php echo $lang['tools-template5'] ?></label>
															<textarea type="text" class="form-control required" name="help" id="help" rows="3"><?php echo $row->help; ?></textarea>
														</div>
													</div>
												</div>

												<div class="row">
													<div class="col-md-12">
														<div id="editor">
															<textarea class="summernote" name="body" style="margin-top: 30px;" placeholder="Type some text">
															<?php echo $row->body; ?>
														</textarea>
															<div class="label2 label-important"><?php echo $lang['tools-template6'] ?> [ ]</div>
														</div>
													</div>
												</div>
											</section>
											<br><br>
											<div class="form-group">
												<div class="col-sm-12">
													<button class="btn btn-outline-primary btn-confirmation" name="dosubmit" type="submit"><?php echo $lang['tools-template7'] ?> <span><i class="icon-ok"></i></span></button>
													<a href="templates_email.php" class="btn btn-outline-secondary btn-confirmation"><span><i class="ti-share-alt"></i></span> <?php echo $lang['tools-template8'] ?></a>
												</div>
											</div>
											<input name="id" id="id" type="hidden" value="<?php echo $_GET['id']; ?>" />
										</form>
									</div>
								</div>
								<!-- Column -->
							</div>
						</div>
					</div>


				</div>
				<?php include 'views/inc/footer.php'; ?>

			</div>
			<!-- ============================================================== -->
			<!-- End Page wrapper  -->
			<!-- ============================================================== -->
		</div>
		<!-- ============================================================== -->
		<!-- End Wrapper -->
		<!-- ============================================================== -->

		<?php include('helpers/languages/translate_to_js.php'); ?>

		<script src="assets/template/assets/libs/summernote/dist/summernote-bs4.min.js"></script>
		<script src="assets/template/assets/libs/summernote/dist/lang/summernote-ar-AR.js"></script>
		<script src="assets/template/assets/libs/summernote/dist/lang/summernote-es-ES.js"></script>
		<script src="assets/template/assets/libs/summernote/dist/lang/summernote-en-US.js"></script>


		<script>
			$(document).ready(function() {
				$('.summernote').summernote({
					lang: "<?php echo strtolower($config_lang) . '-' . strtoupper($config_lang); ?>"
				});
			});
		</script>

		<script src="dataJs/templates_email.js"></script>


</body>

</html>