<?php
/* --------------------------------------------------------------
   $Id: specials.php 14672 2022-07-18 17:18:38Z GTB $   

   modified eCommerce Shopsoftware
   http://www.modified-shop.org

   Copyright (c) 2009 - 2013 [www.modified-shop.org]
   --------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(specials.php,v 1.10 2002/01/31); www.oscommerce.com 
   (c) 2003	 nextcommerce (specials.php,v 1.4 2003/08/14); www.nextcommerce.org
   (c) 2006 xt:Commerce; www.xt-commerce.com

   Released under the GNU General Public License 
   --------------------------------------------------------------*/

define('HEADING_TITLE', 'Sonderangebote');

define('TABLE_HEADING_PRODUCTS', 'Artikel');
define('TABLE_HEADING_PRODUCTS_QUANTITY', 'Anzahl Artikel (Lager)');
define('TABLE_HEADING_SPECIALS_QUANTITY', 'Anzahl Sonderangebote');
define('TABLE_HEADING_START_DATE', 'G&uuml;ltig ab');
define('TABLE_HEADING_EXPIRES_DATE', 'G&uuml;ltig bis');
define('TABLE_HEADING_PRODUCTS_PRICE', 'Artikelpreis');
define('TABLE_HEADING_STATUS', 'Status');
define('TABLE_HEADING_ACTION', 'Aktion');
define('TABLE_HEADING_EDIT','Edit');

define('TEXT_SPECIALS_PRODUCT', 'Artikel:');
define('TEXT_SPECIALS_SPECIAL_PRICE', 'Angebotspreis:');
define('TEXT_SPECIALS_SPECIAL_QUANTITY', 'Anzahl:');
define('TEXT_SPECIALS_START_DATE', 'G&uuml;ltig ab: <small>(JJJJ-MM-TT)</small>');
define('TEXT_SPECIALS_EXPIRES_DATE', 'G&uuml;ltig bis: <small>(JJJJ-MM-TT)</small>');
define('TEXT_SPECIALS_SPECIAL_PRODUCTS_PRICE', 'G&uuml;nstigster Preis (letzte 30 Tage):');

define('TEXT_INFO_DATE_ADDED', 'hinzugef&uuml;gt am:');
define('TEXT_INFO_LAST_MODIFIED', 'letzte &Auml;nderung:');
define('TEXT_INFO_NEW_PRICE', 'neuer Preis:');
define('TEXT_INFO_ORIGINAL_PRICE', 'alter Preis:');
define('TEXT_INFO_PERCENTAGE', 'Prozent:');
define('TEXT_INFO_START_DATE', 'G&uuml;ltig ab:');
define('TEXT_INFO_EXPIRES_DATE', 'G&uuml;ltig bis:');
define('TEXT_INFO_STATUS_CHANGE', 'Deaktiviert am:');

define('TEXT_ACTIVE_ELEMENT','Aktives Element');
define('TEXT_MARKED_ELEMENTS','Markierte Elemente');
define('TEXT_INFO_HEADING_DELETE_ELEMENTS', 'Elemente l&ouml;schen');

define('TEXT_INFO_HEADING_DELETE_SPECIALS', 'Sonderangebot l&ouml;schen');
define('TEXT_INFO_DELETE_INTRO', 'Sind Sie sicher, dass Sie das Sonderangebot l&ouml;schen m&ouml;chten?');

define('TEXT_IMAGE_NONEXISTENT','Kein Bild verf&uuml;gbar!');

define('TEXT_SPECIALS_PRICE_TIP', 'Sie k&ouml;nnen im Feld Angebotspreis auch prozentuale Werte angeben, z.B.: <strong>20%</strong><br>Wenn Sie einen neuen Preis eingeben, m&uuml;ssen die Nachkommastellen mit einem \'.\' getrennt werden, z.B.: <strong>49.99</strong>');
define('TEXT_SPECIALS_QUANTITY_TIP', 'Im Feld <strong>Anzahl</strong> k&ouml;nnen Sie die St&uuml;ckzahl eingeben, f&uuml;r die das Angebot gelten soll.<br>Unter "Konfiguration" -> "Lagerverwaltungs Optionen" -> "&Uuml;berpr&uuml;fen der Sonderangebote" k&ouml;nnen Sie entscheiden, ob der Bestand von Sonderangeboten &uuml;berpr&uuml;ft werden soll.');
define('TEXT_SPECIALS_START_DATE_TIP', 'Geben Sie das Datum an, ab wann der Angebotspreis gelten soll.<br>');
define('TEXT_SPECIALS_EXPIRES_DATE_TIP', 'Lassen Sie das Feld <strong>G&uuml;ltig bis</strong> leer, wenn der Angebotspreis zeitlich unbegrenzt gelten soll.<br>');
define('TEXT_SPECIALS_PRODUCTS_PRICE_TIP', 'Geben Sie den g&uuml;nstigsten Preis der letzten 30 Tage an. Wenn sie das Feld leer lassen, wird der aktuelle Artikelpreis verwendet.');
