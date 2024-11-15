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


// Function to check if a tag should be checked
function isTagChecked($tag, $tags) {
  return in_array($tag, $tags);
}

function cdp_cleanOutx($text)
{
  $text =  strtr($text, array('\r\n' => "", '\r' => "", '\n' => ""));
  $text = html_entity_decode($text, ENT_QUOTES, 'UTF-8');
  $text = str_replace('<br>', '<br />', $text);
  return stripslashes($text);
}



  function cdp_validateTrack($value)
  {

      $valid_uname = "/^[A-Z-a-z0-9_-]{4,55}$/"; 
        if (!preg_match($valid_uname, $value))
            return 2;
      
  }   


function cdp_email_users_notificationsx($array)
{

  $email = "";
  $contador = 0;

  while ($contador < count($array)) {

    $email .= $array[$contador] . ",";
    $contador++;
  }

  $email = substr($email, 0, -1);

  return $email;
}



function cdb_m_format($amount)
{
  $db = new Conexion;


  if ($currency_decimal_digits == 'true') {
    $dec_digit = 2;
  } else {
    $dec_digit = 0;
  }

  if ($currency_symbol_position == 's') {
    $retval =
      number_format($amount, $dec_digit, $curr_point, $curr_sep) . ' ' . $currency_code;
  } else {
    $retval =
      $currency_code .
      ' ' .
      number_format($amount, $dec_digit, $curr_point, $curr_sep);
  }

  return $retval;
}


function cdb__forma($amount)
{

  $db = new Conexion;

  if ($curr_symbol == '') {
    $currency_code = $curr_money;
  } else {
    $currency_code = $curr_symbol;
  }

  $currency_decimal_digits = $curr_decimal;
  $currency_symbol_position = $curr_currency;

  if ($currency_decimal_digits == 'true') {
    $dec_digit = 2;
  } else {
    $dec_digit = 0;
  }

  $retval =  number_format($amount, $dec_digit, $curr_point, $curr_sep);

  return $retval;
}


function getSizex($size, $precision = 2, $long_name = false, $real_size = true)
{
  if ($size == 0) {
    return '-/-';
  } else {
    $base = $real_size ? 1024 : 1000;
    $pos = 0;
    while ($size > $base) {
      $size /= $base;
      $pos++;
    }
    $prefix = _getSizePrefix($pos);
    $size_name = $long_name ? $prefix . "bytes" : $prefix[0] . 'B';
    return round($size, $precision) . ' ' . ucfirst($size_name);
  }
}



function _getSizePrefixx($pos)
{
  switch ($pos) {
    case 00:
      return "";
    case 01:
      return "kilo";

    case 02:
      return "mega";
    case 03:
      return "giga";
    default:
      return "?-";
  }
}


function obtenerNombreMes($numeroMes) {
    // Array con los nombres de los meses en español
    $meses = array(
        1 => "Jan", 
        2 => "Feb", 
        3 => "Mar", 
        4 => "Apr", 
        5 => "may", 
        6 => "Jun", 
        7 => "Jul", 
        8 => "Aug", 
        9 => "Sept", 
        10 => "Oct", 
        11 => "Nov", 
        12 => "Dec"
    );

    // Verificar si el número de mes está dentro del rango válido
    if ($numeroMes >= 1 && $numeroMes <= 12) {
        return $meses[$numeroMes];
    } else {
        return "Invalid month";
    }
}



function cdp_round_outx($valor)
{
  $float_redondeado = round($valor * 100) / 100;
  return $float_redondeado;
}

function get_first_name($full_name)
{
    $parts = explode(' ', $full_name);
    
    return $parts[0];
}

function get_last_name($full_name)
{
  $parts = explode(' ', $full_name);

  array_shift($parts);

  return implode(' ', $parts);
}

function get_fullname($first_name, $last_name)
{
    $full_name = $first_name . ' ' . $last_name;
    
    return $full_name;
}


