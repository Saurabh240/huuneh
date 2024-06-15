<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Distance Calculator and Shipping Rate</title>
</head>
<body>
<h1>Distance Calculator and Shipping Rate</h1>

<form method="post" action="">
    <label for="origin">Origin Address:</label>
    <input type="text" id="origin" name="origin" required><br><br>

    <label for="destination">Destination Address:</label>
    <input type="text" id="destination" name="destination" required><br><br>

    <label for="deliveryType">Delivery Type:</label>
    <select class="form-control custom-select" id="deliveryType" name="deliveryType" required style="width: 100%;">
														<option value="" selected>Select Delivery Type</option>
														<option <?php if(time() > strtotime("12:00 PM")) { echo "disabled='disabled' class='disabled-cls'"; }?>value="SAME DAY (1PM to 4PM)">SAME DAY (1PM to 4PM)</option>
														<option <?php if(time() > strtotime("12:00 PM")) { echo "disabled='disabled' class='disabled-cls'"; }?> value="SAME DAY (BEFORE 5PM)">SAME DAY (BEFORE 5PM)</option>
														<option <?php if(time() > strtotime("1:30 PM")) { echo "disabled='disabled' class='disabled-cls'"; }?> value="RUSH (4 HOURS)">RUSH (4 HOURS)</option>
														<option <?php if(time() > strtotime("2:30 PM")) { echo "disabled='disabled' class='disabled-cls'"; }?> value="RUSH (3 HOURS)">RUSH (3 HOURS)</option>
														<option <?php if(time() > strtotime("9:00 PM")) { echo "disabled='disabled' class='disabled-cls'"; }?> value="RUSH (2 HOURS)">RUSH (2 HOURS)</option>
														<option <?php if(time() > strtotime("9:00 PM")) { echo "disabled='disabled' class='disabled-cls'"; }?> value="URGENT (90 MINUTES)">URGENT (90 MINUTES)</option>
														<option value="NEXT DAY (BEFORE 5PM)">NEXT DAY (BEFORE 5PM)</option>
														<option value="NEXT DAY (BEFORE 2PM)">NEXT DAY (BEFORE 2PM)</option>
														<option value="NEXT DAY (BEFORE 11:30AM)">NEXT DAY (BEFORE 11:30AM)</option>
														<option value="NEXT DAY (BEFORE 10:30AM)">NEXT DAY (BEFORE 10:30AM)</option>
                                                      
													</select><br><br>

    <button type="submit">Calculate Shipping Price</button>
</form>

<?php
// Replace 'YOUR_GOOGLE_API_KEY' with your actual Google Maps API key
$apiKey = 'AIzaSyCAP41rsfjKCKORsVRuSM_4ff6f7YGV7kQ';

// Function to calculate distance between two coordinates
function calculateDistance($origin, $destination, $apiKey) {
    $url = "https://maps.googleapis.com/maps/api/distancematrix/json?origins=$origin&destinations=$destination&key=$apiKey";
    $response = file_get_contents($url);
    $data = json_decode($response, true);

    // Check if API request was successful
    if ($data['status'] == 'OK') {
        // Extract distance in meters
//        $distance = $data['rows'][0]['elements'][0]['distance']['value'];
        $distance = 100000;
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
?>
</body>
</html>
