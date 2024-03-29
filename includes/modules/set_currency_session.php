<?php
/* -----------------------------------------------------------------------------------------
   $Id: set_currency_session.php 15592 2023-11-27 12:08:10Z GTB $

   modified eCommerce Shopsoftware
   http://www.modified-shop.org

   Copyright (c) 2009 - 2013 [www.modified-shop.org]
   -----------------------------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------------------------*/
   
// currency
defined('LANGUAGE_CURRENCY') OR define('LANGUAGE_CURRENCY', DEFAULT_CURRENCY);

if (!isset ($_SESSION['currency']) || isset ($_GET['currency']) || ((USE_DEFAULT_LANGUAGE_CURRENCY == 'true') && (LANGUAGE_CURRENCY != $_SESSION['currency']))) {
  if (isset ($_GET['currency'])) {
    $_GET['currency'] = xtc_input_validation($_GET['currency'], 'char');
    if (!$_SESSION['currency'] = xtc_currency_exists($_GET['currency']))
      $_SESSION['currency'] = xtc_currency_exists((USE_DEFAULT_LANGUAGE_CURRENCY == 'true') ? LANGUAGE_CURRENCY : DEFAULT_CURRENCY);
  } else {
    $_SESSION['currency'] = xtc_currency_exists((USE_DEFAULT_LANGUAGE_CURRENCY == 'true') ? LANGUAGE_CURRENCY : DEFAULT_CURRENCY);
  }
}

if (isset ($_SESSION['currency']) && $_SESSION['currency'] == '') {
  $_SESSION['currency'] = DEFAULT_CURRENCY;
}
