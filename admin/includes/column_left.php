<?php
  /* --------------------------------------------------------------
   $Id: column_left.php 15109 2023-04-19 16:52:42Z GTB $

   modified eCommerce Shopsoftware
   http://www.modified-shop.org

   Copyright (c) 2009 - 2013 [www.modified-shop.org]
   --------------------------------------------------------------
   based on:
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(column_left.php,v 1.15 2002/01/11); www.oscommerce.com
   (c) 2003 nextcommerce (column_left.php,v 1.25 2003/08/19); www.nextcommerce.org
   (c) 2006 XT-Commerce (content_manager.php 1304 2005-10-12)

   Released under the GNU General Public License
   --------------------------------------------------------------*/
   
defined( '_VALID_XTC' ) or die( 'Direct Access to this location is not allowed.' );

$admin_access = array();
if (($_SESSION['customers_status']['customers_status_id'] == '0')) {
  $admin_access_query = xtc_db_query("SELECT * FROM " . TABLE_ADMIN_ACCESS . " WHERE customers_id = ".(int)$_SESSION['customer_id']);
  $admin_access = xtc_db_fetch_array($admin_access_query); 
}

if (!function_exists('mainMenue')) {
  function mainMenue($box_title) {
    $html  = '<li>';            
    if (defined('NEW_ADMIN_STYLE')) {
      $html .= '<div class="dataNavHeadingContent"><a href="#"><strong>'.$box_title.'</strong></a></div>';
    } else {
      $html .= '<div class="dataNavHeadingContent"><strong>'.$box_title.'</strong></div>';
    }
    $html .= PHP_EOL .'<ul>'.PHP_EOL;
    return $html;
  }
}

if (!function_exists('endMenue')) {
  function endMenue($box_title) {
    global $menu_access;
    
    $html = '</ul>'.PHP_EOL;
    $html .= '</li>'.PHP_EOL;
    
    // extra menu
    if (function_exists('dynamicsAdds')) {
      $menu_access[] = dynamicsAdds($box_title);
      $menu_access = array_filter($menu_access);
    }
    
    if (count($menu_access) > 1) {
      echo implode(PHP_EOL, $menu_access);
      echo $html;
    }
  }
}

// extra menu
if (file_exists(DIR_WS_INCLUDES.'extra_menu.php')) {
  require_once(DIR_WS_INCLUDES.'extra_menu.php');
}

echo '<div id="cssmenu" class="suckertreemenu">';
echo '<ul id="treemenu1">';

//flags
echo '<li><div id="lang_flag">' . xtc_image('../lang/' .  $_SESSION['language'] .'/admin/images/' . 'icon.gif', $_SESSION['language']). '</div></li>';

//start
echo '<li><a href="' . xtc_href_link('start.php', '', 'NONSSL') . '" id="current"><b>' . TEXT_ADMIN_START . '</b></a></li>'; 

//customers
$menu_access = array();
$menu_access[] = mainMenue(BOX_HEADING_CUSTOMERS);
if ($admin_access['customers'] == '1') $menu_access[] = '<li><a href="' . xtc_href_link(FILENAME_CUSTOMERS, '', 'NONSSL') . '" class="menuBoxContentLink"> -' . BOX_CUSTOMERS . '</a></li>';
if ($admin_access['customers_status'] == '1') $menu_access[] = '<li><a href="' . xtc_href_link(FILENAME_CUSTOMERS_STATUS, '', 'NONSSL') . '" class="menuBoxContentLink"> -' . BOX_CUSTOMERS_STATUS . '</a></li>';
if ($admin_access['customers_group'] == '1' && GROUP_CHECK == 'true') $menu_access[] = '<li><a href="' . xtc_href_link('customers_group.php', '', 'NONSSL') . '" class="menuBoxContentLink"> -' . BOX_CUSTOMERS_GROUP . '</a></li>';
if ($admin_access['orders'] == '1') $menu_access[] = '<li><a href="' . xtc_href_link(FILENAME_ORDERS, '', 'NONSSL') . '" class="menuBoxContentLink"> -' . BOX_ORDERS . '</a></li>';
if ($admin_access['module_export'] == '1') $menu_access[] = '<li><a href="' . xtc_href_link(FILENAME_MODULE_EXPORT, 'set=export&module=dsgvo_export', 'NONSSL') . '" class="menuBoxContentLink"> -' . BOX_DSGVO_EXPORT . '</a></li>';
endMenue(BOX_HEADING_CUSTOMERS); 

