<?php
/* -----------------------------------------------------------------------------------------
   $Id: paypalprzelewy.php 15580 2023-11-20 19:07:27Z GTB $

   modified eCommerce Shopsoftware
   http://www.modified-shop.org

   Copyright (c) 2009 - 2013 [www.modified-shop.org]
   -----------------------------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------------------------*/


$lang_array = array(
  'MODULE_PAYMENT_PAYPALPRZELEWY_TEXT_TITLE' => 'Przelewy24 via PayPal',
  'MODULE_PAYMENT_PAYPALPRZELEWY_TEXT_ADMIN_TITLE' => 'Przelewy24 via PayPal',
  'MODULE_PAYMENT_PAYPALPRZELEWY_TEXT_INFO' => '<img src="https://www.paypalobjects.com/images/checkout/alternative_payments/paypal_przelewy24_color.svg" />',
  'MODULE_PAYMENT_PAYPALPRZELEWY_TEXT_DESCRIPTION' => 'Sie werden nach dem "Best&auml;tigen" zu Przelewy24 geleitet, um hier Ihre Bestellung zu bezahlen.<br />Danach gelangen Sie zur&uuml;ck in den Shop und erhalten Ihre Bestell-Best&auml;tigung.<br />Jetzt schneller bezahlen mit unbegrenztem PayPal-K&auml;uferschutz - nat&uuml;rlich kostenlos.<br /><br /><strong><font color="red">ACHTUNG:</font></strong> Damit der Bestellstatus korrekt gesetzt wird, m&uuml;ssen folgende <a href="'.xtc_href_link('paypal_webhook.php').'">Webhooks</a> in der PayPal Konfiguration eingestellt werden , damit der Status korrekt umgestellt wird:<ul><li>PAYMENT.CAPTURE.COMPLETED</li><li>PAYMENT.CAPTURE.DECLINED</li><li>PAYMENT.CAPTURE.DENIED</li><li>PAYMENT.CAPTURE.PENDING</li></ul>',
  'MODULE_PAYMENT_PAYPALPRZELEWY_ALLOWED_TITLE' => 'Erlaubte Zonen',
  'MODULE_PAYMENT_PAYPALPRZELEWY_ALLOWED_DESC' => 'Das Modul kann f&uuml;r die folgenden Zonen verwendet werden.',
  'MODULE_PAYMENT_PAYPALPRZELEWY_STATUS_TITLE' => 'Przelewy24 via PayPal aktivieren',
  'MODULE_PAYMENT_PAYPALPRZELEWY_STATUS_DESC' => 'M&ouml;chten Sie Zahlungen per PayPal Przelewy24 akzeptieren?',
  'MODULE_PAYMENT_PAYPALPRZELEWY_SORT_ORDER_TITLE' => 'Anzeigereihenfolge',
  'MODULE_PAYMENT_PAYPALPRZELEWY_SORT_ORDER_DESC' => 'Reihenfolge der Anzeige. Kleinste Ziffer wird zuerst angezeigt',
  'MODULE_PAYMENT_PAYPALPRZELEWY_ZONE_TITLE' => 'Zahlungszone',
  'MODULE_PAYMENT_PAYPALPRZELEWY_ZONE_DESC' => 'Wenn eine Zone ausgew&auml;hlt ist, gilt die Zahlungsmethode nur f&uuml;r diese Zone.',
  'MODULE_PAYMENT_PAYPALPRZELEWY_LP' => '<br /><br /><a target="_blank" href="http://www.paypal.com/de/webapps/mpp/referral/paypal-business-account2?partner_id=EHALBVD4M2RQS"><strong>Jetzt PayPal Konto hier erstellen.</strong></a>',

  'MODULE_PAYMENT_PAYPALPRZELEWY_TEXT_EXTENDED_DESCRIPTION' => '<strong><font color="red">ACHTUNG:</font></strong> Bitte nehmen Sie noch die Einstellungen unter "Partner Module" -> "PayPal" -> <a href="'.xtc_href_link('paypal_config.php').'"><strong>"PayPal Konfiguration"</strong></a> vor!',

  'MODULE_PAYMENT_PAYPALPRZELEWY_TEXT_ERROR_HEADING' => 'Hinweis',
  'MODULE_PAYMENT_PAYPALPRZELEWY_TEXT_ERROR_MESSAGE' => 'Die Zahlung mit Przelewy24 via PayPal wurde abgebrochen',  
);


foreach ($lang_array as $key => $val) {
  defined($key) or define($key, $val);
}
