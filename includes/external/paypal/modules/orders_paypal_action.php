<?php
/* -----------------------------------------------------------------------------------------
   $Id: orders_paypal_action.php 15116 2023-04-25 11:24:05Z GTB $

   modified eCommerce Shopsoftware
   http://www.modified-shop.org

   Copyright (c) 2009 - 2013 [www.modified-shop.org]
   -----------------------------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------------------------*/

if (isset($oID) && $oID != '') {
  $order = new order($oID);

  $orders_v1_array = array(
    'paypalclassic',
    'paypalcart',
    'paypalplus',
    'paypallink',
    'paypalpluslink',
    'paypalsubscription',
  );

  $orders_v2_array = array(
    'paypal',
    'paypalacdc',
    'paypalpui',
    'paypalexpress',
    'paypalcard',
    'paypalsepa',
    'paypalsofort',
    'paypaltrustly',
    'paypalprzelewy',
    'paypalmybank',
    'paypalideal',
    'paypalgiropay',
    'paypaleps',
    'paypalblik',
    'paypalbancontact',
  );
  
  if (in_array($order->info['payment_method'], $orders_v1_array)
      || (isset($_POST['cmd']) && strpos($_POST['cmd'] , 'tracking') !== false)
      )
  {
    require_once(DIR_FS_EXTERNAL.'paypal/classes/PayPalInfo.php');
    $paypal = new PayPalInfo($order->info['payment_method']);
    
    // action
    if (isset($_POST['cmd'])) {
      switch ($_POST['cmd']) {
        case 'refund':
          if ($_POST['refund_price'] > 0) {
            $response = $paypal->refund_payment($order->info['order_id'], $_POST['refund_price'], $_POST['refund_comment']);

            if (is_object($response) && $response->state == 'completed') {
              xtc_db_query("UPDATE ".TABLE_PAYPAL_INSTRUCTIONS."
                               SET amount = amount - ".(double)$_POST['refund_price']."
                             WHERE orders_id = '".(int)$oID."'");
            }
          } else {
            $_SESSION['pp_error'] = TEXT_PAYPAL_ERROR_AMOUNT;
          }
          break;
        case 'capture':
          if ($_POST['capture_price'] > 0) {
            $paypal->capture_payment_admin($order->info['order_id'], $_POST['capture_price'], (isset($_POST['final_capture'])));
          } else {
            $_SESSION['pp_error'] = TEXT_PAYPAL_ERROR_AMOUNT;
          }
          break;
        case 'cancel':
          $response = $paypal->cancel_subscription($order->info['order_id']);
          if ($response === false) {
            $_SESSION['pp_error'] = TEXT_PAYPAL_ERROR_CANCEL;
          }
          break;
        case 'addtracking':
          $response = $paypal->addTracking($order->info['order_id'], $_POST['tracking']);
          if (is_array($response) && count($response) > 0) {
            $_SESSION['pp_error'] = implode('<br/>', $response);
          }
          break;
        case 'canceltracking':
          $response = $paypal->cancelTracking($order->info['order_id'], $_POST['tracking_id']);
          if (is_array($response) && count($response) > 0) {
            $_SESSION['pp_error'] = implode('<br/>', $response);
          }
          break;
      }
    }
  }

  if (in_array($order->info['payment_method'], $orders_v2_array)) {
    require_once(DIR_FS_EXTERNAL.'paypal/classes/PayPalPaymentV2.php');
    $paypal = new PayPalPaymentV2($order->info['payment_method']);

    // action
    if (isset($_POST['cmd'])) {
      switch ($_POST['cmd']) {
        case 'refund':
          if ($_POST['refund_price'] > 0) {
            $response = $paypal->refundOrder($_POST['refund_id'], $_POST['refund_price'], $order->info['currency'], $_POST['refund_comment']);
            
            if (is_object($response)) {
              if ($response->status == 'COMPLETED') {
                xtc_db_query("UPDATE ".TABLE_PAYPAL_INSTRUCTIONS."
                                 SET amount = amount - ".(double)$_POST['refund_price']."
                               WHERE orders_id = '".(int)$oID."'");
              }
              
              if (method_exists($response, 'getMessage')) {
                $messages = $response->getMessage();
                $messages = json_decode($messages, true);
                if (isset($messages['details'])) {
                  $_SESSION['pp_error'] = $messages['details'][0]['description'];
                }
              }
            }
          } else {
            $_SESSION['pp_error'] = TEXT_PAYPAL_ERROR_AMOUNT;
          }
          break;
        case 'capture':
          if ($_POST['capture_price'] > 0) {
            $response = $paypal->CaptureAuthorizedOrder($_POST['authorize_id'], $_POST['capture_price'], $order->info['currency'], (isset($_POST['final_capture'])));

            if (method_exists($response, 'getMessage')) {
              $messages = $response->getMessage();
              $messages = json_decode($messages, true);
              if (isset($messages['details'])) {
                $_SESSION['pp_error'] = $messages['details'][0]['description'];
              }
            }
          } else {
            $_SESSION['pp_error'] = TEXT_PAYPAL_ERROR_AMOUNT;
          }
          break;
      }
    }    
  }
}