//products
$menu_access = array();
$menu_access[] = mainMenue(BOX_HEADING_PRODUCTS);
if ($admin_access['categories'] == '1') $menu_access[] = '<li><a href="' . xtc_href_link(FILENAME_CATEGORIES, '', 'NONSSL') . '" class="menuBoxContentLink"> -' . BOX_CATEGORIES . '</a></li>';
if ($admin_access['products_attributes'] == '1') $menu_access[] = '<li><a href="' . xtc_href_link(FILENAME_PRODUCTS_ATTRIBUTES, '', 'NONSSL') . '" class="menuBoxContentLink"> -' . BOX_PRODUCTS_ATTRIBUTES . '</a></li>';
if ($admin_access['content_manager'] == '1') $menu_access[] = '<li><a href="' . xtc_href_link(FILENAME_CONTENT_MANAGER, 'set=product') . '" class="menuBoxContentLink"> -' . BOX_PRODUCTS_CONTENT . '</a></li>';
if ($admin_access['products_tags'] == '1') $menu_access[] = '<li><a href="' . xtc_href_link(FILENAME_PRODUCTS_TAGS, '', 'NONSSL') . '" class="menuBoxContentLink"> -' . BOX_PRODUCTS_TAGS . '</a></li>';
if ($admin_access['manufacturers'] == '1') $menu_access[] = '<li><a href="' . xtc_href_link(FILENAME_MANUFACTURERS, '', 'NONSSL') . '" class="menuBoxContentLink"> -' . BOX_MANUFACTURERS . '</a></li>';
if ($admin_access['reviews'] == '1') $menu_access[] = '<li><a href="' . xtc_href_link(FILENAME_REVIEWS, '', 'NONSSL') . '" class="menuBoxContentLink"> -' . BOX_REVIEWS . '</a></li>';
if ($admin_access['specials'] == '1') $menu_access[] = '<li><a href="' . xtc_href_link(FILENAME_SPECIALS, '', 'NONSSL') . '" class="menuBoxContentLink"> -' . BOX_SPECIALS . '</a></li>';
if ($admin_access['products_expected'] == '1') $menu_access[] = '<li><a href="' . xtc_href_link(FILENAME_PRODUCTS_EXPECTED, '', 'NONSSL') . '" class="menuBoxContentLink"> -' . BOX_PRODUCTS_EXPECTED . '</a></li>';
if ($admin_access['stats_stock_warning'] == '1') $menu_access[] = '<li><a href="' . xtc_href_link(FILENAME_STATS_STOCK_WARNING, '', 'NONSSL') . '" class="menuBoxContentLink"> -' . BOX_STOCK_WARNING . '</a></li>';
endMenue(BOX_HEADING_PRODUCTS); 

