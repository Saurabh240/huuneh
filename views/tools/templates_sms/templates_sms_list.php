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
	<!-- This Page CSS -->
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
								<span><?php echo $lang['tools-config61'] ?> | <?php echo $lang['left1125'] ?> </span>
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
						<div class="col-lg-12">
							<div class="row">
								<!-- Column -->
								<div class="col-lg-12 col-xl-12 col-md-12">
									<div class="card-body">


										<div class="col-md-4 pull-right m-b-20">

											<div class="input-group input-group">
												<div class="input-group-append">
													<button type="submit" class="btn btn-info"><i class="fa fa-search"></i></button>
												</div>
												<input type="text" name="search" id="search" class="form-control input-sm float-right" placeholder="<?php echo $lang['left1126'] ?>" onkeyup="cdp_load(1);">

											</div>
										</div><!-- /.col -->


										<div class="table-responsive-sm">

										<div class="outer_div"></div>

										</div>

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


		<script src="dataJs/templates_sms.js"></script>
</body>

</html>