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



require_once("../../loader.php");

$db = new Conexion;
$user = new User;
$core = new Core;
$userData = $user->cdp_getUserData();

$search = cdp_sanitize($_REQUEST['search']);

$sWhere = "";

if ($userData->userlevel == 3) {

	$sWhere .= " and  a.driver_id = '" . $_SESSION['userid'] . "'";
} else if ($userData->userlevel == 1) {

	$sWhere .= " and  a.sender_id = '" . $_SESSION['userid'] . "'";
} else {
	$sWhere .= "";
}
if ($search != null) {
    $sWhere .= " AND (CONCAT(u.fname, ' ', u.lname) LIKE '%" . $search . "%')";
}

// // pagination variables
$page = (isset($_REQUEST['page']) && !empty($_REQUEST['page'])) ? $_REQUEST['page'] : 1;
$per_page = 10; //how much records you want to show
$adjacents  = 4; //gap between pages after number of adjacents
$offset = ($page - 1) * $per_page;


$sql = "SELECT  a.is_consolidate,a.sub_total,a.notes,a.delivery_type,a.distance, a.order_incomplete, a.status_invoice, a.is_pickup, a.total_order, a.order_id, a.order_prefix, a.order_no, a.order_date, a.sender_id, a.receiver_id, a.order_courier, a.order_pay_mode, a.status_courier, a.driver_id, a.order_service_options, a.total_order,  b.mod_style, b.color, 
			 u.username, u.fname, u.lname FROM
			 cdb_add_order as a
			 LEFT JOIN cdb_users as u ON a.sender_id = u.id
			 INNER JOIN cdb_styles as b ON a.status_courier = b.id
			 $sWhere and a.is_pickup=1 
			 order by order_id desc
			 ";

$query_count = $db->cdp_query($sql);
$db->cdp_execute();
$numrows = $db->cdp_rowCount();


$db->cdp_query($sql . " limit $offset, $per_page");
$data = $db->cdp_registros();

$total_pages = ceil($numrows / $per_page);