//modules
$menu_access = array();
$menu_access[] = mainMenue(BOX_HEADING_MODULES);
if ($admin_access['modules'] == '1') $menu_access[] = '<li><a href="' . xtc_href_link(FILENAME_MODULES, 'set=payment', 'NONSSL') . '" class="menuBoxContentLink"> -' . BOX_PAYMENT . '</a></li>';
if ($admin_access['modules'] == '1') $menu_access[] = '<li><a href="' . xtc_href_link(FILENAME_MODULES, 'set=shipping', 'NONSSL') . '" class="menuBoxContentLink"> -' . BOX_SHIPPING . '</a></li>';
if ($admin_access['modules'] == '1') $menu_access[] = '<li><a href="' . xtc_href_link(FILENAME_MODULES, 'set=ordertotal', 'NONSSL') . '" class="menuBoxContentLink"> -' . BOX_ORDER_TOTAL . '</a></li>';
if ($admin_access['modules'] == '1') {
  $menu_access[] = '<li><a href="javascript:void(0)" class="menuBoxContentLinkSub"> -' . BOX_MODULE_TYPE . '</a><ul>';
  $menu_access[] = '  <li><a href="' . xtc_href_link(FILENAME_MODULES, 'set=categories') . '" class="menuBoxContentLink"> -' . BOX_MODULE_CATEGORIES . '</a></li>';
  $menu_access[] = '  <li><a href="' . xtc_href_link(FILENAME_MODULES, 'set=checkout') . '" class="menuBoxContentLink"> -' . BOX_MODULE_CHECKOUT . '</a></li>';
  $menu_access[] = '  <li><a href="' . xtc_href_link(FILENAME_MODULES, 'set=main') . '" class="menuBoxContentLink"> -' . BOX_MODULE_MAIN . '</a></li>';
  $menu_access[] = '  <li><a href="' . xtc_href_link(FILENAME_MODULES, 'set=order') . '" class="menuBoxContentLink"> -' . BOX_MODULE_ORDER . '</a></li>';
  $menu_access[] = '  <li><a href="' . xtc_href_link(FILENAME_MODULES, 'set=product') . '" class="menuBoxContentLink"> -' . BOX_MODULE_PRODUCT . '</a></li>';
  $menu_access[] = '  <li><a href="' . xtc_href_link(FILENAME_MODULES, 'set=shopping_cart') . '" class="menuBoxContentLink"> -' . BOX_MODULE_SHOPPING_CART . '</a></li>';
  $menu_access[] = '  <li><a href="' . xtc_href_link(FILENAME_MODULES, 'set=xtcPrice') . '" class="menuBoxContentLink"> -' . BOX_MODULE_XTCPRICE . '</a></li>';
  $menu_access[] = '</ul></li>';
}
if ($admin_access['module_export'] == '1') $menu_access[] = '<li><a href="' . xtc_href_link(FILENAME_MODULE_EXPORT, 'set=export&module=sitemaporg', 'NONSSL') . '" class="menuBoxContentLink"> -' . BOX_GOOGLE_SITEMAP . '</a></li>';
if ($admin_access['module_export'] == '1') $menu_access[] = '<li><a href="' . xtc_href_link(FILENAME_MODULE_EXPORT, 'set=system', 'NONSSL') . '" class="menuBoxContentLink"> -' . BOX_MODULE_SYSTEM . '</a></li>';
if ($admin_access['module_export'] == '1') $menu_access[] = '<li><a href="' . xtc_href_link(FILENAME_MODULE_EXPORT, 'set=export', 'NONSSL') . '" class="menuBoxContentLink"> -' . BOX_MODULE_EXPORT . '</a></li>';
endMenue(BOX_HEADING_MODULES);

//partner
$menu_access = array();
$menu_access[] = mainMenue(BOX_HEADING_PARTNER_MODULES);
if (isset($admin_access['avalex']) && $admin_access['avalex'] == '1') $menu_access[] = '<li><a href="' . xtc_href_link(FILENAME_AVALEX, '') . '" class="menuBoxContentLink"> -' . BOX_AVALEX . '</a></li>';
if (isset($admin_access['cleverreach']) && $admin_access['cleverreach'] == '1') $menu_access[] = '<li><a href="' . xtc_href_link(FILENAME_CLEVERREACH, '') . '" class="menuBoxContentLink"> -' . BOX_CLEVERREACH . '</a></li>';
if (isset($admin_access['dhl']) && $admin_access['dhl'] == '1') $menu_access[] = '<li><a href="' . xtc_href_link(FILENAME_DHL, '') . '" class="menuBoxContentLink"> -' . BOX_DHL . '</a></li>';
if (isset($admin_access['haendlerbund']) && $admin_access['haendlerbund'] == '1') $menu_access[] = '<li><a href="' . xtc_href_link(FILENAME_HAENDLERBUND, '') . '" class="menuBoxContentLink"> -' . BOX_HAENDLERBUND . '</a></li>';
if (isset($admin_access['it_recht_kanzlei']) && $admin_access['it_recht_kanzlei'] == '1') $menu_access[] = '<li><a href="' . xtc_href_link(FILENAME_IT_RECHT_KANZLEI, '') . '" class="menuBoxContentLink"> -' . BOX_IT_RECHT_KANZLEI . '</a></li>';
if (isset($admin_access['janolaw']) && $admin_access['janolaw'] == '1') $menu_access[] = '<li><a href="' . xtc_href_link(FILENAME_JANOLAW, '', 'NONSSL') . '" class="menuBoxContentLink"> -' . BOX_JANOLAW . '</a></li>';

