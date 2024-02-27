<?php
/* -----------------------------------------------------------------------------------------
   $Id: paypalcart.php 15256 2023-06-15 15:24:34Z GTB $

   modified eCommerce Shopsoftware
   http://www.modified-shop.org

   Copyright (c) 2009 - 2013 [www.modified-shop.org]
   -----------------------------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------------------------*/

chdir('../../');
include('includes/application_top.php');


// include needed classes
require_once(DIR_WS_CLASSES.'order.php');
require_once(DIR_FS_EXTERNAL.'paypal/classes/PayPalPayment.php');

$paypal = new PayPalPayment('paypalcart');
$paypal->validate_payment_paypalcart();

if (!isset($_SESSION['customer_id'])) {
  xtc_redirect(xtc_href_link(FILENAME_SHOPPING_CART, '', 'NONSSL'));
}

// shipping
$_SESSION['shipping'] = '';

$order = new order();

if ($order->content_type == 'virtual' 
    || ($order->content_type == 'virtual_weight') 
    || ($_SESSION['cart']->count_contents_virtual() == 0)) {
  $_SESSION['shipping'] = false;
  $_SESSION['sendto'] = false;
} elseif ($order->delivery['country']['iso_code_2'] != '') {
  $_SESSION['delivery_zone'] = $order->delivery['country']['iso_code_2'];
  if (isset($order->delivery['delivery_zone']) && $order->delivery['delivery_zone'] != '') {
    $_SESSION['delivery_zone'] = $order->delivery['delivery_zone'];
  }
}

// payment
$_SESSION['payment'] = 'paypalcart';

// billto
$_SESSION['billto'] = $_SESSION['customer_default_address_id'];

if ($order->billing['country']['iso_code_2'] != '') {
  $_SESSION['billing_zone'] = $order->billing['country']['iso_code_2'];
}

// paypal
$_SESSION['paypal']['payment_modules'] = 'paypalcart.php';

xtc_redirect(xtc_href_link(FILENAME_CHECKOUT_CONFIRMATION, 'conditions=true', 'NONSSL'));
?>