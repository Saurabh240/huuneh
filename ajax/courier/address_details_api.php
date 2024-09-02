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

    if (!empty($details['country'])) {
        $db->cdp_query("SELECT * FROM cdb_countries WHERE `name` = :name");
    
        $db->bind(':name', $details['country']);
        $db->cdp_execute();
    
        $country = $db->cdp_registro();
        $country_id = !empty($country) ? $country->id : null;
    
        if (empty($country)) {
            $db->cdp_query("
                INSERT INTO cdb_countries 
                    (name)
                VALUES
                    (:name)
            ");
    
            $db->bind(':name', $details['country']);
            $db->cdp_execute();
    
            // Get the last inserted ID
            $country_id = $db->dbh->lastInsertId();
        }
    }
    
    if (!empty($details['state'])) {
        $db->cdp_query("SELECT * FROM cdb_states WHERE `name` = :name AND `country_id` = :country_id");
    
        $db->bind(':name', $details['state']);
        $db->bind(':country_id', $country_id);
        $db->cdp_execute();
    
        $state = $db->cdp_registro();
        $state_id = !empty($state) ? $state->id : null;
    
        if (empty($state)) {
            $db->cdp_query("
                INSERT INTO cdb_states 
                    (name, country_id)
                VALUES
                    (:name, :country_id)
            ");
    
            $db->bind(':name', $details['state']);
            $db->bind(':country_id', $country_id);
            $db->cdp_execute();
    
            // Get the last inserted ID
            $state_id = $db->dbh->lastInsertId();
        }
    }
    
    if (!empty($details['city'])) {
        $db->cdp_query("SELECT * FROM cdb_cities WHERE `name` = :name AND `state_id` = :state_id");
    
        $db->bind(':name', $details['city']);
        $db->bind(':state_id', $state_id);
        $db->cdp_execute();
    
        $city = $db->cdp_registro();
        $city_id = !empty($city) ? $city->id : null;
    
        if (empty($city)) {
            $db->cdp_query("
                INSERT INTO cdb_cities 
                    (name, state_id)
                VALUES
                    (:name, :state_id)
            ");
    
            $db->bind(':name', $details['city']);
            $db->bind(':state_id', $state_id);
            $db->cdp_execute();
    
            // Get the last inserted ID
            $city_id = $db->dbh->lastInsertId();
        }
    }
    

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

    echo "<pre>";
    print_r($data['results']);
    exit;

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
