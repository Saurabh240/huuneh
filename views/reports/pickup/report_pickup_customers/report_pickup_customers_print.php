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
$customer_id = intval($_REQUEST['customer_id']);


$sWhere = "";


if ($status_courier > 0) {

    $sWhere .= " and  a.status_courier = '" . $status_courier . "'";
}

if ($customer_id > 0) {

    $sWhere .= " and a.sender_id = '" . $customer_id . "'";
}


if (!empty($range)) {

    $fecha =  explode(" - ", $range);
    $fecha = str_replace('/', '-', $fecha);

    $fecha_inicio = date('Y-m-d', strtotime($fecha[0]));
    $fecha_fin = date('Y-m-d', strtotime($fecha[1]));


    $sWhere .= " and  a.order_date between '" . $fecha_inicio . "'  and '" . $fecha_fin . "'";
}

$sql = "SELECT a.total_declared_value, a.total_weight, a.total_tax_discount, a.sub_total, a.total_tax_insurance, a.total_tax_custom_tariffis, a.total_tax, a.status_invoice,  a.is_consolidate, a.is_pickup,  a.total_order, a.order_id, a.order_prefix, a.order_no, a.order_date, a.sender_id, a.order_courier,a.status_courier,  b.mod_style, b.color, a.delivery_type FROM
     cdb_add_order as a
     INNER JOIN cdb_styles as b ON a.status_courier = b.id
     $sWhere
      and a.is_pickup=1

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

    <title><?php echo $lang['report-text67']; ?></title>
    <link href="assets/custom_dependencies/print_report.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Tajawal&subset=arabic" rel="stylesheet">
    <style>
        * {
            font-family: 'Tajawal';
        }
    </style>
    <style type="text/css">
        table {
            table-layout: fixed;
            width: 250px;
        }
        th,
        td {
            border: 1px solid black;
            width: 150px;
            word-wrap: break-word;
        }
    </style>

</head>

<body>
    <div id="page-wrap">

        <h2><?php echo $core->site_name; ?><br>
            <?php echo $lang['report-text67']; ?> <br>

            [<?php echo $fecha[0] . ' - ' . $fecha[1]; ?>]</h2>


        <table>
            <tr>
						<th><b>Order Number</b></th>
						<th class="text-center"><b><?php echo $lang['ddate']; ?></b></th>
						<th class="text-center"><b>Order Type</b></th>
						<th class="text-center"><b><?php echo $lang['lstatusshipment']; ?></b></th>
						<th class="text-center"><b><?php echo $lang['report-text37'] ?> Name</b></th>
						<th class="text-center"><b>Sender Address</b></th>
						<th class="text-center"><b>Recipient Address</b></th>
						<th class="text-center"><b><?php echo $lang['report-text43']; ?></b></th>
						<th class="text-center"><b><?php echo $lang['report-text42']; ?></b></th>
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


                    $db->cdp_query("SELECT * FROM cdb_styles where id= '14'");
                    $status_style_pickup = $db->cdp_registro();

                    $db->cdp_query("SELECT * FROM cdb_styles where id= '13'");
                    $status_style_consolidate = $db->cdp_registro();

                    $db->cdp_query("SELECT * FROM cdb_address_shipments where order_track='" . $row->order_prefix . $row->order_no . "'");
                    $address_order = $db->cdp_registro();

                    if ($row->status_invoice == 1) {
                        $text_status = $lang['invoice_paid'];
                        $label_class = "label-success";
                    } else if ($row->status_invoice == 2) {
                        $text_status = $lang['invoice_pending'];
                        $label_class = "label-warning";
                    } else if ($row->status_invoice == 3) {
                        $text_status = $lang['invoice_due'];
                        $label_class = "label-danger";
                    }



                    $weight = $row->total_weight;
                    $sub_total = $row->sub_total;
                    $discount = $row->total_tax_discount;
                    $insurance = $row->total_tax_insurance;
                    $custom_c = $row->total_tax_custom_tariffis;
                    $tax = $row->total_tax;
                    $total = $row->total_order;
                    $total_declared_tax = $row->total_declared_value;

                    if ($row->status_courier != 21) {

                        $sumador_weight += $weight;
                        $sumador_subtotal += $sub_total;
                        $sumador_discount += $discount;
                        $sumador_insurance += $insurance;
                        $sumador_c_tariff += $custom_c;
                        $sumador_tax += $tax;
                        $sumador_total += $total;
                        $sumador_declared_tax += $total_declared_tax;
                    }

                    $count++;


            ?>

                      <tr class="card-hover">
							<td><b><a href="courier_view.php?id=<?php echo $row->order_id; ?>"><?php echo $row->order_prefix . $row->order_no; ?></a></b></td>
							<td class="text-center"><?php echo $row->order_date; ?></td>
							<td class="text-center"><?php echo $row->delivery_type; ?></td>
							<td class="text-center"><?php echo $row->mod_style; ?></td>
							<td class="text-center">
								<?php echo $sender_data->fname; ?> <?php echo $sender_data->lname; ?>
							</td>
								<td class="text-center"><?php echo $address_order->sender_address.', '.$address_order->sender_city.', '.$address_order->sender_state.', '.$address_order->sender_country.', '.$address_order->sender_zip_code; ?></td>
							<td class="text-center"><?php echo $address_order->recipient_address.', '.$address_order->recipient_country.', '.$address_order->recipient_state.', '.$address_order->recipient_country.', '.$address_order->recipient_zip_code; ?></td>
						
							<td class="text-center">
								<?php echo cdb_money_format($row->sub_total); ?>

							</td>
							<td class="text-center">
								 <?php echo cdb_money_format($row->total_order); ?>
							</td>
							
							
						</tr>
                <?php
                }
                ?>

                 <tr class="card-hover">
					<td class="text-center"><b><?php echo $lang['report-text53'] ?></b></td>
					<td colspan="6"></td>
					<td class="text-center">
						<b> <?php echo cdb_money_format($sumador_subtotal); ?> </b>
					</td>
					<td class="text-center">
						<b><?php echo cdb_money_format($sumador_total); ?> </b>
					</td>
				</tr>
            <?php
            }
            ?>


        </table>

        <button class='button -dark center no-print' onClick="window.print();" style="font-size:16px; margin-top: 20px;"><?php echo $lang['report-text5'] ?> &nbsp;&nbsp; <i class="fa fa-print"></i></button>
    </div>

</body>

</html>