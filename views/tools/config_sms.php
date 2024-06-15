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



if (!$user->cdp_is_Admin())
	cdp_redirect_to("login.php");

$userData = $user->cdp_getUserData();

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
	<link rel="stylesheet" href="assets/template/assets/libs/intlTelInput/intlTelInput.css">

	<?php include 'views/inc/head_scripts.php'; ?>


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
			<div class="email-app">
				<!-- ============================================================== -->
				<!-- Left Part menu -->
				<!-- ============================================================== -->

				<?php include 'views/inc/left_part_menu.php'; ?>

				<!-- ============================================================== -->
				<!-- Right Part contents-->
				<!-- ============================================================== -->
				<div class="right-part mail-list bg-white mt-3">
					<div class="p-15 b-b">
						<div class="d-flex align-items-center">
							<div>
								<span><?php echo $lang['left1123'] ?></span>
							</div>

						</div>
					</div>
					<!-- Action part -->
					<!-- Button group part -->
					<div class="bg-light ">
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
										<!-- <div id="loader" style="display:none"></div> -->
										<!-- <div id="msgholder"></div> -->
										<form class="form-horizontal form-material" id="save_data" name="save_data" method="post">
											<header><b><?php echo $lang['left1114'] ?></b></header> <br><br>
											<section>
												<div class="row">
													<div class="col-md-12">
														<div class="form-group">
															<label for="firstName1"><?php echo $lang['left1121'] ?></label>
															<input type="text" class="form-control" name="twilio_sms_sid" id="twilio_sms_sid" placeholder="<?php echo $lang['left1121'] ?>" value="<?php echo $core->twilio_sms_sid; ?>">
														</div>
													</div>

												</div>

												<div class="row">
													<div class="col-md-12">
														<div class="form-group">
															<label for="firstName1"><?php echo $lang['left1119'] ?></label>
															<input type="text" class="form-control" name="twilio_sms_token" id="twilio_sms_token" placeholder="<?php echo $lang['left1119'] ?>" value="<?php echo $core->twilio_sms_token; ?>">
														</div>
													</div>

												</div>

												<div class="row">
													<div class="col-md-12">
														<div class="form-group">
															<label for="firstName1"><?php echo $lang['left1122'] ?></label>
															<input type="text" class="form-control" name="phone_custom" id="phone_custom" placeholder="+14155238886" value="<?php echo $core->twilio_sms_number; ?>">
															<span id="valid-msg" class="hide"></span>
															<div id="error-msg" class="hide text-danger"></div>
														</div>
													</div>

													<input type="hidden" name="twilio_sms_number" id="twilio_sms_number" value="<?php echo $core->twilio_sms_number; ?>" />


												</div>
											</section>

											<div class="form-group">
												<div class="col-sm-12">
													<button class="btn btn-primary btn-confirmation" name="dosubmit" type="submit"><?php echo $lang['leftorder228'] ?> <span><i class="icon-ok"></i></span></button>

												</div>
											</div>
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


		<script src="assets/template/assets/libs/intlTelInput/intlTelInput.js"></script>
		<script src="dataJs/config_twilio_sms.js"></script>

</body>

</html>