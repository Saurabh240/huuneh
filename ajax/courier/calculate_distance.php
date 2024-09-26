<?php
require_once("../../loader.php");
session_start();
/*
require '../../vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\IOFactory;*/
$db = new Conexion;

if ( isset( $_POST['sender_id'] ) )  {
    $db->cdp_query('SELECT * FROM cdb_users WHERE id=:id');
    $db->bind(':id', $_POST['sender_id']);
} else {
    $db->cdp_query('SELECT * FROM cdb_users WHERE username=:user OR email=:user');
    $db->bind(':user', $_SESSION['username']);
}
$db->cdp_execute();
$user = $db->cdp_registro();

$business_type = $user->business_type;

// Replace 'YOUR_GOOGLE_API_KEY' with your actual Google Maps API key
$apiKey = 'AIzaSyCAP41rsfjKCKORsVRuSM_4ff6f7YGV7kQ';

if (isset($_POST["origin"]) && isset($_POST["destination"]) && isset($_POST["deliveryType"]) && $_POST["deliveryType"]!='') {
    $origin = urlencode($_POST["origin"]);
	$destination = urlencode($_POST["destination"]);
    $deliveryType = $_POST["deliveryType"];
	$check_deliveryType=array('SAMEDAY (BEFORE 7PM)','NEXT DAY (BEFORE 7PM)','SAMEDAY (BEFORE 9PM)');
	if(isset($_POST["origin_id"])){
		$db->cdp_query('SELECT name FROM cdb_senders_addresses,cdb_cities WHERE cdb_cities.id=cdb_senders_addresses.city && id_addresses='.$_POST["origin_id"]);
		$db->cdp_execute();
		$originCityName = $db->cdp_registro();
		$originCity = $originCityName->name??'';
	}
	if(isset($_POST["destination_id"])){	
		$db->cdp_query('SELECT name FROM  cdb_recipients_addresses,cdb_cities WHERE cdb_cities.id=cdb_recipients_addresses.city && id_addresses='.$_POST["destination_id"]); 	
		$db->cdp_execute();		
		$destinationCityName = $db->cdp_registro();
		$destinationCity = $destinationCityName->name??'';
	}
	$flat_price_approverd_for=array('flat_1','flat_2','special');
    $distance_bw = $courier['distance']= calculateDistance($origin, $destination, $apiKey);
    if ($courier['distance'] !== false) {
		$flag = array_search ($deliveryType, $check_deliveryType);
		
		if(in_array($business_type,$flat_price_approverd_for)  && $originCity!='' && $destinationCity!='' && $flag!='' && $flag>=0){
			  
			   $db->cdp_query("SELECT * FROM   cdb_flat_price_lists WHERE business_type= '".$business_type."' && sender_city= '".$originCity."' && recipient_city= '".$destinationCity."'"); 	
				$db->cdp_execute();
				$flat_price_data = $db->cdp_registro();
				 if ($flat_price_data && isset($flat_price_data->price)) {
				$courier['baseRate'] = $flat_price_data->price??'';
				$courier['shipmentfee'] = $flat_price_data->price??'';
				$courier['taxfee'] = $flat_price_data->price_with_tax??'';
				 }
				$courier['distance']=$distance_bw;
				if(isset($courier['baseRate'])){ echo json_encode($courier); }else{ $courier['msg']= "Your request is outside of the Range provided our pricing program. Pricing will be to our Default KM pricing. For any questions please contact our dispatch office."; }
			  
		}
		if((in_array($business_type,$flat_price_approverd_for) && !isset($courier['baseRate'])) ||  !in_array($business_type,$flat_price_approverd_for)){
			// Calculate shipping price based on distance and delivery type
			$rates = getRatesByDeliveryTypeAndBusinessType($deliveryType, $business_type);
			if ($rates) {
				$baseRate = $rates['baseRate'];
				$additionalRatePerKm = $rates['additionalRatePerKm'];
				$baseKm = $rates['baseKm'];
				$courier['baseRate'] = $baseRate;
				$courier['shipmentfee'] = calculateShippingPrice($courier['distance'], $baseRate, $additionalRatePerKm, $baseKm);
				echo json_encode($courier);
			}  else {
				echo "<p>Invalid delivery type or business type.</p>";
			}
		}
    } else {
        echo "<p>Error calculating distance.</p>";
    }
} else {
    echo "<p>Please fill in all fields.</p>";
}