## Magnalister
if(defined('MODULE_MAGNALISTER_STATUS') && MODULE_MAGNALISTER_STATUS=='True') {
  if (isset($admin_access['magnalister']) && $admin_access['magnalister'] == '1') $menu_access[] = '<li><a href="' . xtc_href_link(FILENAME_MAGNALISTER."", '', 'NONSSL') . '" class="menuBoxContentLink"> -'.BOX_MAGNALISTER.'</a></li>';
} else {
  if ($admin_access['modules'] == '1') $menu_access[] = '<li><a href="' . xtc_href_link(FILENAME_MODULE_EXPORT, 'set=system&module=magnalister', 'NONSSL') . '" class="menuBoxContentLink"> -' . BOX_MAGNALISTER . '</a></li>';
}

## Payone
include(DIR_FS_EXTERNAL.'payone/modules/column_left.php');

## PayPal
include(DIR_FS_EXTERNAL.'paypal/modules/column_left.php');

if (isset($admin_access['protectedshops']) && $admin_access['protectedshops'] == '1') $menu_access[] = '<li><a href="' . xtc_href_link(FILENAME_PROTECTEDSHOPS, '') . '" class="menuBoxContentLink"> -' . BOX_PROTECTEDSHOPS . '</a></li>';
if (isset($admin_access['semknox']) && $admin_access['semknox'] == '1') $menu_access[] = '<li><a href="' . xtc_href_link(FILENAME_SEMKNOX, '') . '" class="menuBoxContentLink"> -' . BOX_SEMKNOX . '</a></li>';

## shipcloud
include(DIR_FS_EXTERNAL.'shipcloud/column_left.php');

if (isset($admin_access['supermailer']) && $admin_access['supermailer'] == '1') $menu_access[] = '<li><a href="' . xtc_href_link(FILENAME_SUPERMAILER, '') . '" class="menuBoxContentLink"> -' . BOX_SUPERMAILER . '</a></li>';
if (isset($admin_access['trustedshops']) && $admin_access['trustedshops'] == '1') $menu_access[] = '<li><a href="' . xtc_href_link(FILENAME_TRUSTEDSHOPS, '') . '" class="menuBoxContentLink"> -' . BOX_TRUSTEDSHOPS . '</a></li>';
endMenue(BOX_HEADING_PARTNER_MODULES);

//stats
$menu_access = array();
$menu_access[] = mainMenue(BOX_HEADING_STATISTICS);
if ($admin_access['stats_products_viewed'] == '1') $menu_access[] = '<li><a href="' . xtc_href_link(FILENAME_STATS_PRODUCTS_VIEWED, '', 'NONSSL') . '" class="menuBoxContentLink"> -' . BOX_PRODUCTS_VIEWED . '</a></li>';
if ($admin_access['stats_products_purchased'] == '1') $menu_access[] = '<li><a href="' . xtc_href_link(FILENAME_STATS_PRODUCTS_PURCHASED, '', 'NONSSL') . '" class="menuBoxContentLink"> -' . BOX_PRODUCTS_PURCHASED . '</a></li>';
if ($admin_access['stats_customers'] == '1') $menu_access[] = '<li><a href="' . xtc_href_link(FILENAME_STATS_CUSTOMERS, '', 'NONSSL') . '" class="menuBoxContentLink"> -' . BOX_STATS_CUSTOMERS . '</a></li>';
if ($admin_access['stats_sales_report'] == '1') $menu_access[] = '<li><a href="' . xtc_href_link(FILENAME_SALES_REPORT, '', 'NONSSL') . '" class="menuBoxContentLink"> -' . BOX_SALES_REPORT . '</a></li>';
if ($admin_access['stats_campaigns'] == '1') $menu_access[] = '<li><a href="' . xtc_href_link(FILENAME_CAMPAIGNS_REPORT, '', 'NONSSL') . '" class="menuBoxContentLink"> -' . BOX_CAMPAIGNS_REPORT . '</a></li>';
endMenue(BOX_HEADING_STATISTICS);

