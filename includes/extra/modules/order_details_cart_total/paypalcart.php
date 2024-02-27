<?php
/* -----------------------------------------------------------------------------------------
   $Id: paypalcart.php 14327 2022-04-19 10:41:14Z GTB $

   modified eCommerce Shopsoftware
   http://www.modified-shop.org

   Copyright (c) 2009 - 2013 [www.modified-shop.org]
   -----------------------------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------------------------*/
  
  if (defined('MODULE_PAYMENT_PAYPAL_SECRET')
      && MODULE_PAYMENT_PAYPAL_SECRET != ''
      )
  {
    // include needed classes
    require_once(DIR_FS_EXTERNAL.'paypal/classes/PayPalPayment.php');
    
    $paypal = new PayPalPayment('paypalcart');
    if ($paypal->is_enabled()) {
      $smarty->assign('BUTTON_PAYPAL', $paypal->checkout_button());
      if (isset($_GET['payment_error'])) {
        include_once(DIR_WS_LANGUAGES . $_SESSION['language'] . '/modules/payment/paypalcart.php');
        $error = $paypal->get_error();
        $smarty->assign('error_message',  $error['error']);
      }
    }
  }