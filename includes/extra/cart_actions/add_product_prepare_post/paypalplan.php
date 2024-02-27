<?php
/* -----------------------------------------------------------------------------------------
   $Id: paypalplan.php 14327 2022-04-19 10:41:14Z GTB $

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
    
    $paypal_subscription = new PayPalPayment('paypalsubscription');
    if ($paypal_subscription->is_enabled()) {
      $plan_query = xtDBquery("SELECT *
                                 FROM `paypal_plan`
                                WHERE products_id = '".(int)$_POST['products_id']."'
                                  AND plan_status = 1");
      if (xtc_db_num_rows($plan_query, true) > 0) {
        if (!isset($_POST['plan_id'])) {
          $messageStack->add_session('paypalplan', TEXT_PAYPAL_ERROR_NO_PLAN);
          xtc_redirect(xtc_href_link(FILENAME_PRODUCT_INFO, 'products_id='.(int)$_POST['products_id'], 'NONSSL'));
        }
      }
    }
  }