//tools
$menu_access = array();
$menu_access[] = mainMenue(BOX_HEADING_TOOLS);
if (defined('MODULE_NEWSLETTER_STATUS') && MODULE_NEWSLETTER_STATUS == 'true') {
  if ($admin_access['newsletter_recipients'] == '1') $menu_access[] = '<li><a href="' . xtc_href_link(FILENAME_NEWSLETTER_RECIPIENTS) . '" class="menuBoxContentLink"> -' . BOX_NEWSLETTER_RECIPIENTS . '</a></li>';
  if ($admin_access['module_newsletter'] == '1') $menu_access[] = '<li><a href="' . xtc_href_link(FILENAME_MODULE_NEWSLETTER) . '" class="menuBoxContentLink"> -' . BOX_MODULE_NEWSLETTER . '</a></li>';
}
if ($admin_access['content_manager'] == '1') {
  $menu_access[] = '<li><a href="javascript:void(0)" class="menuBoxContentLinkSub"> -' . BOX_CONTENT . '</a><ul>';
  $menu_access[] = '  <li><a href="' . xtc_href_link(FILENAME_CONTENT_MANAGER) . '" class="menuBoxContentLink"> -' . BOX_PAGES_CONTENT . '</a></li>';
  $menu_access[] = '  <li><a href="' . xtc_href_link(FILENAME_CONTENT_MANAGER, 'set=content') . '" class="menuBoxContentLink"> -' . BOX_CONTENT_CONTENT . '</a></li>';
  $menu_access[] = '</ul></li>';
}
if ($admin_access['removeoldpics'] == '1') $menu_access[] = '<li><a href="' . xtc_href_link(FILENAME_REMOVEOLDPICS, '', 'NONSSL') . '" class="menuBoxContentLink"> -' . BOX_REMOVEOLDPICS . '</a></li>';
if ($admin_access['backup'] == '1') $menu_access[] = '<li><a href="' . xtc_href_link(FILENAME_BACKUP) . '" class="menuBoxContentLink"> -' . BOX_BACKUP . '</a></li>';
if (defined('MODULE_BANNER_MANAGER_STATUS') && MODULE_BANNER_MANAGER_STATUS == 'true') {
  if ($admin_access['banner_manager'] == '1') $menu_access[] = '<li><a href="' . xtc_href_link(FILENAME_BANNER_MANAGER) . '" class="menuBoxContentLink"> -' . BOX_BANNER_MANAGER . '</a></li>';
}
if ($admin_access['server_info'] == '1') $menu_access[] = '<li><a href="' . xtc_href_link(FILENAME_SERVER_INFO) . '" class="menuBoxContentLink"> -' . BOX_SERVER_INFO . '</a></li>';
if ($admin_access['whos_online'] == '1') $menu_access[] = '<li><a href="' . xtc_href_link(FILENAME_WHOS_ONLINE) . '" class="menuBoxContentLink"> -' . BOX_WHOS_ONLINE . '</a></li>';
if ($admin_access['csv_backend'] == '1') $menu_access[] = '<li><a href="' . xtc_href_link(FILENAME_CSV_BACKEND) . '" class="menuBoxContentLink"> -' . BOX_IMPORT . '</a></li>';
if ($admin_access['parcel_carriers'] == '1') $menu_access[] = '<li><a href="' . xtc_href_link(FILENAME_PARCEL_CARRIERS) . '" class="menuBoxContentLink"> -' . BOX_PARCEL_CARRIERS . '</a></li>';
if ($admin_access['logs'] == '1') $menu_access[] = '<li><a href="' . xtc_href_link(FILENAME_LOGS) . '" class="menuBoxContentLink"> -' . BOX_LOGS . '</a></li>';
if ($admin_access['blacklist_logs'] == '1') $menu_access[] = '<li><a href="' . xtc_href_link(FILENAME_BLACKLIST_LOGS) . '" class="menuBoxContentLink"> -' . BOX_BLACKLIST_LOGS . '</a></li>';
if ($admin_access['scheduled_tasks'] == '1') $menu_access[] = '<li><a href="' . xtc_href_link(FILENAME_SCHEDULED_TASKS, '', 'NONSSL') . '" class="menuBoxContentLink"> -' . BOX_SCHEDULED_TASKS . '</a></li>';
endMenue(BOX_HEADING_TOOLS);

