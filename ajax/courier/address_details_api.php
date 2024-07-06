<?php
require_once("../../loader.php");
session_start();
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

$response = ['status' => false, "message" => "Something went wrong"];
$apiKey = 'AIzaSyCAP41rsfjKCKORsVRuSM_4ff6f7YGV7kQ';
if (isset($_POST["address_modal"]) ) {
    // $address = "1600 Amphitheatre Parkway, Mountain View, CA";
    $address = $_POST["address_modal"];
    $details = getAddressDetails($address, $apiKey);

    // echo "City: " . $details['city'] . PHP_EOL;
    // echo "State: " . $details['state'] . PHP_EOL;
    // echo "Zip Code: " . $details['zip_code'] . PHP_EOL;
    // echo "Country: " . $details['country'] . PHP_EOL;

    $response = ['status' => true, 'fullAddress' => $details];

    header('Content-Type: application/json');
    echo json_encode($response);
   
} else {
    header('Content-Type: application/json');
    echo json_encode($response);    
}

// Function to get complete address.
function getAddressDetails($address, $apiKey) {
    $baseUrl = "https://maps.googleapis.com/maps/api/geocode/json";
    $params = http_build_query([
        'address' =>  $address,
        'key' => $apiKey
    ]);
    $url = "$baseUrl?$params";
    
    $response = file_get_contents($url);
    if ($response === FALSE) {
        return NULL;
    }

    $data = json_decode($response, true);
    if ($data['status'] != 'OK') {
        return NULL;
    }

    $addressComponents = $data['results'][0]['address_components'];
    $details = [];

    foreach ($addressComponents as $component) {
        if (in_array('locality', $component['types'])) {
            $details['city'] = $component['long_name'];
        }
        if (in_array('administrative_area_level_1', $component['types'])) {
            $details['state'] = $component['long_name'];
        }
        if (in_array('postal_code', $component['types'])) {
            $details['zip_code'] = $component['long_name'];
        }
        if (in_array('country', $component['types'])) {
            $details['country'] = $component['long_name'];
        }
    }

    return $details;
}


?>
