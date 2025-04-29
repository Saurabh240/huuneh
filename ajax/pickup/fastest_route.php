<?php

require_once("../../loader.php");
session_start();
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
$db = new Conexion;

$apiKey = "AIzaSyCAP41rsfjKCKORsVRuSM_4ff6f7YGV7kQ";

$accepted_orders=$_POST['accepted_order_check'];
$starting_address=$_POST['starting_address'];

if($starting_address==''){
	echo 0; return false;
}
if(empty($accepted_orders)){
	echo 0;
	return false;
}
$end_address=array();
$zip_address=array();
$full_address=array();
$half_address=array();
foreach($accepted_orders as $k => $v){
	$db->cdp_query('SELECT cdb_cities.name as city,cdb_countries.name as country,cdb_states.name as state,zip_code,address FROM cdb_add_order,cdb_recipients_addresses, cdb_cities,cdb_countries,cdb_states WHERE order_id=:id and id_addresses=receiver_address_id and cdb_cities.id = city and cdb_states.id = state and cdb_countries.id = country');
    $db->bind(':id', $v);
	$db->cdp_execute();
	$order = $db->cdp_registro();
	$address = $order->address.', '.$order->city.', '.$order->state.', '.$order->country.', '.$order->zip_code;
	$end_address[] = $address;
	$zip_address[$v] = $order->zip_code;
	$half_address[$v] = $order->address;
	$full_address[$v] = $address;
	
}


function getZipCodeFromLatLng($latLng, $apiKey) {
    $url = "https://maps.googleapis.com/maps/api/geocode/json?latlng=$latLng&key=$apiKey";

    $response = file_get_contents($url);
    $data = json_decode($response, true);

    if ($data['status'] !== 'OK') {
        return "N/A";
    }

    foreach ($data['results'][0]['address_components'] as $component) {
        if (in_array("postal_code", $component['types'])) {
            return $component['long_name'];
        }
    }

    return "N/A"; // If no zip code found
}