// Function to calculate distance between two coordinates
function calculateDistance($origin, $destination, $apiKey) {
    $url = "https://maps.googleapis.com/maps/api/distancematrix/json?origins=$origin&destinations=$destination&key=$apiKey";
    $response = file_get_contents($url);
    $data = json_decode($response, true);
    
    // Check if API request was successful
    if ($data['status'] == 'OK') {       
		 
        // Extract distance in meters
        if($distance = $data['rows'][0]['elements'][0]['status'] == 'ZERO_RESULTS'){
            $distance = 0;
        }else{
            $distance = $data['rows'][0]['elements'][0]['distance']['value'];
        }

        // Convert meters to kilometers
        return $distance / 1000;
    } else {
        // Handle API error
        return false;
    }
}

// Function to get rates based on delivery type and business type
function getRatesByDeliveryTypeAndBusinessType($deliveryType, $businessType) {
    $rates = [ 
        'default' => [
            'SAMEDAY (BEFORE 9PM)' => ['baseRate' => 10.00, 'additionalRatePerKm' => 0.55, 'baseKm' => 10],
            'SAMEDAY (BEFORE 7PM)' => ['baseRate' => 10.00, 'additionalRatePerKm' => 0.55, 'baseKm' => 10],
            'SAME DAY (1PM to 4PM)' => ['baseRate' => 10.00, 'additionalRatePerKm' => 0.55, 'baseKm' => 10],
            'SAME DAY (BEFORE 5PM)' => ['baseRate' => 10.00, 'additionalRatePerKm' => 0.50, 'baseKm' => 10],
            'RUSH (4 HOURS)' => ['baseRate' => 10.00, 'additionalRatePerKm' => 0.55, 'baseKm' => 10],
            'RUSH (3 HOURS)' => ['baseRate' => 15.00, 'additionalRatePerKm' => 0.70, 'baseKm' => 10],
            'RUSH (2 HOURS)' => ['baseRate' => 20.00, 'additionalRatePerKm' => 0.75, 'baseKm' => 10],
            'URGENT (90 MINUTES)' => ['baseRate' => 25.00, 'additionalRatePerKm' => 0.75, 'baseKm' => 10],
            'NEXT DAY (BEFORE 7PM)' => ['baseRate' => 5.00, 'additionalRatePerKm' => 0.50, 'baseKm' => 10],
            'NEXT DAY (BEFORE 5PM)' => ['baseRate' => 5.00, 'additionalRatePerKm' => 0.55, 'baseKm' => 10],
            'NEXT DAY (BEFORE 2PM)' => ['baseRate' => 7.00, 'additionalRatePerKm' => 0.55, 'baseKm' => 10],
            'NEXT DAY (BEFORE 11:30AM)' => ['baseRate' => 10.00, 'additionalRatePerKm' => 0.75, 'baseKm' => 10],
            'NEXT DAY (BEFORE 10:30AM)' => ['baseRate' => 15.00, 'additionalRatePerKm' => 0.75, 'baseKm' => 10]
        ],
        'law_firm' => [
            'SAMEDAY (BEFORE 9PM)' => ['baseRate' => 10.00, 'additionalRatePerKm' => 0.55, 'baseKm' => 10],
            'SAMEDAY (BEFORE 7PM)' => ['baseRate' => 10.00, 'additionalRatePerKm' => 0.55, 'baseKm' => 10],
            'SAME DAY (1PM to 4PM)' => ['baseRate' => 10.00, 'additionalRatePerKm' => 0.55, 'baseKm' => 10],
            'SAME DAY (BEFORE 5PM)' => ['baseRate' => 10.00, 'additionalRatePerKm' => 0.50, 'baseKm' => 10],
            'RUSH (4 HOURS)' => ['baseRate' => 10.00, 'additionalRatePerKm' => 0.55, 'baseKm' => 10],
            'RUSH (3 HOURS)' => ['baseRate' => 15.00, 'additionalRatePerKm' => 0.70, 'baseKm' => 10],
            'RUSH (2 HOURS)' => ['baseRate' => 20.00, 'additionalRatePerKm' => 0.75, 'baseKm' => 10],
            'URGENT (90 MINUTES)' => ['baseRate' => 25.00, 'additionalRatePerKm' => 0.75, 'baseKm' => 10],
            'NEXT DAY (BEFORE 7PM)' => ['baseRate' => 5.00, 'additionalRatePerKm' => 0.50, 'baseKm' => 10],
            'NEXT DAY (BEFORE 5PM)' => ['baseRate' => 5.00, 'additionalRatePerKm' => 0.55, 'baseKm' => 10],
            'NEXT DAY (BEFORE 2PM)' => ['baseRate' => 7.00, 'additionalRatePerKm' => 0.55, 'baseKm' => 10],
            'NEXT DAY (BEFORE 11:30AM)' => ['baseRate' => 10.00, 'additionalRatePerKm' => 0.75, 'baseKm' => 10],
            'NEXT DAY (BEFORE 10:30AM)' => ['baseRate' => 15.00, 'additionalRatePerKm' => 0.75, 'baseKm' => 10]
        ],
        'pharmacy' => [
            'SAMEDAY (BEFORE 9PM)' => ['baseRate' => 5.00, 'additionalRatePerKm' => 0.33, 'baseKm' => 15],
            'SAMEDAY (BEFORE 7PM)' => ['baseRate' => 5.00, 'additionalRatePerKm' => 0.33, 'baseKm' => 15],
            'SAME DAY (1PM to 4PM)' => ['baseRate' => 10.00, 'additionalRatePerKm' => 0.55, 'baseKm' => 15],
            'SAME DAY (BEFORE 5PM)' => ['baseRate' => 5.00, 'additionalRatePerKm' => 0.33, 'baseKm' => 15],
            'RUSH (4 HOURS)' => ['baseRate' => 10.00, 'additionalRatePerKm' => 0.55, 'baseKm' => 15],
            'RUSH (3 HOURS)' => ['baseRate' => 15.00, 'additionalRatePerKm' => 0.70, 'baseKm' => 15],
            'RUSH (2 HOURS)' => ['baseRate' => 20.00, 'additionalRatePerKm' => 0.75, 'baseKm' => 15],
            'URGENT (90 MINUTES)' => ['baseRate' => 25.00, 'additionalRatePerKm' => 0.75, 'baseKm' => 15],
            'NEXT DAY (BEFORE 7PM)' => ['baseRate' => 4.00, 'additionalRatePerKm' => 0.00, 'baseKm' => 15],
            'NEXT DAY (BEFORE 5PM)' => ['baseRate' => 5.00, 'additionalRatePerKm' => 0.33, 'baseKm' => 15],
            'NEXT DAY (BEFORE 2PM)' => ['baseRate' => 7.00, 'additionalRatePerKm' => 0.55, 'baseKm' => 15],
            'NEXT DAY (BEFORE 11:30AM)' => ['baseRate' => 10.00, 'additionalRatePerKm' => 0.75, 'baseKm' => 15],
            'NEXT DAY (BEFORE 10:30AM)' => ['baseRate' => 15.00, 'additionalRatePerKm' => 0.75, 'baseKm' => 15]
        ],
        'pharmacy_2' => [
            'SAMEDAY (BEFORE 9PM)' => ['baseRate' => 5.00, 'additionalRatePerKm' => 0.00, 'baseKm' => 15],
            'SAMEDAY (BEFORE 7PM)' => ['baseRate' => 5.00, 'additionalRatePerKm' => 0.00, 'baseKm' => 15],
            'SAME DAY (1PM to 4PM)' => ['baseRate' => 5.00, 'additionalRatePerKm' => 0.00, 'baseKm' => 15],
            'SAME DAY (BEFORE 5PM)' => ['baseRate' => 5.00, 'additionalRatePerKm' => 0.33, 'baseKm' => 15],
            'RUSH (4 HOURS)' => ['baseRate' => 5.00, 'additionalRatePerKm' => 0.00, 'baseKm' => 15],
            'RUSH (3 HOURS)' => ['baseRate' => 5.00, 'additionalRatePerKm' => 0.00, 'baseKm' => 15],
            'RUSH (2 HOURS)' => ['baseRate' => 5.00, 'additionalRatePerKm' => 0.00, 'baseKm' => 15],
            'URGENT (90 MINUTES)' => ['baseRate' => 5.00, 'additionalRatePerKm' => 0.00, 'baseKm' => 15],
            'NEXT DAY (BEFORE 7PM)' => ['baseRate' => 5.00, 'additionalRatePerKm' => 0.00, 'baseKm' => 15],
            'NEXT DAY (BEFORE 5PM)' => ['baseRate' => 5.00, 'additionalRatePerKm' => 0.00, 'baseKm' => 15],
            'NEXT DAY (BEFORE 2PM)' => ['baseRate' => 5.00, 'additionalRatePerKm' => 0.00, 'baseKm' => 15],
            'NEXT DAY (BEFORE 11:30AM)' => ['baseRate' => 5.00, 'additionalRatePerKm' => 0.00, 'baseKm' => 15],
            'NEXT DAY (BEFORE 10:30AM)' => ['baseRate' => 5.00, 'additionalRatePerKm' => 0.00, 'baseKm' => 15]
        ],
        'pharmacy_3' => [
            'SAMEDAY (BEFORE 9PM)' => ['baseRate' => 4.00, 'additionalRatePerKm' => 0.00, 'baseKm' => 15],
            'SAMEDAY (BEFORE 7PM)' => ['baseRate' => 4.00, 'additionalRatePerKm' => 0.00, 'baseKm' => 15],
            'SAME DAY (1PM to 4PM)' => ['baseRate' => 4.00, 'additionalRatePerKm' => 0.00, 'baseKm' => 15],
            'SAME DAY (BEFORE 5PM)' => ['baseRate' => 4.00, 'additionalRatePerKm' => 0.33, 'baseKm' => 15],
            'RUSH (4 HOURS)' => ['baseRate' => 4.00, 'additionalRatePerKm' => 0.00, 'baseKm' => 15],
            'RUSH (3 HOURS)' => ['baseRate' => 4.00, 'additionalRatePerKm' => 0.00, 'baseKm' => 15],
            'RUSH (2 HOURS)' => ['baseRate' => 4.00, 'additionalRatePerKm' => 0.00, 'baseKm' => 15],
            'URGENT (90 MINUTES)' => ['baseRate' => 4.00, 'additionalRatePerKm' => 0.00, 'baseKm' => 15],
            'NEXT DAY (BEFORE 7PM)' => ['baseRate' => 4.00, 'additionalRatePerKm' => 0.00, 'baseKm' => 15],
            'NEXT DAY (BEFORE 5PM)' => ['baseRate' => 4.00, 'additionalRatePerKm' => 0.00, 'baseKm' => 15],
            'NEXT DAY (BEFORE 2PM)' => ['baseRate' => 4.00, 'additionalRatePerKm' => 0.00, 'baseKm' => 15],
            'NEXT DAY (BEFORE 11:30AM)' => ['baseRate' => 4.00, 'additionalRatePerKm' => 0.00, 'baseKm' => 15],
            'NEXT DAY (BEFORE 10:30AM)' => ['baseRate' => 4.00, 'additionalRatePerKm' => 0.00, 'baseKm' => 15]
        ],
        'special' => [
            'SAMEDAY (BEFORE 9PM)' => ['baseRate' => 5.00, 'additionalRatePerKm' => 0.33, 'baseKm' => 15],
            'SAMEDAY (BEFORE 7PM)' => ['baseRate' => 5.00, 'additionalRatePerKm' => 0.33, 'baseKm' => 15],
            'SAME DAY (1PM to 4PM)' => ['baseRate' => 10.00, 'additionalRatePerKm' => 0.55, 'baseKm' => 10],
            'SAME DAY (BEFORE 5PM)' => ['baseRate' => 4.00, 'additionalRatePerKm' => 0.00, 'baseKm' => 15],
            'RUSH (4 HOURS)' => ['baseRate' => 10.00, 'additionalRatePerKm' => 0.55, 'baseKm' => 15],
            'RUSH (3 HOURS)' => ['baseRate' => 15.00, 'additionalRatePerKm' => 0.70, 'baseKm' => 15],
            'RUSH (2 HOURS)' => ['baseRate' => 20.00, 'additionalRatePerKm' => 0.75, 'baseKm' => 15],
            'NEXT DAY (BEFORE 7PM)' => ['baseRate' => 4.00, 'additionalRatePerKm' => 0.00, 'baseKm' => 15],
            'URGENT (90 MINUTES)' => ['baseRate' => 25.00, 'additionalRatePerKm' => 0.75, 'baseKm' => 15],
            'NEXT DAY (BEFORE 5PM)' => ['baseRate' => 4.00, 'additionalRatePerKm' => 0.00, 'baseKm' => 15],
            'NEXT DAY (BEFORE 2PM)' => ['baseRate' => 7.00, 'additionalRatePerKm' => 0.55, 'baseKm' => 15],
            'NEXT DAY (BEFORE 11:30AM)' => ['baseRate' => 10.00, 'additionalRatePerKm' => 0.75, 'baseKm' => 15],
            'NEXT DAY (BEFORE 10:30AM)' => ['baseRate' => 15.00, 'additionalRatePerKm' => 0.75, 'baseKm' => 15]
        ],
        'flower_shop' => [
            'SAMEDAY (BEFORE 9PM)' => ['baseRate' => 5.00, 'additionalRatePerKm' => 0.33, 'baseKm' => 15],
            'SAMEDAY (BEFORE 7PM)' => ['baseRate' => 5.00, 'additionalRatePerKm' => 0.33, 'baseKm' => 15],
            'SAME DAY (1PM to 4PM)' => ['baseRate' => 10.00, 'additionalRatePerKm' => 0.55, 'baseKm' => 10],
            'SAME DAY (BEFORE 5PM)' => ['baseRate' => 5.00, 'additionalRatePerKm' => 0.33, 'baseKm' => 15],
            'RUSH (4 HOURS)' => ['baseRate' => 10.00, 'additionalRatePerKm' => 0.33, 'baseKm' => 15],
            'RUSH (3 HOURS)' => ['baseRate' => 15.00, 'additionalRatePerKm' => 0.33, 'baseKm' => 15],
            'RUSH (2 HOURS)' => ['baseRate' => 20.00, 'additionalRatePerKm' => 0.33, 'baseKm' => 15],
            'URGENT (90 MINUTES)' => ['baseRate' => 25.00, 'additionalRatePerKm' => 0.33, 'baseKm' => 15],
            'NEXT DAY (BEFORE 7PM)' => ['baseRate' => 5.00, 'additionalRatePerKm' => 0.33, 'baseKm' => 15],
            'NEXT DAY (BEFORE 5PM)' => ['baseRate' => 5.00, 'additionalRatePerKm' => 0.33, 'baseKm' => 15],
            'NEXT DAY (BEFORE 2PM)' => ['baseRate' => 7.00, 'additionalRatePerKm' => 0.33, 'baseKm' => 15],
            'NEXT DAY (BEFORE 11:30AM)' => ['baseRate' => 10.00, 'additionalRatePerKm' => 0.33, 'baseKm' => 15],
            'NEXT DAY (BEFORE 10:30AM)' => ['baseRate' => 15.00, 'additionalRatePerKm' => 0.33, 'baseKm' => 15]
        ],
        'flower_shop_2' => [
            'SAMEDAY (BEFORE 9PM)' => ['baseRate' => 7.00, 'additionalRatePerKm' => 0.00, 'baseKm' => 15],
            'SAMEDAY (BEFORE 7PM)' => ['baseRate' => 7.00, 'additionalRatePerKm' => 0.00, 'baseKm' => 15],
            'SAME DAY (1PM to 4PM)' => ['baseRate' => 7.00, 'additionalRatePerKm' => 0.00, 'baseKm' => 10],
            'SAME DAY (BEFORE 5PM)' => ['baseRate' => 7.00, 'additionalRatePerKm' => 0.00, 'baseKm' => 15],
            'RUSH (4 HOURS)' => ['baseRate' => 7.00, 'additionalRatePerKm' => 0.00, 'baseKm' => 15],
            'RUSH (3 HOURS)' => ['baseRate' => 7.00, 'additionalRatePerKm' => 0.00, 'baseKm' => 15],
            'RUSH (2 HOURS)' => ['baseRate' => 7.00, 'additionalRatePerKm' => 0.00, 'baseKm' => 15],
            'URGENT (90 MINUTES)' => ['baseRate' => 7.00, 'additionalRatePerKm' => 0.00, 'baseKm' => 15],
            'NEXT DAY (BEFORE 7PM)' => ['baseRate' => 7.00, 'additionalRatePerKm' => 0.00, 'baseKm' => 15],
            'NEXT DAY (BEFORE 5PM)' => ['baseRate' => 7.00, 'additionalRatePerKm' => 0.00, 'baseKm' => 15],
            'NEXT DAY (BEFORE 2PM)' => ['baseRate' => 7.00, 'additionalRatePerKm' => 0.00, 'baseKm' => 15],
            'NEXT DAY (BEFORE 11:30AM)' => ['baseRate' => 7.00, 'additionalRatePerKm' => 0.00, 'baseKm' => 15],
            'NEXT DAY (BEFORE 10:30AM)' => ['baseRate' => 7.00, 'additionalRatePerKm' => 0.00, 'baseKm' => 15]
        ]
    ];

    if ($businessType == 'flower_shop' || $businessType == 'flower_shop_2' || $businessType == 'pharmacy' || $businessType == 'pharmacy_2' || $businessType == 'pharmacy_3') {
        return $rates[$businessType][$deliveryType] ?? null;
    } else if ($businessType == 'special') {
        return $rates['special'][$deliveryType] ?? null;
    } else {
        return $rates['default'][$deliveryType] ?? null;
    }
}

// Function to calculate shipping price based on distance, base rate, and additional rate per kilometer
function calculateShippingPrice($distance, $baseRate, $additionalRatePerKm, $baseKm) {
    // Calculate additional rate for distance beyond base kilometers
    $additionalDistance = max(0, ($distance - $baseKm));
    $additionalCharge = $additionalDistance * $additionalRatePerKm;

    // Calculate total shipping price
    $totalPrice = $baseRate + $additionalCharge;
    return $totalPrice;
}
?>