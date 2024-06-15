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

$db = new Conexion;

$status_courier = intval($_REQUEST['status_courier']);
$range = $_REQUEST['range'];
$agency = intval($_REQUEST['agency']);


$sWhere = "";


if ($status_courier > 0) {

    $sWhere .= " and  a.status_courier = '" . $status_courier . "'";
}

if ($agency > 0) {

    $db->cdp_query("SELECT * FROM cdb_branchoffices where id= '" . $agency . "'");
    $agencys = $db->cdp_registro();

    $agencyFilter = $agencys->name_branch;

    $sWhere .= " and a.agency = '" . $agency . "'";
} else {

    $agencyFilter = $lang['report-text54'];
}


if (!empty($range)) {

    $fecha =  explode(" - ", $range);
    $fecha = str_replace('/', '-', $fecha);

    $fecha_inicio = date('Y-m-d', strtotime($fecha[0]));
    $fecha_fin = date('Y-m-d', strtotime($fecha[1]));


    $sWhere .= " and  a.order_date between '" . $fecha_inicio . "'  and '" . $fecha_fin . "'";
}

$sql = "SELECT  a.total_declared_value , a.agency, a.origin_off, a.total_weight, a.sub_total, a.total_tax_discount, a.total_tax_insurance, a.total_tax_custom_tariffis, a.total_tax,  a.tracking_purchase, a.provider_purchase, a.price_purchase, a.status_invoice, a.total_order, a.order_id, a.order_prefix, a.order_no, a.order_date, a.sender_id, a.order_courier, a.order_pay_mode, a.status_courier, a.driver_id, a.order_service_options,  b.mod_style, b.color FROM
     cdb_customers_packages as a
     INNER JOIN cdb_styles as b ON a.status_courier = b.id
     $sWhere
      

     order by order_id desc 
     ";


$query_count = $db->cdp_query($sql);
$db->cdp_execute();
$numrows = $db->cdp_rowCount();


$db->cdp_query($sql);
$data = $db->cdp_registros();

$fecha = str_replace('-', '/', $fecha);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html dir="<?php echo $direction_layout; ?>">

<head>
    <meta http-equiv='Content-Type' content='text/html; charset=UTF-8' />
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="theme-color" content="#ffffff">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="assets/uploads/favicon.png">

    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700,900" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <title><?php echo $lang['report-text74'] ?></title>

    <link href="https://fonts.googleapis.com/css?family=Tajawal&subset=arabic" rel="stylesheet">
    <style>
        * {
            font-family: 'Tajawal';
        }
    </style>

    <style>
        body {
            font: 14px/1.4 Helvetica, Arial, sans-serif;
        }

        @media print {

            .no-print,
            .no-print * {
                display: none !important;
            }
        }

        table {
            table-layout: fixed;
            width: 250px;
        }

        th,
        td {
            border: 1px solid black;
            width: 100px;
            word-wrap: break-word;
        }

        /* Extra CSS for Print Button*/
        .button {
            display: -webkit-box;
            display: -webkit-flex;
            display: -ms-flexbox;
            display: flex;
            overflow: hidden;
            margin-top: 20px;
            padding: 12px 12px;
            cursor: pointer;
            -webkit-user-select: none;
            -webkit-transition: all 60ms ease-in-out;
            transition: all 60ms ease-in-out;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
            text-align: center;
            white-space: nowrap;
            text-decoration: none !important;

            color: #fff;
            border: 0 none;
            border-radius: 4px;
            font-size: 14px;
            font-weight: 500;
            line-height: 1.3;
            -webkit-appearance: none;
            -moz-appearance: none;

            -webkit-box-pack: center;
            -webkit-justify-content: center;
            -ms-flex-pack: center;
            justify-content: center;
            -webkit-box-align: center;
            -webkit-align-items: center;
            -ms-flex-align: center;
            align-items: center;
            -webkit-box-flex: 0;
            -webkit-flex: 0 0 160px;
            -ms-flex: 0 0 160px;
            flex: 0 0 160px;
        }

        .button:hover {
            -webkit-transition: all 60ms ease;
            transition: all 60ms ease;
            opacity: .85;
        }

        .button:active {
            -webkit-transition: all 60ms ease;
            transition: all 60ms ease;
            opacity: .75;
        }

        .button:focus {
            outline: 1px dotted #959595;
            outline-offset: -4px;
        }

        .button.-regular {
            color: #202129;
            background-color: #edeeee;
        }

        .button.-regular:hover {
            color: #202129;
            background-color: #e1e2e2;
            opacity: 1;
        }

        .button.-regular:active {
            background-color: #d5d6d6;
            opacity: 1;
        }

        .button.-dark {
            color: #FFFFFF;
            background: #333030;
        }

        .button.-dark:focus {
            outline: 1px dotted white;
            outline-offset: -4px;
        }
    </style>
</head>

