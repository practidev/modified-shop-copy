<?php
/* -----------------------------------------------------------------------------------------
   $Id: paypalbancontact.php 15580 2023-11-20 19:07:27Z GTB $

   modified eCommerce Shopsoftware
   http://www.modified-shop.org

   Copyright (c) 2009 - 2013 [www.modified-shop.org]
   -----------------------------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------------------------*/


$lang_array = array(
  'MODULE_PAYMENT_PAYPALBANCONTACT_TEXT_TITLE' => 'Bancontact via PayPal',
  'MODULE_PAYMENT_PAYPALBANCONTACT_TEXT_ADMIN_TITLE' => 'Bancontact via PayPal',
  'MODULE_PAYMENT_PAYPALBANCONTACT_TEXT_INFO' => '<img src="https://www.paypalobjects.com/images/checkout/alternative_payments/paypal_bancontact_color.svg" />',
  'MODULE_PAYMENT_PAYPALBANCONTACT_TEXT_DESCRIPTION' => 'After "confirm" your will be routet to Bancontact to pay your order.<br />Back in shop you will get your order-mail.<br />PayPal is the safer way to pay online. We keep your details safe from others and can help you get your money back if something ever goes wrong.<br /><br /><strong><font color="red">ATTENTION:</font></strong> In order for the order status to be set correctly, the following <a href="'.xtc_href_link('paypal_webhook.php').'">webhooks</a> must be set in the PayPal configuration so that the status is changed correctly:<ul><li>PAYMENT.CAPTURE.COMPLETED</li><li>PAYMENT.CAPTURE.DECLINED</li><li>PAYMENT.CAPTURE.DENIED</li><li>PAYMENT.CAPTURE.PENDING</li></ul>',
  'MODULE_PAYMENT_PAYPALBANCONTACT_ALLOWED_TITLE' => 'Allowed zones',
  'MODULE_PAYMENT_PAYPALBANCONTACT_ALLOWED_DESC' => 'The module can be used for the following zones.',
  'MODULE_PAYMENT_PAYPALBANCONTACT_STATUS_TITLE' => 'Enable Bancontact via PayPal',
  'MODULE_PAYMENT_PAYPALBANCONTACT_STATUS_DESC' => 'Do you want to accept PayPal Bancontact payments?',
  'MODULE_PAYMENT_PAYPALBANCONTACT_SORT_ORDER_TITLE' => 'Sort order',
  'MODULE_PAYMENT_PAYPALBANCONTACT_SORT_ORDER_DESC' => 'Sort order of the view. Lowest numeral will be displayed first',
  'MODULE_PAYMENT_PAYPALBANCONTACT_ZONE_TITLE' => 'Payment zone',
  'MODULE_PAYMENT_PAYPALBANCONTACT_ZONE_DESC' => 'If a zone is choosen, the payment method will be valid for this zone only.',
  'MODULE_PAYMENT_PAYPALBANCONTACT_LP' => '<br /><br /><a target="_blank" href="http://www.paypal.com/de/webapps/mpp/referral/paypal-business-account2?partner_id=EHALBVD4M2RQS"><strong>Create PayPal account now.</strong></a>',

  'MODULE_PAYMENT_PAYPALBANCONTACT_TEXT_EXTENDED_DESCRIPTION' => '<strong><font color="red">ATTENTION:</font></strong> Please setup PayPal configuration under "Partner Modules" -> "PayPal" -> <a href="'.xtc_href_link('paypal_config.php').'"><strong>"PayPal Configuration"</strong></a>!',

  'MODULE_PAYMENT_PAYPALBANCONTACT_TEXT_ERROR_HEADING' => 'Note',
  'MODULE_PAYMENT_PAYPALBANCONTACT_TEXT_ERROR_MESSAGE' => 'The payment with Bancontact via PayPal was cancelled',
);


foreach ($lang_array as $key => $val) {
  defined($key) or define($key, $val);
}
