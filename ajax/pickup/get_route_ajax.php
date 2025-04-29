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

$route_id = cdp_sanitize($_REQUEST['route_id']);

$sql = "SELECT * from cdb_route where id = ".$route_id;
$query_count = $db->cdp_query($sql);
$db->cdp_execute();
$numrows = $db->cdp_rowCount();
$data = $db->cdp_registros();
if ($numrows > 0) {
$route=explode('+++',$data[0]->route);
$n= count($route);
$starting_point=$data[0]->starting_point;
$total_distance=$data[0]->total_distance;
$total_duration=$data[0]->total_duration;

$sql2 = "SELECT delivery_type,cdb_add_order.notes as notes,cdb_add_order.tags as tags,business_name from cdb_route_order, cdb_add_order,  cdb_users  where cdb_route_order.order_id = cdb_add_order.order_id and  cdb_add_order.sender_id = cdb_users.id and route_id = ".$route_id;
$query_count2 = $db->cdp_query($sql2);
$db->cdp_execute();
$numrows2 = $db->cdp_rowCount();
$data_order = $db->cdp_registros();


 ?>


               
                    <h2 class="mb-4 text-success">Travel Route</h2>
					<p class="fs-4 fw-semibold text-warning">Total KM: <?php echo $total_distance; ?> KM</p>
					<p class="fs-4 fw-semibold text-warning pb-4">Total Duration: <?php echo $total_duration; ?> Hrs</p>
					<table class="table table-bordered  table-striped">
					  <thead>
						<tr>
						  <th scope="col"><strong>Action</strong></th>
						  <th scope="col"><strong>Business</strong></th>
						  <th scope="col"><strong>Order Type</strong></th>
						  <th scope="col"><strong>Address</strong></th>
						  <th scope="col"><strong>Any Notes/Tags/Collection Amounts</strong></th>
						</tr>
					  </thead>
					  <tbody>
					  <tr>
						  <th scope="row"><strong>PICK UP/START</strong></th>
						  <td></td>
						  <td></td>
						  <td><?php echo $starting_point; ?></td>
						  <td></td>
						</tr>
						<?php $cnt=1;
							for($i=0;$i<$n;$i++){ ?>
						<tr>
						  <th scope="row"><strong>DROP OFF <?php echo $cnt++; ?></strong></th>
						  <td><?php echo $data_order[$i]->business_name; ?></td>
						  <td><?php echo $data_order[$i]->delivery_type; ?></td>
						  <td><?php echo $route[$i]; ?></td>
						  <td><?php if($data_order[$i]->tags!="[]" && $data_order[$i]->tags!=""){ echo $data_order[$i]->tags; }
								    if($data_order[$i]->notes!=''){ echo $data_order[$i]->notes; }  ?></td>
						</tr>
					<?php } ?>
					  </tbody>
					</table>
                   
                  

                   
   





<?php } ?>