<body>
    <div id="page-wrap">

        <h2><?php echo $core->site_name; ?><br>
            <?php echo $lang['report-text74'] ?> <br>

            [<?php echo $fecha[0] . ' - ' . $fecha[1]; ?>] <br>

            <?php echo $lang['report-text58'] ?>: <?php echo $agencyFilter; ?>
        </h2>


        <table>
            <tr>
                <th class="text-center"><b></b></th>
                <th class="text-center"><b><?php echo $lang['ltracking']; ?></b></th>
                <th class="text-center"><b><?php echo $lang['ddate']; ?></b></th>
                <th class="text-center"><b><?php echo $lang['report-text37'] ?></b></th>
                <th class="text-center"><b><?php echo $lang['lorigin']; ?></b></th>
                <th class="text-center"><b><?php echo $lang['lstatusshipment']; ?></b></th>
                <th class="text-center"><b><?php echo $lang['report-text52'] ?></b></th>
                <th class="text-center"><b><?php echo $lang['report-text43'] ?></b></th>
                <th class="text-center"><b><?php echo $lang['report-text44'] ?></b></th>
                <th class="text-center"><b><?php echo $lang['report-text48'] ?></b></th>
                <th class="text-center"><b><?php echo $lang['report-text49'] ?></b></th>
                <th class="text-center"><b><?php echo $lang['report-text51'] ?></b></th>
                <th class="text-center"><b><?php echo $lang['report-text50'] ?></b></th>
                <th class="text-center"><b><?php echo $lang['report-text42'] ?></b></th>
                <th class="text-center"><b></b></th>
            </tr>

            <?php

            if ($numrows > 0) {

                $count = 0;
                $sumador_weight = 0;
                $sumador_subtotal = 0;
                $sumador_discount = 0;
                $sumador_insurance = 0;
                $sumador_c_tariff = 0;
                $sumador_tax = 0;
                $sumador_total = 0;
                $sumador_declared_tax = 0;

                foreach ($data as $row) {

                    $db->cdp_query("SELECT * FROM cdb_users where id= '" . $row->sender_id . "'");
                    $sender_data = $db->cdp_registro();


                    $db->cdp_query("SELECT * FROM cdb_courier_com where id= '" . $row->order_courier . "'");
                    $courier_com = $db->cdp_registro();


                    $db->cdp_query("SELECT * FROM cdb_pre_alert where tracking= '" . $row->tracking_purchase . "'");
                    $package_prealert = $db->cdp_registro();


                    if ($row->status_invoice == 1) {
                        $text_status = $lang['invoice_paid'];
                        $label_class = "label-success";
                    } else if ($row->status_invoice == 0 || $row->status_invoice == 2) {
                        $text_status = $lang['invoice_pending'];
                        $label_class = "label-warning";
                    } else if ($row->status_invoice == 3) {
                        $text_status = $lang['verify_payment'];
                        $label_class = "label-info";
                    }



                    $db->cdp_query("SELECT * FROM cdb_address_shipments where order_track='" . $row->order_prefix . $row->order_no . "'");
                    $address_order = $db->cdp_registro();

                    $weight = $row->total_weight;
                    $sub_total = $row->sub_total;
                    $discount = $row->total_tax_discount;
                    $insurance = $row->total_tax_insurance;
                    $custom_c = $row->total_tax_custom_tariffis;
                    $tax = $row->total_tax;
                    $total = $row->total_order;
                    $total_declared_tax = $row->total_declared_value;

                    $sumador_weight += $weight;
                    $sumador_subtotal += $sub_total;
                    $sumador_discount += $discount;
                    $sumador_insurance += $insurance;
                    $sumador_c_tariff += $custom_c;
                    $sumador_tax += $tax;
                    $sumador_total += $total;
                    $sumador_declared_tax += $total_declared_tax;


                    $count++;


            ?>

                    <tr>
                        <td><b><?php echo $count; ?> </b></td>
                        <td><?php echo $row->order_prefix . $row->order_no; ?></td>
                        <td><?php echo $row->order_date; ?></td>
                        <td><?php echo $sender_data->fname . ' ' . $sender_data->lname; ?></td>
                        <td><?php echo $address_order->sender_country . ' ' . $address_order->sender_city; ?></td>
                        <td><?php echo $row->mod_style; ?></td>
                        <td><?php echo  $row->total_weight; ?></td>
                        <td><?php echo  cdb_money_format_bar($row->sub_total); ?></td>
                        <td><?php echo  cdb_money_format_bar($row->total_tax_discount); ?></td>
                        <td><?php echo  cdb_money_format_bar($row->total_tax_insurance); ?></td>
                        <td><?php echo  cdb_money_format_bar($row->total_tax_custom_tariffis); ?></td>
                        <td><?php echo  cdb_money_format_bar($row->total_tax); ?></td>
                        <td><?php echo  cdb_money_format_bar($row->total_declared_value); ?></td>
                        <td><?php echo  cdb_money_format($row->total_order); ?></td>
                        <td><?php echo $text_status; ?></td>
                    </tr>
                <?php
                }
                ?>

                <tr>
                    <td><b><?php echo $lang['report-text53'] ?></td> </b>
                    <td colspan="5"></td>
                    <td><b><?php echo  $sumador_weight; ?></b></td>
                    <td><b><?php echo  cdb_money_format($sumador_subtotal); ?></b></td>
                    <td><b><?php echo  cdb_money_format($sumador_discount); ?></b></td>
                    <td><b><?php echo  cdb_money_format($sumador_insurance); ?></b></td>
                    <td><b><?php echo  cdb_money_format($sumador_c_tariff); ?></b></td>
                    <td><b><?php echo  cdb_money_format($sumador_tax); ?></b></td>
                    <td><b><?php echo  cdb_money_format($sumador_declared_tax); ?></b></td>
                    <td><b><?php echo  cdb_money_format($sumador_total); ?></b></td>
                    <td></td>

                </tr>
            <?php
            }
            ?>


        </table>

        <button class='button -dark center no-print' onClick="window.print();" style="font-size:16px; margin-top: 20px;"><?php echo $lang['report-text5'] ?> &nbsp;&nbsp; <i class="fa fa-print"></i></button>
    </div>

</body>

</html>