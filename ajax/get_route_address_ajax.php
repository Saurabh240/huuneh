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
require_once("../loader.php");

$db = new Conexion; 

$route_id = cdp_sanitize($_REQUEST['route_id']);

$sql = "SELECT * from cdb_route where id = ".$route_id;
$query_count = $db->cdp_query($sql);
$db->cdp_execute();
$numrows = $db->cdp_rowCount();
$data = $db->cdp_registros();
$data_address=array();
if ($numrows > 0) {
	$route=explode('+++',$data[0]->route);
	$data_address[0]= $data[0]->starting_point;
	foreach($route as $v){
		$data_address[]= $v;
	}
}
echo json_encode($data_address);