if ($numrows > 0) { ?>
	<div class="table-responsive">


		<table id="zero_config" class=" table table-condensed table-hover table-striped">
			<thead>
				<tr>
					<th><b>Order Number</b></th>
					<th class="text-center"><b>Order Date</b></th>
					<th class="text-center"><b>Type of Order</b></th>
					<th class="text-center"><b>Sender Name</b></th>
					<th class="text-center"><b>Pick up Address</b></th>
					<th class="text-center"><b>Receiver Name</b></th>
					<th class="text-center"><b>Drop off Address</b></th>
					<th class="text-center"><b>Total KM</b></th>
					<th class="text-center"><b>Subtotal</b></th>
					<th class="text-center"><b>Total</b></th>
					<th class="text-center"><b>Status</b></th>
					<th class="text-center"><b>Notes</b></th>
					
					<th></th>
					<?php if ($userData->userlevel != 1) { ?>
						<th class="text-center"><b><?php
													?></b></th>
					<?php } ?>
				</tr>
			</thead>
			<tbody id="projects-tbl">


				<?php if (!$data) { ?>
					<tr>
						<td colspan="6">
							<?php echo "
				<i align='center' class='display-3 text-warning d-block'><img src='assets/images/alert/ohh_shipment.png' width='150' /></i>								
				", false; ?>
						</td>
					</tr>
				<?php } else { ?>
					<?php foreach ($data as $row) {

						$db->cdp_query("SELECT * FROM cdb_users where id= '" . $row->sender_id . "'");
						$sender_data = $db->cdp_registro();

						$db->cdp_query("SELECT * FROM cdb_recipients where id= '" . $row->receiver_id . "'");
						$receiver_data = $db->cdp_registro();

						$db->cdp_query("SELECT * FROM cdb_users where id= '" . $row->driver_id . "'");
						$driver_data = $db->cdp_registro();

						$db->cdp_query("SELECT * FROM cdb_courier_com where id= '" . $row->order_courier . "'");
						$courier_com = $db->cdp_registro();

						$db->cdp_query("SELECT * FROM cdb_met_payment where id= '" . $row->order_pay_mode . "'");
						$met_payment = $db->cdp_registro();

						$db->cdp_query("SELECT * FROM cdb_shipping_mode where id= '" . $row->order_service_options . "'");
						$order_service_options = $db->cdp_registro();

						$db->cdp_query("SELECT * FROM cdb_styles where id= '14'");
						$status_style_pickup = $db->cdp_registro();

						$db->cdp_query("SELECT * FROM cdb_address_shipments where order_track='" . $row->order_prefix . $row->order_no . "'");
						$address_order = $db->cdp_registro();



					?>
						<tr class="card-hovera">
							<td><b><a href="courier_view.php?id=<?php echo $row->order_id; ?>"><?php echo $row->order_no; ?></a></b></td>
							<td class="text-center">
								<?php echo $row->order_date; ?>
							</td>

							<td class="text-center">
					
								<?php
									echo $row->delivery_type
								?>
							</td>

							<td class="text-center">
								<?php echo $sender_data->fname; ?> <?php echo $sender_data->lname; ?>
							</td>
							
							<td class="text-center"><?php echo $address_order->sender_address; ?></td>


							<td class="text-center">
								<?php echo $receiver_data->fname; ?> <?php echo $receiver_data->lname; ?>
							</td>

							<td class="text-center"><?php echo $address_order->recipient_address; ?></td>

							<td class="text-center">
								<?php echo $row->distance; ?>
							</td>

							<td class="text-center">
								<?php echo $row->sub_total; ?>
							</td>

							<td class="text-center"><?php echo cdb_money_format($row->total_order); ?></td>


							<td class="text-center">

								<!-- <span style="background: <?php echo $status_style_pickup->color; ?>;" class="label label-large"><?php echo $status_style_pickup->mod_style; ?></span> -->

								<br>
								<?php if ($row->is_pickup != 0) { ?>
									<?php if ($row->status_courier == 14) { ?>
										<span style="background: #5BE472;" class="label label-large"><?php echo $lang['left533020020']; ?></span>

									<?php } else{ ?>
										<span style="background: <?php echo $row->color; ?>;" class="label label-large"><?php echo $row->mod_style; ?></span>
										<?php
										}
									?>

								<?php } ?>							</td>

							<td><?php echo @$row->notes; ?></td>
							
							<!-- <td align='center'>
								<?php if ($row->status_courier != 14) { ?>


									<div class="btn-group">
										<button class="btn btn-block btn-outline-dark btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
									        <i class="fas fa-ellipsis-v"></i>
									    </button>
										<div class="dropdown-menu" style="overflow-y: auto; max-height: 200px;">
											<a class="dropdown-item" href="courier_view.php?id=<?php echo $row->order_id; ?>" title="<?php echo $lang['tooledit'] ?>"><i style="color:#343a40" class="fa fa-search"></i>&nbsp;<?php echo $lang['leftorder266']; ?></a>
											<?php if ($row->order_incomplete == 0) { ?>
												<?php if ($row->is_pickup == 0) { ?>
													<?php if ($userData->userlevel != 1) { ?>
														<a class="dropdown-item" href="pickup_accept.php?id=<?php echo $row->order_id; ?>" title="<?php echo $lang['tooledit'] ?>"><i style="color:#343a40" class="ti-pencil"></i>&nbsp; <?php echo $lang['modal-text7']; ?></a>
													<?php } ?>
													<a class="dropdown-item" href="print_label_ship.php?id=<?php echo $row->order_id; ?>" target="_blank"> <i style="color:#343a40" class="ti-printer"></i>&nbsp;<?php echo $lang['toollabel'] ?> </a>
												<?php } ?>
											<?php } ?>

											<?php if ($row->order_incomplete == 1) { ?>


												<?php if ($userData->userlevel == 9 || $userData->userlevel == 2) { ?>


													<?php if ($row->status_courier != 21) { ?>
														<?php if ($row->status_courier != 12) { ?>
															<a class="dropdown-item" target="blank" href="print_inv_ship.php?id=<?php echo $row->order_id; ?>"> <i style="color:#343a40" class="ti-printer"></i>&nbsp;<?php echo $lang['toolprint'] ?></a>
															<a class="dropdown-item" href="print_label_ship.php?id=<?php echo $row->order_id; ?>" target="_blank"> <i style="color:#343a40" class="ti-printer"></i>&nbsp;<?php echo $lang['toollabel'] ?> </a>

														<?php } ?>

													<?php } ?>
												<?php } ?>
											<?php } ?>

										</div>

									</div>
								<?php
								} else { ?>


									<div class="btn-group">
										<button class="btn btn-info btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
											<?php echo $lang['left906']; ?>
										</button>
										<div class="dropdown-menu">

											<a class="dropdown-item" href="courier_view.php?id=<?php echo $row->order_id; ?>" title="<?php echo $lang['tooledit'] ?>"><i style="color:#343a40" class="fa fa-search"></i>&nbsp;<?php echo $lang['leftorder266']; ?></a>

											<?php if ($userData->userlevel == 9 || $userData->userlevel == 2) { ?>

												<?php if ($row->status_courier == 14) { ?>

													<a class="dropdown-item" href="pickup_accept.php?id=<?php echo $row->order_id; ?>">
														<i style="color:#36bea6" class="fas fa-check-circle"></i>&nbsp; <?php echo $lang['left533020020']; ?>
													</a>

												<?php } ?>
											<?php } ?>

											<a class="dropdown-item" data-id="<?php echo $row->order_id; ?>" href="#" data-toggle="modal" data-target="#myModalCancel">
												<i style="color:#f62d51" class="fas fa-times-circle"></i>&nbsp;<?php echo $lang['left533020021']; ?></a>

										</div>
									</div>
								<?php } ?>
							</td> -->
						</tr>
					<?php } ?>

				<?php } ?>
			</tbody>

		</table>


		<div class="pull-right">
			<?php echo cdp_paginate($page, $total_pages, $adjacents, $lang);	?>
		</div>
	</div>
<?php } ?>