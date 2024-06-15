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

	<link href="assets/template/assets/libs/bootstrap-switch/dist/css/bootstrap3/bootstrap-switch.min.css" rel="stylesheet">
	<?php include 'views/inc/head_scripts.php'; ?>
	<link href="assets/template/dist/css/custom_swicth.css" rel="stylesheet">


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
								<span><?php echo $lang['leftorder190'] ?></span>
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

													<div class="table-responsive">
														<table class="table table-hover table-sm">
															<tbody>


																<tr class="card-hover">
																	<td class="" colspan="4">
																		<div class="form-group">
																			<label class="custom-control custom-checkbox" style="font-size: 18px;"> <b><?php echo $lang['left1124'] ?></b>
																			</label>
																		</div>
																	</td>

																	<td class="">
																		<div class="form-group">
																			<label class="custom-control custom-checkbox" style="font-size: 18px;">
																				<input type="checkbox" class="custom-control-input" name="active_sms" id="active_sms" value="1" <?php if ($core->active_sms == 1) {
																																													echo 'checked';
																																												} ?>>

																				<span class="custom-control-indicator"></span>
																			</label>
																		</div>
																	</td>

																</tr>

															</tbody>
														</table>
													</div>
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

		<script src="assets/template/dist/js/app-style-switcher.js"></script>
		<script src="assets/template/assets/libs/bootstrap-switch/dist/js/bootstrap-switch.min.js"></script>
		<script src="dataJs/sms_config.js"></script>
</body>

</html>