//gift
if (ACTIVATE_GIFT_SYSTEM=='true') {
  $menu_access = array();
  $menu_access[] = mainMenue(BOX_HEADING_GV_ADMIN);
  if ($admin_access['coupon_admin'] == '1') $menu_access[] = '<li><a href="' . xtc_href_link(FILENAME_COUPON_ADMIN, '', 'NONSSL') . '" class="menuBoxContentLink"> -' . BOX_COUPON_ADMIN . '</a></li>';
  if ($admin_access['gv_queue'] == '1') $menu_access[] = '<li><a href="' . xtc_href_link(FILENAME_GV_QUEUE, '', 'NONSSL') . '" class="menuBoxContentLink"> -' . BOX_GV_ADMIN_QUEUE . '</a></li>';
  if ($admin_access['gv_mail'] == '1') $menu_access[] = '<li><a href="' . xtc_href_link(FILENAME_GV_MAIL, '', 'NONSSL') . '" class="menuBoxContentLink"> -' . BOX_GV_ADMIN_MAIL . '</a></li>';
  if ($admin_access['gv_sent'] == '1') $menu_access[] = '<li><a href="' . xtc_href_link(FILENAME_GV_SENT, '', 'NONSSL') . '" class="menuBoxContentLink"> -' . BOX_GV_ADMIN_SENT . '</a></li>';
  if ($admin_access['gv_customers'] == '1') $menu_access[] = '<li><a href="' . xtc_href_link(FILENAME_GV_CUSTOMERS, '', 'NONSSL') . '" class="menuBoxContentLink"> -' . BOX_GV_CUSTOMERS . '</a></li>';
  endMenue(BOX_HEADING_GV_ADMIN); 
}

//countries
$menu_access = array();
$menu_access[] = mainMenue(BOX_HEADING_ZONE);
if ($admin_access['languages'] == '1') $menu_access[] = '<li><a href="' . xtc_href_link(FILENAME_LANGUAGES, '', 'NONSSL') . '" class="menuBoxContentLink"> -' . BOX_LANGUAGES . '</a></li>';
if ($admin_access['countries'] == '1') $menu_access[] = '<li><a href="' . xtc_href_link(FILENAME_COUNTRIES, '', 'NONSSL') . '" class="menuBoxContentLink"> -' . BOX_COUNTRIES . '</a></li>';
if ($admin_access['currencies'] == '1') $menu_access[] = '<li><a href="' . xtc_href_link(FILENAME_CURRENCIES, '', 'NONSSL') . '" class="menuBoxContentLink"> -' . BOX_CURRENCIES. '</a></li>';
if ($admin_access['zones'] == '1') $menu_access[] = '<li><a href="' . xtc_href_link(FILENAME_ZONES, '', 'NONSSL') . '" class="menuBoxContentLink"> -' . BOX_ZONES . '</a></li>';
if ($admin_access['geo_zones'] == '1') $menu_access[] = '<li><a href="' . xtc_href_link(FILENAME_GEO_ZONES, '', 'NONSSL') . '" class="menuBoxContentLink"> -' . BOX_GEO_ZONES . '</a></li>';
if ($admin_access['tax_classes'] == '1') $menu_access[] = '<li><a href="' . xtc_href_link(FILENAME_TAX_CLASSES, '', 'NONSSL') . '" class="menuBoxContentLink"> -' . BOX_TAX_CLASSES . '</a></li>';
if ($admin_access['tax_rates'] == '1') $menu_access[] = '<li><a href="' . xtc_href_link(FILENAME_TAX_RATES, '', 'NONSSL') . '" class="menuBoxContentLink"> -' . BOX_TAX_RATES . '</a></li>';
endMenue(BOX_HEADING_ZONE);

