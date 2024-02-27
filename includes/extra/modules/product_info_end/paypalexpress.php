<?php
/* -----------------------------------------------------------------------------------------
   $Id: paypalexpress.php 14445 2022-05-09 12:04:55Z GTB $

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
    require_once(DIR_FS_EXTERNAL.'paypal/classes/PayPalPaymentV2.php');

    $paypal = new PayPalPaymentV2('paypalexpress');
    if ($paypal->is_enabled()
        && ($paypal->get_config('MODULE_PAYMENT_'.strtoupper($paypal->code).'_SHOW_PRODUCT') == '1'
            || $paypal->get_config('MODULE_PAYMENT_'.strtoupper($paypal->code).'_SHOW_PRODUCT_BNPL') == '1'
            )
        ) 
    {
      $paypal_smarty = new Smarty();
      $paypal_smarty->assign('language', $_SESSION['language']);
      $paypal_smarty->assign('product', true);
      if ($paypal->get_config('MODULE_PAYMENT_'.strtoupper($paypal->code).'_SHOW_PRODUCT') == '1') {
        $paypal_smarty->assign('paypalexpress', true);
      }
      if ($paypal->get_config('MODULE_PAYMENT_'.strtoupper($paypal->code).'_SHOW_PRODUCT_BNPL') == '1') {
        $paypal_smarty->assign('paypalbnpl', true);
      }
      $paypal_smarty->caching = 0;
  
      $tpl_file = DIR_FS_EXTERNAL.'paypal/templates/apms.html';
      if (is_file(DIR_FS_CATALOG.'templates/'.CURRENT_TEMPLATE.'/module/paypal/apms.html')) {
        $tpl_file = DIR_FS_CATALOG.'templates/'.CURRENT_TEMPLATE.'/module/paypal/apms.html';
      }
      $info_smarty->assign('ADD_CART_BUTTON_PAYPAL', $paypal_smarty->fetch($tpl_file));
    }
  }