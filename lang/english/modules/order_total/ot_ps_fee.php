<?php
/* -----------------------------------------------------------------------------------------
   $Id: ot_ps_fee.php 15246 2023-06-15 05:53:17Z GTB $

   modified eCommerce Shopsoftware
   http://www.modified-shop.org

   Copyright (c) 2009 - 2013 [www.modified-shop.org]
   -----------------------------------------------------------------------------------------
   based on:
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(ot_cod_fee.php,v 1.02 2003/02/24); www.oscommerce.com
   (C) 2001 - 2003 TheMedia, Dipl.-Ing Thomas Pl�nkers ; http://www.themedia.at & http://www.oscommerce.at
   (c) 2006 XT-Commerce (ot_ps_fee.php 899 2005-04-29 02)

   Released under the GNU General Public License
   ---------------------------------------------------------------------------------------*/


  define('MODULE_ORDER_TOTAL_PS_FEE_TITLE', 'Personal Shipping');
  define('MODULE_ORDER_TOTAL_PS_FEE_DESCRIPTION', 'Calculation of the Personal Shipping charge');

  define('MODULE_ORDER_TOTAL_PS_FEE_STATUS_TITLE','Personal Shipping');
  define('MODULE_ORDER_TOTAL_PS_FEE_STATUS_DESC','Calculation of the Personal Shipping charge');

  define('MODULE_ORDER_TOTAL_PS_FEE_SORT_ORDER_TITLE','Sort Order');
  define('MODULE_ORDER_TOTAL_PS_FEE_SORT_ORDER_DESC','Sort order of display');

  define('MODULE_ORDER_TOTAL_PS_FEE_TAX_CLASS_TITLE','Taxclass');
  define('MODULE_ORDER_TOTAL_PS_FEE_TAX_CLASS_DESC','Choose a taxclass.');

  function define_shipping_titles_ps() {
    $module_keys = str_replace('.php','',MODULE_SHIPPING_INSTALLED);
    $installed_shipping_modules = explode(';',$module_keys);
    //support for ot_shipping
    $installed_shipping_modules[] = 'free';

    if (count($installed_shipping_modules) > 0) {
      foreach($installed_shipping_modules as $shipping_code) {
        $module_type = 'shipping';
        $file = $shipping_code.'.php';
        $shipping_code = strtoupper($shipping_code);
        $title = '';

        if (defined('DIR_FS_LANGUAGES') && is_file(DIR_FS_LANGUAGES . 'english/modules/' . $module_type . '/' . $file)) {
          include_once(DIR_FS_LANGUAGES . 'english/modules/' . $module_type . '/' . $file);
          $title = constant('MODULE_SHIPPING_'.$shipping_code.'_TEXT_TITLE');
        }
        //support for ot_shipping
        $title = $shipping_code == 'FREE' ? 'Free Shipping (order total modul ot_shipping)' : $title;
        
        $shipping_code = ($shipping_code == 'FREEAMOUNT') ? 'FREEAMOUNT_FREE' : 'FEE_' . $shipping_code;

        define('MODULE_ORDER_TOTAL_PS_'.$shipping_code.'_TITLE',$title);
        define('MODULE_ORDER_TOTAL_PS_'.$shipping_code.'_DESC','&lt;ISO2-Code&gt;:&lt;Preis&gt;, ....<br />
        00 as ISO2-Code allows the PS shipping in all countries. If
        00 is used you have to enter it as last argument. If
        no 00:9.99 is entered the PS shipping into foreign countries will not be calculated
        (not possible). To exclude only one country, do not enter costs for this country.
        Example: DE:4.00,CH:,00:9.99<br />-> Explanation: Shipping to DE: 4&euro; / Shipping to CH: not possible
        / Shipping to the rest of the world: 9,99&euro;');
      }
    }
  }
  define_shipping_titles_ps();
?>