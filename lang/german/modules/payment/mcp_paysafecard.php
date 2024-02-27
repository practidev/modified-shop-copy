<?php
/**
 *
 * @package    micropayment
 * @copyright  Copyright (c) 2022 Micropayment GmbH (http://www.micropayment.de)
 * @author     micropayment GmbH (TE) <support@micropayment.de>
 */
include_once('mcp_service.php');
define('MODULE_PAYMENT_MCP_PAYSAFECARD_TEXT_DESCRIPTION', 'micropayment&trade; Paysafecard Modul
<br /><br />
Links<br />
<b>Tools</b><br />
<a target="_new" href="../callback/micropayment/cleanup.php">
    <input type="button" value="Bestellungen aufr&auml;umen">
</a><br />
<br />
<b>Extern</b><br />
<a href="https://www.micropayment.de/products/plugins/modified/?_r=gst&_src=ctor">
    <input type="button" value="Handbuch">
</a>&nbsp;
<a target="_new" href="https://r120.micropayment.de">
    <input type="button" value="Micropayment Registrierung">
    </a>');
define('MODULE_PAYMENT_MCP_PAYSAFECARD_TEXT_TITLE', 'micropayment&trade; Paysafecard');
define('MODULE_PAYMENT_MCP_PAYSAFECARD_TEXT_TITLE_EXTERN', 'Paysafecard');
define('MODULE_PAYMENT_MCP_PAYSAFECARD_TEXT_INFO', '
<div style="margin:10px; height:140px;">
  <div style="float:right;"><img src="./images/micropayment/logo_small.png" width="150"/></div>
  <div style="float:left;">
    <b>Bitte halten Sie Ihre Paysafecard Daten bereit.</b></br />
    Um Ihre Bestellung abzuschlie&szlig;en, leiten wir Sie nun auf die Webseite<br /> unseres Zahlungsdienstleisters micropayment&trade; weiter.<br /><br />
    &#10004; sicher &nbsp; &#10004; einfach &nbsp; &#10004; registrierungsfrei
  </div>
</div>');
define('MODULE_PAYMENT_MCP_PAYSAFECARD_STATUS_TITLE','Paysafecard');
define('MODULE_PAYMENT_MCP_PAYSAFECARD_STATUS_DESC','Paysafecardmodul von micropayment&trade;');
define('MODULE_PAYMENT_MCP_PAYSAFECARD_MINIMUM_AMOUNT_TITLE','Mindestbestellwert');
define('MODULE_PAYMENT_MCP_PAYSAFECARD_MINIMUM_AMOUNT_DESC','Mindestbestellwert');
define('MODULE_PAYMENT_MCP_PAYSAFECARD_MAXIMUM_AMOUNT_TITLE','Maximalbestellwert');
define('MODULE_PAYMENT_MCP_PAYSAFECARD_MAXIMUM_AMOUNT_DESC','Maximalbestellwert');
define('MODULE_PAYMENT_MCP_PAYSAFECARD_SORT_ORDER_TITLE','Positionierung');
define('MODULE_PAYMENT_MCP_PAYSAFECARD_SORT_ORDER_DESC','Position in der Liste der Bezahlarten');
define('MODULE_PAYMENT_MCP_PAYSAFECARD_ALLOWED_TITLE','L&auml;nderauswahl');
define('MODULE_PAYMENT_MCP_PAYSAFECARD_ALLOWED_DESC','Bestellungen nur aus den L&auml;ndern erlauben (Komma separierte Liste z.b. DE,EN)');