//configuration
$menu_access = array();
$menu_access[] = mainMenue(BOX_HEADING_CONFIGURATION);
if ($admin_access['configuration'] == '1') {
  $menu_access[] = '<li><a href="' . xtc_href_link(FILENAME_CONFIGURATION, 'gID=1', 'NONSSL') . '" class="menuBoxContentLink"> -' . BOX_CONFIGURATION_1 . '</a></li>';
  $menu_access[] = '<li><a href="' . xtc_href_link(FILENAME_CONFIGURATION, 'gID=1000', 'NONSSL') . '" class="menuBoxContentLink"> -' . BOX_CONFIGURATION_1000 . '</a></li>';
  $menu_access[] = '<li><a href="' . xtc_href_link(FILENAME_CONFIGURATION, 'gID=2', 'NONSSL') . '" class="menuBoxContentLink"> -' . BOX_CONFIGURATION_2 . '</a></li>';
  $menu_access[] = '<li><a href="' . xtc_href_link(FILENAME_CONFIGURATION, 'gID=3', 'NONSSL') . '" class="menuBoxContentLink"> -' . BOX_CONFIGURATION_3 . '</a></li>';
  $menu_access[] = '<li><a href="' . xtc_href_link(FILENAME_CONFIGURATION, 'gID=4', 'NONSSL') . '" class="menuBoxContentLink"> -' . BOX_CONFIGURATION_4 . '</a></li>';
  $menu_access[] = '<li><a href="' . xtc_href_link(FILENAME_CONFIGURATION, 'gID=5', 'NONSSL') . '" class="menuBoxContentLink"> -' . BOX_CONFIGURATION_5 . '</a></li>';
  $menu_access[] = '<li><a href="' . xtc_href_link(FILENAME_CONFIGURATION, 'gID=7', 'NONSSL') . '" class="menuBoxContentLink"> -' . BOX_CONFIGURATION_7 . '</a></li>';
  $menu_access[] = '<li><a href="' . xtc_href_link(FILENAME_CONFIGURATION, 'gID=8', 'NONSSL') . '" class="menuBoxContentLink"> -' . BOX_CONFIGURATION_8 . '</a></li>';
  $menu_access[] = '<li><a href="' . xtc_href_link(FILENAME_CONFIGURATION, 'gID=9', 'NONSSL') . '" class="menuBoxContentLink"> -' . BOX_CONFIGURATION_9 . '</a></li>';
  $menu_access[] = '<li><a href="javascript:void(0)" class="menuBoxContentLinkSub"> -' . BOX_CONFIGURATION_12 . '</a><ul>';
  $menu_access[] = '  <li><a href="' . xtc_href_link(FILENAME_CONFIGURATION, 'gID=12', 'NONSSL') . '" class="menuBoxContentLink"> -' . BOX_CONFIGURATION_12 . '</a></li>';
  $menu_access[] = '  <li><a href="' . xtc_href_link(FILENAME_CONTENT_MANAGER, 'set=email') . '" class="menuBoxContentLink"> -' . BOX_EMAIL_CONTENT . '</a></li>';
  $menu_access[] = '</ul></li>';
  $menu_access[] = '<li><a href="' . xtc_href_link(FILENAME_CONFIGURATION, 'gID=13', 'NONSSL') . '" class="menuBoxContentLink"> -' . BOX_CONFIGURATION_13 . '</a></li>';
}
if ($admin_access['orders_status'] == '1') $menu_access[] = '<li><a href="' . xtc_href_link(FILENAME_ORDERS_STATUS, '', 'NONSSL') . '" class="menuBoxContentLink"> -' . BOX_ORDERS_STATUS . '</a></li>';
if (ACTIVATE_SHIPPING_STATUS=='true' && $admin_access['shipping_status'] == '1') $menu_access[] = '<li><a href="' . xtc_href_link(FILENAME_SHIPPING_STATUS, '', 'NONSSL') . '" class="menuBoxContentLink"> -' . BOX_SHIPPING_STATUS . '</a></li>';
if ($admin_access['products_vpe'] == '1') $menu_access[] = '<li><a href="' . xtc_href_link(FILENAME_PRODUCTS_VPE, '', 'NONSSL') . '" class="menuBoxContentLink"> -' . BOX_PRODUCTS_VPE . '</a></li>';
if ($admin_access['campaigns'] == '1') $menu_access[] = '<li><a href="' . xtc_href_link(FILENAME_CAMPAIGNS, '', 'NONSSL') . '" class="menuBoxContentLink"> -' . BOX_CAMPAIGNS . '</a></li>';
if ($admin_access['cross_sell_groups'] == '1') $menu_access[] = '<li><a href="' . xtc_href_link(FILENAME_XSELL_GROUPS, '', 'NONSSL') . '" class="menuBoxContentLink"> -' . BOX_ORDERS_XSELL_GROUP . '</a></li>';
if (isset($admin_access['cookie_consent']) && $admin_access['cookie_consent'] == '1') $menu_access[] = '<li><a href="' . xtc_href_link(FILENAME_COOKIE_CONSENT, '', 'NONSSL') . '" class="menuBoxContentLink"> -' . BOX_COOKIE_CONSENT . '</a></li>';
endMenue(BOX_HEADING_CONFIGURATION);

