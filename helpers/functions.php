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
    
    $num_parts = count($parts);
    
    return $parts[$num_parts - 1];
}

function get_fullname($first_name, $last_name)
{
    $full_name = $first_name . ' ' . $last_name;
    
    return $full_name;
}
