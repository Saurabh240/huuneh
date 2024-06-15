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

$userData = $user->cdp_getUserData();
$db = new Conexion;

$sWhere = "";

if ($userData->userlevel == 3) {

	$sWhere .= " and  a.driver_id = '" . $_SESSION['userid'] . "'";
} else if ($userData->userlevel == 1) {

	$sWhere .= " and  a.sender_id = '" . $_SESSION['userid'] . "'";
} else {
	$sWhere .= "";
}

// Obtener el mes y el año actual
$month = date('m');
$year = date('Y');

// Obtener el número del mes actual
$currentMonth = date('n');

// Obtener el nombre del mes actual
$monthName = obtenerNombreMes($currentMonth);

?>
<!DOCTYPE html>
<html dir="<?php echo $direction_layout; ?>" lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="keywords" content="Courier DEPRIXA-Integral Web System" />
    <meta name="author" content="Jaomweb">
    <title><?php echo $lang['left-menu-sidebar-2'] ?> | <?php echo $core->site_name ?></title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="assets/<?php echo $core->favicon ?>">

    <?php include 'views/inc/head_scripts.php'; ?>
    <script src="assets/template/assets/extra-libs/chart.js-2.8/Chart.min.js"></script>

</head>

<body>
    <?php include 'views/inc/preloader.php'; ?>

    <div id="main-wrapper">
        <!-- ============================================================== -->
        <!-- Preloader - style you can find in spinners.css -->
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

        <div class="page-wrapper">
            <div class="page-breadcrumb">
                <div class="row">
                    <div class="col-5 align-self-center">
                        <h4 class="page-title"><?php echo $lang['left-menu-sidebar-2'] ?></h4>
                    </div>
                </div>
            </div>
            <!-- ============================================================== -->
            <!-- End Bread crumb and right sidebar toggle -->
            <!-- ============================================================== -->
            <!-- ============================================================== -->
            <!-- Container fluid  -->
            <!-- ============================================================== -->
            <div class="container-fluid">

                <!-- ============================================================== -->
                <!-- Earnings, Sale Locations -->
                <!-- ============================================================== -->

                <div class="row">

                <div class="col-md-8 col-12 col-xl-12 mb-4">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-sm-8 col-md-8 col-lg-8">

                                        <div class="card-header-title d-flex justify-content-between">
                                            <div class="card-title mb-6">
                                                <h5 class="m-0 me-2"><?php echo $lang['messagesform91'] ?></h5>
                                                <small class="text-muted"><?php echo $lang['messagesform92'] ?></small>
                                            </div>
                                        </div>
                                        <div><br></div>
                                        <div class="pb-0">
                                            <div class="row">
                                                <!-- Primer grupo de 3 elementos -->
                                                <div class="col-sm-6 col-md-6 col-lg-6">
                                                    <!-- Primer elemento contador de envios -->
                                                    <div class="col-lg-12 col-md-12 mb-2">
                                                        <div class="d-flex align-items-center">
                                                            <div class="m-r-10">
                                                                <a href="#">
                                                                    <span class="text-orange display-7">
                                                                        <i class="mdi mdi-package-variant-closed"></i>
                                                                    </span>
                                                                </a>
                                                            </div>

                                                            <div class="card-info-statics">
                                                              <h5 class="mb-0">
                                                                <?php
                                                                
                                                                    $sql = 'SELECT COUNT(*) as total FROM cdb_add_order WHERE  status_courier=11' . $sWhere;
                                                                    $db->cdp_query($sql);
                                                                    $db->cdp_execute();
                                                                    $count = $db->cdp_registro();
                                                                    echo $count->total;
                                                                    ?>            
                                                              </h5>
                                                              <small><?php echo $lang['dash-pending-orders'] ?></small>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- Segundo elemento contador de recogida envio -->
                                                    <div class="col-lg-12 col-md-12 mb-2">
                                                        <div class="d-flex align-items-center">
                                                            <div class="m-r-10"><a href="#"><span class="text-cyan display-7"><i class="mdi mdi-star-circlemdi mdi-clock-fast"></i></span> </a>
                                                            </div>

                                                            <div class="card-info-statics">
                                                              <h5 class="mb-0">
                                                                <?php
                                                                 $sql = 'SELECT COUNT(*) as total FROM cdb_add_order WHERE  status_courier=14' . $sWhere;
                                                                    $db->cdp_query($sql);
                                                                    $db->cdp_execute();
                                                                    $count = $db->cdp_registro();
                                                                    echo $count->total;
                                                                ?>            
                                                              </h5>
                                                              <small><?php echo $lang['dash-picked-up-orders'] ?></small>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- Tercer elemento contador de consolidados de envios-->
                                                    <div class="col-lg-12 col-md-12 mb-2">
                                                        <div class="d-flex align-items-center">
                                                            <div class="m-r-10"><a href="#"><span class="text-danger display-7"><i class="mdi mdi-gift"></i></span></a>
                                                            </div>

                                                            <div class="card-info-statics">
                                                              <h5 class="mb-0">
                                                                <?php
                                                                    $sql = 'SELECT COUNT(*) as total FROM cdb_add_order WHERE  status_courier=10' . $sWhere;
                                                                    $db->cdp_query($sql);
                                                                    $db->cdp_execute();
                                                                    $count = $db->cdp_registro();
                                                                    echo $count->total;
                                                                ?>           
                                                              </h5>
                                                              <small><?php echo $lang['dash-confirmed-orders'] ?></small>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                          
                                                <!-- Segundo grupo de 3 elementos -->
                                                <div class="col-sm-6 col-md-6 col-lg-6">
                                                    <!-- Cuarto elemento contador de cuentas por cobrar -->
                                                    <div class="col-lg-12 col-md-12 mb-2">
                                                        <div class="d-flex align-items-center">
                                                            <div class="m-r-10"><a href="#">
                                                                <span class="text-primary display-7"><i class="mdi mdi-package-down"></i></span></a>
                                                            </div>

                                                            <div class="card-info-statics">
                                                              <h5 class="mb-0">
                                                                <?php
                                                                    $sql = 'SELECT COUNT(*) as total FROM cdb_add_order WHERE  status_courier=8' . $sWhere;
                                                                    $db->cdp_query($sql);
                                                                    $db->cdp_execute();
                                                                    $count = $db->cdp_registro();
                                                                    echo $count->total;
                                                                ?>         
                                                              </h5>
                                                              <small><?php echo $lang['dash-delivered-orders'] ?></small>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- Quinto elemento contador de pre alertas -->
                                                    <!-- <div class="col-lg-12 col-md-12 mb-2">
                                                        <div class="d-flex align-items-center">
                                                            <div class="m-r-10"><a href="prealert_list.php"><span class="text-warning display-7"><i class="mdi mdi-clock-alert"></i></span></a>
                                                            </div>

                                                            <div class="card-info-statics">
                                                              <h5 class="mb-0">
                                                                 <?php
                                                                    $db->cdp_query('SELECT COUNT(*) as total FROM cdb_pre_alert where is_package=0');
                                                                    $db->cdp_execute();
                                                                    $count = $db->cdp_registro();
                                                                    echo $count->total;
                                                                ?>       
                                                              </h5>
                                                              <small><?php echo $lang['dash-general-5'] ?></small>
                                                            </div>
                                                        </div>
                                                    </div> -->
                                                    <!-- Sexto elemento de contador de paquetes -->
                                                    <!-- <div class="col-lg-12 col-md-12 mb-2">
                                                        <div class="d-flex align-items-center">
                                                            <div class="m-r-10"><a href="customer_packages_list.php"><span class="text-success display-7"><i class="fas fa-cube"></i></span></a>
                                                            </div>

                                                            <div class="card-info-statics">
                                                              <h5 class="mb-0">
                                                                 <?php
                                                                    $db->cdp_query('SELECT COUNT(*) as total FROM cdb_customers_packages');
                                                                    $db->cdp_execute();
                                                                    $count = $db->cdp_registro();
                                                                    echo $count->total;
                                                                ?> 
                                                              </h5>
                                                              <small><?php echo $lang['dash-general-661'] ?></small>
                                                            </div>
                                                        </div>
                                                    </div> -->
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="col-sm-4 col-md-4 col-lg-4">
                                        <div class="card-header-title d-flex justify-content-between">
                                            <div class="card-title mb-6">
                                                <h5 class="m-0 me-2"><?php echo $lang['messagesform97'] ?></h5>
                                                <small class="text-muted"><?php echo $lang['messagesform98'] ?></small>
                                            </div>
                                        </div>
                                        <div><br></div>
                                        <div class="pb-0">
                                            <ul class="p-0 m-0">
                                                <li class="d-flex mb-2">
                                                        <div class="avatar flex-shrink-0 me-3">
                                                            <span class="avatar-initial rounded bg-label-secondary">     <i class="ti ti-user-star ti-sm"></i>
                                                            </span>
                                                        </div>
                                                        <div class="card-user d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                                        <div class="me-2">
                                                            <h6 class="mb-0"><?php echo $lang['dash-general-14'] ?></h6>
                                                        </div>
                                                        <div class="user-progress d-flex align-items-center gap-3">
                                                          
                                                          <div class="d-flex align-items-center gap-1">
                                                            <small class="text-muted">
                                                                <?php
                                                                $db->cdp_query('SELECT COUNT(*) as total FROM cdb_users WHERE userlevel=9');
                                                                $db->cdp_execute();
                                                                $count = $db->cdp_registro();
                                                                echo $count->total;
                                                                ?>  
                                                            </small>
                                                          </div>
                                                        </div>
                                                    </div>
                                                </li>

                                                <li class="d-flex mb-2">
                                                        <div class="avatar flex-shrink-0 me-3">
                                                            <span class="avatar-initial rounded bg-label-secondary">     <i class="ti ti-users-group ti-sm"></i>
                                                            </span>
                                                        </div>
                                                        <div class="card-user d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                                        <div class="me-2">
                                                            <h6 class="mb-0"><?php echo $lang['dash-general-15'] ?></h6>
                                                        </div>
                                                        <div class="user-progress d-flex align-items-center gap-3">
                                                          
                                                          <div class="d-flex align-items-center gap-1">
                                                            <small class="text-muted">
                                                                <?php
                                                                $db->cdp_query('SELECT COUNT(*) as total FROM cdb_users WHERE userlevel=2');
                                                                $db->cdp_execute();
                                                                $count = $db->cdp_registro();
                                                                echo $count->total;
                                                                ?>  
                                                            </small>
                                                          </div>
                                                        </div>
                                                    </div>
                                                </li>

                                                <li class="d-flex mb-2">
                                                        <div class="avatar flex-shrink-0 me-3">
                                                            <span class="avatar-initial rounded bg-label-secondary">     <i class="ti ti-user-pin ti-sm"></i>
                                                            </span>
                                                        </div>
                                                        <div class="card-user d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                                        <div class="me-2">
                                                            <h6 class="mb-0"><?php echo $lang['dash-general-16'] ?></h6>
                                                        </div>
                                                        <div class="user-progress d-flex align-items-center gap-3">
                                                          
                                                          <div class="d-flex align-items-center gap-1">
                                                            <small class="text-muted">
                                                                <?php
                                                                $db->cdp_query('SELECT COUNT(*) as total FROM cdb_users WHERE userlevel=3');
                                                                $db->cdp_execute();
                                                                $count = $db->cdp_registro();
                                                                echo $count->total;
                                                                ?>  
                                                            </small>
                                                          </div>
                                                        </div>
                                                    </div>
                                                </li>

                                                <li class="d-flex mb-2">
                                                        <div class="avatar flex-shrink-0 me-3">
                                                            <span class="avatar-initial rounded bg-label-secondary">     <i class="ti ti-user-plus ti-sm"></i>
                                                            </span>
                                                        </div>
                                                        <div class="card-user d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                                        <div class="me-2">
                                                            <h6 class="mb-0"><?php echo $lang['dash-general-17'] ?></h6>
                                                        </div>
                                                        <div class="user-progress d-flex align-items-center gap-3">
                                                          
                                                          <div class="d-flex align-items-center gap-1">
                                                            <small class="text-muted">
                                                                <?php
                                                                $db->cdp_query('SELECT COUNT(*) as total FROM cdb_users WHERE userlevel=1');
                                                                $db->cdp_execute();
                                                                $count = $db->cdp_registro();
                                                                echo $count->total;
                                                                ?>  
                                                            </small>
                                                          </div>
                                                        </div>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- View sales -->
                    <div class="col-md-4 col-xl-12 mb-4 col-lg-4 col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex align-items-end row">
                                  <div class="col-8">
                                    <div class="text-nowrap">
                                      <h5 class="card-title mb-3"><?php echo $lang['messagesform84'] ?></h5>
                                      
                                      <h4 class="text-primary mb-2">
                                        <?php echo $core->currency; ?>
                                        <?php
                                            // Consulta SQL
                                            $sql = "SELECT IFNULL(SUM(total_order), 0) as total 
                                                    FROM cdb_add_order 
                                                    WHERE status_courier != 21 
                                                    AND status_invoice != 0 
                                                    AND order_payment_method > 1 
                                                    AND MONTH(order_date) = :month 
                                                    AND YEAR(order_date) = :year";

                                            // Preparar la consulta
                                            $db->cdp_query($sql);
                                            // Vincular parámetros
                                            $db->bind(':month', $month);
                                            $db->bind(':year', $year);
                                            // Ejecutar la consulta
                                            $db->cdp_execute();
                                            // Obtener el registro
                                            $count = $db->cdp_registro();
                                            // Mostrar el total de ventas
                                            echo cdb_money_format($count->total);
                                        ?>

                                      </h4>
                                      <a href="dashboard_admin_account.php" class="btn btn-primary"><?php echo $lang['messagesform83'] ?></a>
                                    </div>
                                  </div>
                                  <div class="col-4 text-center text-sm-left">
                                    <div class="card-body pb-0 px-0 px-md-4">
                                      <div class="m-r-10"><span class="text-primary display-6"><i class="mdi mdi-wallet"></i></span></div>
                                    </div>
                                  </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- View sales -->

                    <!-- Statistics -->
                    <!-- <div class="col-md-8 col-xl-12 mb-4 col-12">
                        <div class="card">
                            <div class="card-body">
                              <div class="d-flex justify-content-between mb-3">
                                <h4 class="card-title mb-1"><?php echo $lang['messagesform89'] ?></h4>
                                <small class="text-muted"><?php echo $lang['messagesform90'] ?> <?php echo $monthName; ?></small>
                              </div>

                            </div>
                            <div class="card-body">
                              <div class="row gy-3">
                                <div class="col-md-4 col-12">
                                  <div class="d-flex align-items-center">
                                    <div class="badge rounded-pill bg-label-info me-3 p-2">
                                      <i class="mdi mdi-star-circlemdi mdi-clock-fast ti-sm"></i>
                                    </div>
                                    <div class="card-info">
                                      <h5 class="mb-0">
                                        <?php echo $core->currency; ?>
                                        <?php
                                            $db->cdp_query('SELECT IFNULL(SUM(total_order),0) as total FROM cdb_add_order where status_courier != 21 and order_incomplete != 0 and is_pickup = 1
                                                AND MONTH(order_date) = :month 
                                                AND YEAR(order_date) = :year');
                                            // Vincular parámetros
                                            $db->bind(':month', $month);
                                            $db->bind(':year', $year);
                                            // Ejecutar la consulta
                                            $db->cdp_execute();
                                            // Obtener el registro
                                            $count = $db->cdp_registro();
                                            $sum2 = $count->total;
                                            echo cdb_money_format($sum2);
                                        ?>
                                      </h5>
                                      <small><?php echo $lang['dash-general-11'] ?></small>
                                    </div>
                                  </div>
                                </div>
                                <div class="col-md-4 col-12">
                                  <div class="d-flex align-items-center">
                                    <div class="badge rounded-pill bg-label-primary me-3 p-2">
                                      <i class="mdi mdi-package-variant-closed ti-sm"></i>
                                    </div>
                                    <div class="card-info">
                                      <h5 class="mb-0">
                                        <?php echo $core->currency; ?>
                                        <?php
                                        $db->cdp_query('SELECT IFNULL(SUM(total_order),0) as total FROM cdb_add_order where status_courier != 21 and is_pickup = 0
                                            AND MONTH(order_date) = :month 
                                            AND YEAR(order_date) = :year');
                                        // Vincular parámetros
                                        $db->bind(':month', $month);
                                        $db->bind(':year', $year);
                                        // Ejecutar la consulta
                                        $db->cdp_execute();
                                        // Obtener el registro
                                        $count = $db->cdp_registro();
                                        $sum1 = $count->total;
                                        echo cdb_money_format($sum1);
                                        ?>
                                      </h5>
                                      <small><?php echo $lang['dash-general-10'] ?></small>
                                    </div>
                                  </div>
                                </div>
                                <div class="col-md-4 col-12">
                                  <div class="d-flex align-items-center">
                                    <div class="badge rounded-pill bg-label-success me-3 p-2">
                                      <i class="mdi mdi-basket ti-sm"></i>
                                    </div>
                                    <div class="card-info">
                                      <h5 class="mb-0">
                                        <?php echo $core->currency; ?>

                                        <?php

                                        $db->cdp_query('SELECT IFNULL(SUM(total_order),0) as total FROM cdb_customers_packages where status_courier != 21
                                            AND MONTH(order_date) = :month 
                                            AND YEAR(order_date) = :year');
                                        // Vincular parámetros
                                        $db->bind(':month', $month);
                                        $db->bind(':year', $year);
                                        // Ejecutar la consulta
                                        $db->cdp_execute();
                                        // Obtener el registro
                                        $count1 = $db->cdp_registro();
                                        $sum3 = $count1->total;
                                        echo cdb_money_format($sum3);
                                        ?>
                                                       
                                      </h5>
                                      <small><?php echo $lang['messagesform85'] ?></small>
                                    </div>
                                  </div>
                                </div>
                                
                              </div>
                            </div>
                        </div>
                    </div> -->
                    <!--/ Statistics -->
                </div> 

                <!-- <div class="row">
                    <div class="col-xl-4 col-md-4 mb-2">
                      <div class="card">
                        <div class="card-body pb-4">
                            <div class="card-header-title d-flex justify-content-between">
                                <div class="card-title mb-0">
                                    <h5 class="m-0 me-2"><?php echo $lang['messagesform95'] ?></h5>
                                    <small class="text-muted"><?php echo $lang['messagesform96'] ?> <?php echo $monthName; ?></small>
                                </div>
                            </div>
                            <div><br></div>
                            <ul class="list-style-none">
                                <li class="mb-0">
                                    <div class="row">
                                        <div class="col-xl-7 col-md-7 mb-2">
                                            <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                                <div class="me-2">
                                                    <h6 class="mb-0"><?php echo $lang['dash-general-11'] ?></h6>
                                                    <small class="text-muted">
                                                        <?php echo $core->currency; ?>
                                                        <?php
                                                        // Ejecutar la consulta SQL para obtener el total de órdenes de compra
                                                        $db->cdp_query('SELECT IFNULL(SUM(total_order),0) as total FROM cdb_add_order where status_courier != 21 and order_incomplete != 0 and is_pickup = 1
                                                            AND MONTH(order_date) = :month 
                                                            AND YEAR(order_date) = :year');
                                                        // Vincular parámetros
                                                        $db->bind(':month', $month);
                                                        $db->bind(':year', $year);
                                                        // Ejecutar la consulta
                                                        $db->cdp_execute();
                                                        // Obtener el registro
                                                        $count = $db->cdp_registro();
                                                        $total_orders = $count->total;
                                                        echo cdb_money_format($total_orders);
                                                        ?>
                                                    </small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xl-5 col-md-5 mb-0">
                                            <div class="user-progress align-items-center gap-3">
                                                <div class="align-items-center gap-1">
                                                    <div class="progress m-t-10">
                                                        <?php
                                                        // Calcular el progreso actual del mes
                                                        $currentDay = date('j');
                                                        $totalDays = date('t');
                                                        $progressPercentage = ($total_orders / $totalDays) * 100; // Utiliza el total de órdenes en lugar del día actual para calcular el progreso
                                                        ?>
                                                        <div class="progress-bar bg-info" role="progressbar" style="width: <?php echo $progressPercentage; ?>%" aria-valuenow="<?php echo $total_orders; ?>" aria-valuemin="0" aria-valuemax="<?php echo $totalDays; ?>"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>

                                <li class="mb-0">
                                    <div class="row">
                                        <div class="col-xl-7 col-md-7 mb-2">
                                            <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                                <div class="me-2">
                                                    <h6 class="mb-0"><?php echo $lang['dash-general-10'] ?></h6>
                                                    <small class="text-muted">
                                                        <?php echo $core->currency; ?>
                                                        <?php
                                                        // Ejecutar la consulta SQL para obtener el total de órdenes de compra
                                                        $db->cdp_query('SELECT IFNULL(SUM(total_order),0) as total FROM cdb_add_order where status_courier != 21 and is_pickup = 0
                                                            AND MONTH(order_date) = :month 
                                                            AND YEAR(order_date) = :year');
                                                        // Vincular parámetros
                                                        $db->bind(':month', $month);
                                                        $db->bind(':year', $year);
                                                        // Ejecutar la consulta
                                                        $db->cdp_execute();
                                                        // Obtener el registro
                                                        $count = $db->cdp_registro();
                                                        $total_orders2 = $count->total;
                                                        echo cdb_money_format($total_orders2);
                                                        ?>
                                                    </small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xl-5 col-md-5 mb-0">
                                            <div class="user-progress align-items-center gap-3">
                                                <div class="align-items-center gap-1">
                                                    <div class="progress m-t-10">
                                                       <?php
                                                        // Calcular el progreso actual del mes
                                                        $currentDay = date('j');
                                                        $totalDays = date('t');
                                                        $progressPercentage = ($total_orders2 / $totalDays) * 100; // Utiliza el total de órdenes en lugar del día actual para calcular el progreso
                                                        ?>
                                                        <div class="progress-bar bg-label-blue" role="progressbar" style="width: <?php echo $progressPercentage; ?>%" aria-valuenow="<?php echo $total_orders2; ?>" aria-valuemin="0" aria-valuemax="<?php echo $totalDays; ?>"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>

                                <li class="mb-0">
                                    <div class="row">
                                        <div class="col-xl-7 col-md-7 mb-2">
                                            <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                                <div class="me-2">
                                                    <h6 class="mb-0"><?php echo $lang['messagesform94'] ?></h6>
                                                    <small class="text-muted">
                                                        <?php echo $core->currency; ?>
                                                        <?php
                                                            // Ejecutar la consulta SQL para obtener el total de órdenes de compra
                                                        $db->cdp_query('SELECT IFNULL(SUM(total_order),0) as total FROM cdb_consolidate where status_courier != 21
                                                            AND MONTH(c_date) = :month 
                                                            AND YEAR(c_date) = :year');
                                                        // Vincular parámetros
                                                        $db->bind(':month', $month);
                                                        $db->bind(':year', $year);
                                                        // Ejecutar la consulta
                                                        $db->cdp_execute();
                                                        // Obtener el registro
                                                        $count = $db->cdp_registro();
                                                        $total_orders3 = $count->total;
                                                        echo cdb_money_format($total_orders3);
                                                        ?>
                                                    </small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xl-5 col-md-5 mb-6">
                                            <div class="user-progress align-items-center gap-3">
                                                <div class="align-items-center gap-1">
                                                    <div class="progress m-t-10">
                                                        <?php
                                                        // Calcular el progreso actual del mes
                                                        $currentDay = date('j');
                                                        $totalDays = date('t');
                                                        $progressPercentage = ($total_orders3 / $totalDays) * 100; // Utiliza el total de órdenes en lugar del día actual para calcular el progreso
                                                        ?>
                                                        <div class="progress-bar bg-danger" role="progressbar" style="width: <?php echo $progressPercentage; ?>%" aria-valuenow="<?php echo $total_orders3; ?>" aria-valuemin="0" aria-valuemax="<?php echo $totalDays; ?>"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>


                                <li class="mb-0">
                                    <div class="row">
                                        <div class="col-xl-7 col-md-7 mb-2">
                                            <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                                <div class="me-2">
                                                    <h6 class="mb-0"><?php echo $lang['messagesform93'] ?></h6>
                                                    <small class="text-muted">
                                                        <?php echo $core->currency; ?>
                                                        <?php
                                                        // Ejecutar la consulta SQL para obtener el total de órdenes de compra
                                                        $db->cdp_query('SELECT IFNULL(SUM(total_order),0) as total FROM cdb_consolidate_packages where status_courier != 21
                                                            AND MONTH(c_date) = :month 
                                                            AND YEAR(c_date) = :year');
                                                        // Vincular parámetros
                                                        $db->bind(':month', $month);
                                                        $db->bind(':year', $year);
                                                        // Ejecutar la consulta
                                                        $db->cdp_execute();
                                                        // Obtener el registro
                                                        $count = $db->cdp_registro();
                                                        $total_orders4 = $count->total;
                                                        echo cdb_money_format($total_orders4);
                                                        ?>
                                                    </small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xl-5 col-md-5 mb-6">
                                            <div class="user-progress align-items-center gap-3">
                                                <div class="align-items-center gap-1">
                                                    <div class="progress m-t-10">
                                                        <?php
                                                        // Calcular el progreso actual del mes
                                                        $currentDay = date('j');
                                                        $totalDays = date('t');
                                                        $progressPercentage = ($total_orders4 / $totalDays) * 100; // Utiliza el total de órdenes en lugar del día actual para calcular el progreso
                                                        ?>
                                                        <div class="progress-bar bg-danger" role="progressbar" style="width: <?php echo $progressPercentage; ?>%" aria-valuenow="<?php echo $total_orders4; ?>" aria-valuemin="0" aria-valuemax="<?php echo $totalDays; ?>"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                      </div>
                    </div>
                    

                    
                </div> -->

                <div class="row">
                    <div class="col-12 col-sm-12 col-md-12">
                        <div class="card">
                            <div class="card-body">

                                <!-- title -->
                                <ul class="nav nav-pills custom-pills m-t-20" id="pills-tab2" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" id="pills-home-tab2" data-toggle="pill" href="#test11" role="tab" aria-selected="true"><h5 class="card-title mb-0"><?php echo $lang['dash-general-19'] ?></h5></a>
                                    </li>
                                    <!-- <li class="nav-item">
                                        <a class="nav-link" id="pills-profile-tab2" data-toggle="pill" href="pickup_list.php" role="tab" aria-selected="false"><h5 class="card-title mb-0"><?php echo $lang['dash-general-20'] ?></h5></a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="pills-profile-tab2" data-toggle="pill" href="consolidate_list.php" role="tab" aria-selected="false"><h5 class="card-title mb-0"><?php echo $lang['dash-general-21'] ?></h5></a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="pills-profile-tab" href="prealert_list.php">
                                            <h5 class="card-title mb-0"><?php echo $lang['dash-general-22'] ?></h5>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="pills-profile-tab" href="customer_packages_list.php">
                                            <h5 class="card-title mb-0"><?php echo $lang['dash-general-23'] ?></h5>
                                        </a>
                                    </li> -->
                                </ul>

                                <div class="tab-content  m-t-30" id="pills-tabContent">
                                    <div class="tab-pane fade show active" id="pills-shipment" role="tabpanel" aria-labelledby="pills-home-tab">

                                        <div class="col-md-12 mt-12 mb-12">
                                            <div class="input-group">
                                                <input type="text" name="search_shipment" id="search_shipment" class="form-control input-sm float-right" placeholder="<?php echo $lang['left21551'] ?>" onkeyup="cdp_load(1);">
                                                <div class="input-group-append input-sm">
                                                    <button type="submit" class="btn btn-info"><i class="fa fa-search"></i></button>
                                                </div>
                                            </div>
                                        </div>
                                        <div><br></div>

                                        <div class="results_shipments"></div>
                                    </div>
                                    <!-- <div class="tab-pane fade" id="pills-pickup" role="tabpanel" aria-labelledby="pills-profile-tab">

                                        <div class="col-md-4 mt-4 mb-4">
                                            <div class="input-group">
                                                <input type="text" name="search_pickup" id="search_pickup" class="form-control input-sm float-right" placeholder="<?php echo $lang['left21551'] ?>" onkeyup="cdp_load(1);">
                                                <div class="input-group-append input-sm">
                                                    <button type="submit" class="btn btn-info"><i class="fa fa-search"></i></button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="results_pickup"></div>

                                    </div>
                                    <div class="tab-pane fade" id="pills-consolidated" role="tabpanel" aria-labelledby="pills-contact-tab">
                                        <div class="col-md-4 mt-4 mb-4">
                                            <div class="input-group">
                                                <input type="text" name="search_consolidated" id="search_consolidated" class="form-control input-sm float-right" placeholder="<?php echo $lang['left21551'] ?>" onkeyup="cdp_load(1);">
                                                <div class="input-group-append input-sm">
                                                    <button type="submit" class="btn btn-info"><i class="fa fa-search"></i></button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="results_consolidated"></div>
                                    </div> -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php include 'views/inc/footer.php'; ?>
        </div>
    </div>




    <script src="dataJs/dashboard_index.js"></script>