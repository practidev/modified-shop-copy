<?php
/* -----------------------------------------------------------------------------------------
   $Id: new_products.php 15236 2023-06-14 06:51:22Z GTB $

   modified eCommerce Shopsoftware
   http://www.modified-shop.org

   Copyright (c) 2009 - 2016 [www.modified-shop.org]
   -----------------------------------------------------------------------------------------
   based on:
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(new_products.php,v 1.33 2003/02/12); www.oscommerce.com
   (c) 2003   nextcommerce (new_products.php,v 1.9 2003/08/17); www.nextcommerce.org
   (c) 2006 xt:Commerce (new_products.php 1292 2005-10-07); www.xt-commerce.de

   Released under the GNU General Public License
   -----------------------------------------------------------------------------------------
   Third Party contributions:
   Enable_Disable_Categories 1.3 - Author: Mikel Williams | mikel@ladykatcostumes.com

   Released under the GNU General Public License
   ---------------------------------------------------------------------------------------*/

// include needed functions
require_once (DIR_FS_INC.'get_pictureset_data.inc.php');

$module_smarty = new Smarty();
$module_smarty->assign('language', $_SESSION['language']);
$module_smarty->assign('tpl_path', DIR_WS_BASE.'templates/'.CURRENT_TEMPLATE.'/');

// set cache ID
if (!CacheCheck()) {
  $cache = false;
  $module_smarty->caching = 0;
  $cache_id = null;
} else {
  $cache = true;
  $module_smarty->caching = 1;
  $module_smarty->cache_lifetime = CACHE_LIFETIME;
  $module_smarty->cache_modified_check = CACHE_CHECK == 'true';
  $cache_id = md5('lID:'.$_SESSION['language'].'|csID:'.$_SESSION['customers_status']['customers_status_id'].'|pID:'.$new_products_category_id.'|curr:'.$_SESSION['currency'].'|country:'.((isset($_SESSION['country'])) ? $_SESSION['country'] : ((isset($_SESSION['customer_country_id'])) ? $_SESSION['customer_country_id'] : STORE_COUNTRY)));
}

if (!$module_smarty->is_cached(CURRENT_TEMPLATE.'/module/new_products.html', $cache_id) || !$cache) {
  $days = '';
  if (MAX_DISPLAY_NEW_PRODUCTS_DAYS != '0') {
    $date_new_products = date("Y-m-d", mktime(1, 1, 1, date("m"), date("d") - MAX_DISPLAY_NEW_PRODUCTS_DAYS, date("Y")));
    $days = " AND p.products_date_added > '".$date_new_products."' ";
  }
  $new_products_query = "SELECT ".$product->default_select.",
                                m.manufacturers_name
                           FROM ".TABLE_PRODUCTS." p
                           JOIN ".TABLE_PRODUCTS_DESCRIPTION." pd
                                ON p.products_id = pd.products_id
                                   AND pd.products_name <> ''
                                   AND pd.language_id = '".(int) $_SESSION['languages_id']."'
                           JOIN ".TABLE_PRODUCTS_TO_CATEGORIES." p2c
                                ON p.products_id = p2c.products_id
                           JOIN ".TABLE_CATEGORIES." c
                                ON p2c.categories_id = c.categories_id
                                   AND c.categories_status = 1
                                   AND c.parent_id = '".(int)$new_products_category_id."'
                                       ".CATEGORIES_CONDITIONS_C."
                      LEFT JOIN ".TABLE_MANUFACTURERS." m
                                ON p.manufacturers_id = m.manufacturers_id
                          WHERE p.products_status = 1
                                ".PRODUCTS_CONDITIONS_P."
                                ".$days."
                       GROUP BY p.products_id
                       ORDER BY p.products_date_added DESC
                          LIMIT ".MAX_DISPLAY_NEW_PRODUCTS;

  $module_content = array();
  $new_products_query = xtDBquery($new_products_query);
  while ($new_products = xtc_db_fetch_array($new_products_query, true)) {
    $module_content[] = $product->buildDataArray($new_products);
  }

  if (count($module_content) > 0) {
    $module_smarty->assign('module_content', $module_content);

    if (defined('PICTURESET_BOX')) {
      $module_smarty->assign('pictureset_box', get_pictureset_data(PICTURESET_BOX));
    }
    if (defined('PICTURESET_ROW')) {
      $module_smarty->assign('pictureset_row', get_pictureset_data(PICTURESET_ROW));
    }
  }
}

$module = $module_smarty->fetch(CURRENT_TEMPLATE.'/module/new_products.html', $cache_id);
$default_smarty->assign('MODULE_new_products', !empty($module) ? trim($module) : $module);
