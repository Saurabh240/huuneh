<?php
// Replace 'YOUR_GOOGLE_API_KEY' with your actual Google Maps API key
$apiKey = 'AIzaSyCAP41rsfjKCKORsVRuSM_4ff6f7YGV7kQ';

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["origin"]) && isset($_POST["destination"]) && isset($_POST["deliveryType"])) {
        $origin = urlencode($_POST["origin"]);
        $destination = urlencode($_POST["destination"]);
        $deliveryType = $_POST["deliveryType"];

        $distance = calculateDistance($origin, $destination, $apiKey);
        if ($distance !== false) {
            // Calculate shipping price based on distance and delivery type
            $shippingPrice = calculateShippingPrice($distance, $deliveryType);
            echo "<p>Shipping Price: $shippingPrice</p>";
        } else {
            echo "<p>Error calculating distance.</p>";
        }
    } else {
        echo "<p>Please fill in all fields.</p>";
    }
}



// Function to calculate distance between two coordinates
function calculateDistance($origin, $destination, $apiKey) {
    $url = "https://maps.googleapis.com/maps/api/distancematrix/json?origins=$origin&destinations=$destination&key=$apiKey";
    $response = file_get_contents($url);
    $data = json_decode($response, true);

    // Check if API request was successful
    if ($data['status'] == 'OK') {
        // Extract distance in meters
        $distance = $data['rows'][0]['elements'][0]['distance']['value'];
        // Convert meters to kilometers
        return $distance / 1000;
    } else {
        // Handle API error
        return false;
    }
}

// Function to calculate shipping price based on distance and delivery type
function calculateShippingPrice($distance, $deliveryType) {
    $baseRate = 0;
    $additionalRatePerKm = 0.75;

    // Determine base rate based on delivery type
    switch ($deliveryType) {
        case 'Next day':
            $baseRate = 5;
            break;
        case 'Same day':
            $baseRate = 10;
            break;
        case 'Rush':
            $baseRate = 20;
            break;
        case 'Urgent':
            $baseRate = 25;
            break;
        default:
            return "Invalid delivery type";
    }

    // Calculate additional rate for distance beyond 10km
    $additionalDistance = max(0, $distance - 10);
    $additionalCharge = $additionalDistance * $additionalRatePerKm;

    // Calculate total shipping price
    $totalPrice = $baseRate + $additionalCharge;
    return $totalPrice;
}


?>