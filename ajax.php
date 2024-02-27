<?php
/* -----------------------------------------------------------------------------------------
   $Id: ajax.php 15238 2023-06-14 08:23:03Z GTB $

   modified eCommerce Shopsoftware
   http://www.modified-shop.org

   Copyright (c) 2013-2016 [www.hackersolutions.com]
   -----------------------------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------------------------*/

// set the level of error reporting
@ini_set('display_errors', false);
error_reporting(0);

// prevent redirect to cart
define('DISPLAY_CART', 'false');

if (isset($_REQUEST['speed'])) {
  // Start the clock for the page parse time log
  define('PAGE_PARSE_START_TIME', microtime(true));

  // configuration parameters
  if (file_exists('includes/local/configure.php')) {
    include_once('includes/local/configure.php');
  } else {
    include_once('includes/configure.php');
  }
} else {
  include_once('includes/application_top.php');
}

// extension
$ajax_ext = preg_replace("/[^a-z0-9\\.\\_]/i", "", $_REQUEST['ext']);

$ajax_ext_file = DIR_WS_INCLUDES . 'extra/ajax/' . $ajax_ext . '.php';

// response type (e.g. json, xml or html): default is json
$ajax_rt = (isset($_REQUEST['type']) ?  preg_replace("/[^h-x]/i", "", $_REQUEST['type']) : 'json');
if (isset($_REQUEST['type1']) && isset($_REQUEST['type2'])) {
  $ajax_rt1 = (isset($_REQUEST['type1']) ?  preg_replace("/[^a-z]/i", "", $_REQUEST['type1']) : 'application');
  $ajax_rt2 = (isset($_REQUEST['type2']) ?  preg_replace("/[^a-z]/i", "", $_REQUEST['type2']) : 'json');  
}

// return error if file not exist or include it
!file_exists($ajax_ext_file) ? die('extension does not exist!') : include_once($ajax_ext_file);

// execute extension in ajax module dir
if (function_exists($ajax_ext)) {
  $response = $ajax_ext();
} elseif (class_exists($ajax_ext)) {
  $object =  new $ajax_ext;
  $method = isset($_REQUEST['method']) ? $_REQUEST['method'] : null;
  if ($method && method_exists($object, $method)) {
    $response = $object->$method();
  } elseif (method_exists($object, 'init')) {
    $response = $object->init($method);
  } else {
    die("method does not exist");
  }
} else {
  die("function or class does not exist");
}

// gzip compression
if (!isset($_REQUEST['speed'])
    && defined('GZIP_COMPRESSION')
    && GZIP_COMPRESSION == 'true' 
    && isset($ext_zlib_loaded)
    && $ext_zlib_loaded == true 
    && isset($ini_zlib_output_compression)
    && $ini_zlib_output_compression < 1
    && $encoding = xtc_check_gzip()
    )
{
  header('Content-Encoding: ' . $encoding);
}

if ($ajax_rt == 'json' || (isset($ajax_rt2) && $ajax_rt2 == 'json')) {
  $response = json_encode($response);
  header('Content-Type: application/json');
} elseif (isset($ajax_rt1) && isset($ajax_rt2)) {
  header('Content-Type: '.$ajax_rt1.'/'.$ajax_rt2);
} else {
  header('Content-Type: text/'.$ajax_rt);
}

// response headers
header("Expires: Sun, 19 Nov 1978 05:00:00 GMT");
header("Last-Modified: " . gmdate('D, d M Y H:i:s') . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

// output
echo $response;

// gzip compression
if (!isset($_REQUEST['speed'])
    && defined('GZIP_COMPRESSION')
    && GZIP_COMPRESSION == 'true' 
    && isset($ext_zlib_loaded)
    && $ext_zlib_loaded == true 
    && isset($ini_zlib_output_compression)
    && $ini_zlib_output_compression < 1
    )
{
  xtc_gzip_output(GZIP_LEVEL);
}

// log parse time
if (defined('STORE_PAGE_PARSE_TIME') && STORE_PAGE_PARSE_TIME == 'true') {
  $parse_time = number_format((microtime(true) - PAGE_PARSE_START_TIME), 3);
  if ($parse_time >= STORE_PAGE_PARSE_TIME_THRESHOLD) {
    error_log(date(STORE_PARSE_DATE_TIME_FORMAT) . ' [' . $parse_time . 's] ' . getenv('REQUEST_URI') . "\n", 3, DIR_FS_LOG.'mod_parsetime_'. date('Y-m-d') .'.log');
  }
}
