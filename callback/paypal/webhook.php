<?php
/* -----------------------------------------------------------------------------------------
   $Id: webhook.php 14827 2022-12-15 14:51:41Z Tomcraft $

   modified eCommerce Shopsoftware
   http://www.modified-shop.org

   Copyright (c) 2009 - 2013 [www.modified-shop.org]
   -----------------------------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------------------------*/


chdir('../../');
include('includes/application_top.php');

// include needed functions
require_once(DIR_FS_INC.'get_external_content.inc.php');

// include needed classes
require_once(DIR_FS_EXTERNAL.'paypal/classes/PayPalPayment.php');
require_once(DIR_FS_EXTERNAL.'paypal/classes/PayPalPaymentV2.php');
require_once(DIR_WS_CLASSES.'order.php');

$request_json = get_external_content('php://input', 3, false);
$request = json_decode($request_json, true);

if (is_array($request)
    && isset($request['resource'])
    && is_array($request['resource'])
    && (array_key_exists('parent_payment', $request['resource']) || array_key_exists('supplementary_data', $request['resource']))
    )
{
  if (array_key_exists('parent_payment', $request['resource'])) {
    $payment_id = $request['resource']['parent_payment'];
    $version = 1;
  } else {
    $payment_id = $request['resource']['supplementary_data']['related_ids']['order_id'];
    $version = 2;
  }

  $check_query = xtc_db_query("SELECT p.*,
                                      o.orders_status,
                                      o.payment_class,
                                      o.customers_id
                                 FROM ".TABLE_PAYPAL_PAYMENT." p
                                 JOIN ".TABLE_ORDERS." o
                                      ON o.orders_id = p.orders_id
                                WHERE p.payment_id = '".xtc_db_input($payment_id)."'");

  if (xtc_db_num_rows($check_query) > 0) {
    $check = xtc_db_fetch_array($check_query);
    
    if ($version == 1) {
      $paypal = new PayPalPayment($check['payment_class']);
    } else {
      $paypal = new PayPalPaymentV2($check['payment_class']);
      if ($check['payment_class'] == 'paypalpui') {
        $PayPalOrder = $paypal->FinishOrderPui($check['orders_id']);
        
        if (is_object($PayPalOrder)
            && $PayPalOrder->status == 'COMPLETED'
            && $check['send_order'] == 1
            )
        {
          $smarty = new Smarty();
          $insert_id = $check['orders_id'];
          $_SESSION['customer_id'] = $check['customers_id'];
          include(DIR_FS_CATALOG.'send_order.php');
          unset($_SESSION['customer_id']);
          
          xtc_db_query("UPDATE ".TABLE_PAYPAL_PAYMENT."
                           SET send_order = 0
                         WHERE paypal_id = '".(int)$check['paypal_id']."'");
        }
      }
    }
    
    $orders_status_id = $paypal->get_config($request['event_type']);
    if ($orders_status_id < 0) {
      $orders_status_id = $check['orders_status'];
    }
    
    $paypal->update_order($request['summary'], $orders_status_id, $check['orders_id']);
  } else {
    // order is missing
    header("HTTP/1.0 404 Not Found");
    header("Status: 404 Not Found");
  }
} else {
  // order is missing
  header("HTTP/1.0 404 Not Found");
  header("Status: 404 Not Found");
}
?>