function calculateDistance1($origin, $destination) {
  $apiKey = 'AIzaSyCAP41rsfjKCKORsVRuSM_4ff6f7YGV7kQ';
  $url = "https://maps.googleapis.com/maps/api/distancematrix/json?origins=$origin&destinations=$destination&key=$apiKey";
  $response = file_get_contents($url);
  
  $data = json_decode($response, TRUE);
  
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
function getRatesByDeliveryTypeAndBusinessType1($deliveryType, $businessType) {
    $rates = [
      'default' => [
          'SAME DAY (BEFORE 9PM)' => ['baseRate' => 10.00, 'additionalRatePerKm' => 0.55, 'baseKm' => 10],
          'SAME DAY (BEFORE 7PM)' => ['baseRate' => 10.00, 'additionalRatePerKm' => 0.55, 'baseKm' => 10],
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
          'SAME DAY (BEFORE 9PM)' => ['baseRate' => 10.00, 'additionalRatePerKm' => 0.55, 'baseKm' => 10],
          'SAME DAY (BEFORE 7PM)' => ['baseRate' => 10.00, 'additionalRatePerKm' => 0.55, 'baseKm' => 10],
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
          'SAME DAY (BEFORE 9PM)' => ['baseRate' => 5.00, 'additionalRatePerKm' => 0.33, 'baseKm' => 15],
          'SAME DAY (BEFORE 7PM)' => ['baseRate' => 5.00, 'additionalRatePerKm' => 0.33, 'baseKm' => 15],
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
          'SAME DAY (BEFORE 9PM)' => ['baseRate' => 5.00, 'additionalRatePerKm' => 0.00, 'baseKm' => 15],
          'SAME DAY (BEFORE 7PM)' => ['baseRate' => 5.00, 'additionalRatePerKm' => 0.00, 'baseKm' => 15],
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
          'SAME DAY (BEFORE 9PM)' => ['baseRate' => 4.00, 'additionalRatePerKm' => 0.00, 'baseKm' => 15],
          'SAME DAY (BEFORE 7PM)' => ['baseRate' => 4.00, 'additionalRatePerKm' => 0.00, 'baseKm' => 15],
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
          'SAME DAY (BEFORE 9PM)' => ['baseRate' => 5.00, 'additionalRatePerKm' => 0.33, 'baseKm' => 15],
          'SAME DAY (BEFORE 7PM)' => ['baseRate' => 5.00, 'additionalRatePerKm' => 0.33, 'baseKm' => 15],
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
          'SAME DAY (BEFORE 9PM)' => ['baseRate' => 5.00, 'additionalRatePerKm' => 0.33, 'baseKm' => 15],
          'SAME DAY (BEFORE 7PM)' => ['baseRate' => 5.00, 'additionalRatePerKm' => 0.33, 'baseKm' => 15],
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
          'SAME DAY (BEFORE 9PM)' => ['baseRate' => 7.00, 'additionalRatePerKm' => 0.00, 'baseKm' => 15],
          'SAME DAY (BEFORE 7PM)' => ['baseRate' => 7.00, 'additionalRatePerKm' => 0.00, 'baseKm' => 15],
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
  if ($businessType == 'flower_shop' || $businessType == 'pharmacy') {
      return $rates[$businessType][$deliveryType] ?? null;
  } else if ($businessType == 'special') {
      return $rates['special'][$deliveryType] ?? null;
  } else {
      return $rates['default'][$deliveryType] ?? null;
  }
}

// Function to calculate shipping price based on distance, base rate, and additional rate per kilometer
function calculateShippingPrice1($distance, $baseRate, $additionalRatePerKm, $baseKm) {
  // Calculate additional rate for distance beyond base kilometers
  $additionalDistance = max(0, ($distance - $baseKm));
  $additionalCharge = $additionalDistance * $additionalRatePerKm;

  // Calculate total shipping price
  $totalPrice = $baseRate + $additionalCharge;
  return $totalPrice;
}


function getAddressDetailsHelper($address) {
  $apiKey = "AIzaSyCAP41rsfjKCKORsVRuSM_4ff6f7YGV7kQ";
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