function getOptimizedRoute($origin,$addresses, $apiKey,$zip_address,$half_address,$full_address) {
    $origin = urlencode($origin); // First address as the starting point
    $destination = urlencode($origin); // First address as the starting point
    $waypoints = implode('|', array_map('urlencode', $addresses)); // All as waypoints
	//Get Destination
	  $matrixUrl = "https://maps.googleapis.com/maps/api/distancematrix/json?origins=$origin&destinations=$waypoints&key=$apiKey";
	  $response_matrix = file_get_contents($matrixUrl);
	  $data_matrix = json_decode($response_matrix, true);
	  if ($data_matrix['status'] === "OK") {
			$distances = $data_matrix['rows'][0]['elements']; // Get distances array
			$maxDistance = 0;
			$farthestWaypoint = "";

			// Loop through waypoints to find the farthest one
			foreach ($distances as $index => $element) {
				if ($element['status'] === "OK") {
					$distanceValue = $element['distance']['value']; // Distance in meters

					if ($distanceValue > $maxDistance) {
						$maxDistance = $distanceValue;
						$farthestWaypoint = $index;
					}
				}
			}
			
			if($farthestWaypoint!=''){
				$destination= urlencode($addresses[$farthestWaypoint]);
				array_splice($addresses, $farthestWaypoint, 1);
				$waypoints = implode('|', array_map('urlencode', $addresses)); 
				
			}
			/*echo "<pre>";
			echo $farthestWaypoint;
			print_r($addresses);
			echo $destination;
			*/
	} 
	
    
	  
     $url = "https://maps.googleapis.com/maps/api/directions/json?origin=$origin&destination=$destination&waypoints=$waypoints&mode=driving&key=$apiKey";

    $response = file_get_contents($url);
    $data = json_decode($response, true);

    if ($data['status'] !== 'OK') {
        return "Error: " . $data['status'];
    }

    $route = [];
    $optimizedOrder = [];
    $totalDistance = 0;
    $totalDuration = 0;
	$cnt=0;
	
    foreach ($data['routes'][0]['legs'] as $leg) {
	
	     //$startLatLng = $leg['start_location']['lat'] . "," . $leg['start_location']['lng'];
        $endLatLng = $leg['end_location']['lat'] . "," . $leg['end_location']['lng'];

        //$startZip = getZipCodeFromLatLng($startLatLng, $apiKey);
       $endZip = getZipCodeFromLatLng($endLatLng, $apiKey);
        $route[] = [
            'start_address' => $leg['start_address'],
            'end_address' => $leg['end_address'],
            'distance' => $leg['distance']['text'],
            'duration' => $leg['duration']['text']
        ];
        $totalDistance += $leg['distance']['value']; // meters
        $totalDuration += $leg['duration']['value']; // seconds
		
		$keys = array_keys($zip_address,$endZip);
		if(!empty($keys)){
			foreach($keys as $v){
				if(!in_array($v,$optimizedOrder)){
					
					$optimizedOrder[$cnt] = $v;
				}
			}
		}else{
			$abc=explode(',',$leg['end_address']);
			if(isset($abc[0])) {
				foreach($half_address as $kk=>$vv)
				{
				 if(str_contains($vv, $abc[0])){
					 if(!in_array($kk,$optimizedOrder)){
								$optimizedOrder[$cnt] = $kk;
								break;
					 }
				 }
				}
				
			} 
		}
		if(!isset($optimizedOrder[$cnt])){
			$optimizedOrder[$cnt]=$leg['end_address'];
		}
		$cnt++;
    }
	foreach($optimizedOrder as $k=>$v){
		if(is_string($v)){
			$abc=explode(',',$v);
			foreach($abc as $l => $m){
				foreach($full_address as $kk=>$vv)
				{
				 if(str_contains($vv, $m)){
					 if(!in_array($kk,$optimizedOrder)){
						// echo "hello=====";
						$optimizedOrder[$k] = $kk;
						break;
					 }
				 }
				}
				
			}
		}
	}
	   

    return [
        'optimized_route' => $route,
        'optimized_order' => $optimizedOrder,
        'total_distance' => round($totalDistance / 1000, 2),
        'total_duration' => gmdate("H:i:s", $totalDuration)
    ];
}

$result = getOptimizedRoute($starting_address, $end_address, $apiKey,$zip_address,$half_address,$full_address);

/*echo "<pre>";
print_r($full_address);
print_r($half_address);
print_r($zip_address);
print_r($result['optimized_order']);
print_r($result['optimized_route']);*/

//Save into Database
if(!empty($result['optimized_route'])) {
	$route='';
	$n=count($result['optimized_route']);
	foreach($result['optimized_route'] as $k => $v){
		$route.=urldecode($v['end_address']);
		if($k<$n-1){ $route.='+++'; }
	}
	
   $db->cdp_query("INSERT INTO cdb_route (starting_point,route,total_distance,total_duration,inserted_date) VALUES (:starting_point,:route,:total_distance,:total_duration,:inserted_date)");
            $db->bind(':starting_point', $starting_address);
            $db->bind(':route', $route);
			$db->bind(':total_distance', $result['total_distance']);
			$db->bind(':total_duration', $result['total_duration']);
            $db->bind(':inserted_date', date('y-m-d h:i:s'));
            $db->cdp_execute();
    
            // Get the last inserted ID
           $route_id = $db->dbh->lastInsertId();
		   if($route_id>0){
			   foreach($result['optimized_order'] as $k => $v){
				   $db->cdp_query("INSERT INTO cdb_route_order (route_id,order_id) VALUES (:route_id,:order_id)");
					$db->bind(':route_id', $route_id);
					$db->bind(':order_id', $v);
					$db->cdp_execute();
			   }
			   echo $route_id;
			   return true;
		   }
        }
		


echo 0;
return false;

?>