//configuration 2
$menu_access = array();
$menu_access[] = mainMenue(BOX_HEADING_CONFIGURATION2);
if ($admin_access['shop_offline'] == '1') $menu_access[] = '<li><a href="' . xtc_href_link('shop_offline.php', '', 'NONSSL') . '" class="menuBoxContentLink"> -'.'Shop online/offline'.'</a></li>';
if ($admin_access['configuration'] == '1') {
  $menu_access[] = '<li><a href="' . xtc_href_link(FILENAME_CONFIGURATION, 'gID=10', 'NONSSL') . '" class="menuBoxContentLink"> -' . BOX_CONFIGURATION_10 . '</a></li>';
  $menu_access[] = '<li><a href="' . xtc_href_link(FILENAME_CONFIGURATION, 'gID=11', 'NONSSL') . '" class="menuBoxContentLink"> -' . BOX_CONFIGURATION_11 . '</a></li>';
  $menu_access[] = '<li><a href="' . xtc_href_link(FILENAME_CONFIGURATION, 'gID=14', 'NONSSL') . '" class="menuBoxContentLink"> -' . BOX_CONFIGURATION_14 . '</a></li>';
  $menu_access[] = '<li><a href="' . xtc_href_link(FILENAME_CONFIGURATION, 'gID=15', 'NONSSL') . '" class="menuBoxContentLink"> -' . BOX_CONFIGURATION_15 . '</a></li>';
  $menu_access[] = '<li><a href="' . xtc_href_link(FILENAME_CONFIGURATION, 'gID=16', 'NONSSL') . '" class="menuBoxContentLink"> -' . BOX_CONFIGURATION_16 . '</a></li>';
  $menu_access[] = '<li><a href="' . xtc_href_link(FILENAME_CONFIGURATION, 'gID=17', 'NONSSL') . '" class="menuBoxContentLink"> -' . BOX_CONFIGURATION_17 . '</a></li>';
  $menu_access[] = '<li><a href="' . xtc_href_link(FILENAME_CONFIGURATION, 'gID=18', 'NONSSL') . '" class="menuBoxContentLink"> -' . BOX_CONFIGURATION_18 . '</a></li>';
  $menu_access[] = '<li><a href="' . xtc_href_link(FILENAME_CONFIGURATION, 'gID=19', 'NONSSL') . '" class="menuBoxContentLink"> -' . BOX_CONFIGURATION_19 . '</a></li>';
  $menu_access[] = '<li><a href="' . xtc_href_link(FILENAME_CONFIGURATION, 'gID=22', 'NONSSL') . '" class="menuBoxContentLink"> -' . BOX_CONFIGURATION_22 . '</a></li>';
  $menu_access[] = '<li><a href="' . xtc_href_link(FILENAME_CONFIGURATION, 'gID=40', 'NONSSL') . '" class="menuBoxContentLink"> -' . BOX_CONFIGURATION_40 . '</a></li>'; 
  if ($admin_access['module_export'] == '1') {
    $menu_access[] = '<li><a href="javascript:void(0)" class="menuBoxContentLinkSub"> -' . BOX_CONFIGURATION_24 . '</a><ul>';
    $menu_access[] = '  <li><a href="' . xtc_href_link(FILENAME_MODULE_EXPORT, 'set=system&module=google_analytics', 'NONSSL') . '" class="menuBoxContentLink"> -' . BOX_GOOGLE_ANALYTICS . '</a></li>';
    $menu_access[] = '  <li><a href="' . xtc_href_link(FILENAME_MODULE_EXPORT, 'set=system&module=matomo_analytics', 'NONSSL') . '" class="menuBoxContentLink"> -' . BOX_MATOMO_ANALYTICS . '</a></li>';
    $menu_access[] = '  <li><a href="' . xtc_href_link(FILENAME_MODULE_EXPORT, 'set=system&module=facebook_pixel', 'NONSSL') . '" class="menuBoxContentLink"> -' . BOX_FACEBOOK_PIXEL . '</a></li>';
    $menu_access[] = '</ul></li>';
  }
  $menu_access[] = '<li><a href="' . xtc_href_link(FILENAME_CONFIGURATION, 'gID=25', 'NONSSL') . '" class="menuBoxContentLink"> -' . BOX_CONFIGURATION_25 . '</a></li>';
}
endMenue(BOX_HEADING_CONFIGURATION2);

echo '</ul>'; 
echo '</div>';
