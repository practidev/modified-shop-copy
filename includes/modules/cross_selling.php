<?php
/* -----------------------------------------------------------------------------------------
   $Id: cross_selling.php 15236 2023-06-14 06:51:22Z GTB $ 

   XT-Commerce - community made shopping
   http://www.xt-commerce.com

   Copyright (c) 2005 XT-Commerce

   Released under the GNU General Public License
   -----------------------------------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(also_purchased_products.php,v 1.21 2003/02/12); www.oscommerce.com 
   (c) 2003	 nextcommerce (also_purchased_products.php,v 1.9 2003/08/17); www.nextcommerce.org 
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
  $cache_id = md5('lID:'.$_SESSION['language'].'|csID:'.$_SESSION['customers_status']['customers_status_id'].'|pID:'.$product->data['products_id'].'|curr:'.$_SESSION['currency'].'|country:'.((isset($_SESSION['country'])) ? $_SESSION['country'] : ((isset($_SESSION['customer_country_id'])) ? $_SESSION['customer_country_id'] : STORE_COUNTRY)));
}

if (!$module_smarty->is_cached(CURRENT_TEMPLATE.'/module/cross_selling.html', $cache_id) || !$cache) {
  if (ACTIVATE_CROSS_SELLING == 'true') {
    $data = $product->getCrossSells();
    if (count($data) > 0) {
      $module_smarty->assign('module_content', $data);

      if (defined('PICTURESET_BOX')) {
        $module_smarty->assign('pictureset_box', get_pictureset_data(PICTURESET_BOX));
      }
      if (defined('PICTURESET_ROW')) {
        $module_smarty->assign('pictureset_row', get_pictureset_data(PICTURESET_ROW));
      }
    }
  }
}

$module = $module_smarty->fetch(CURRENT_TEMPLATE.'/module/cross_selling.html', $cache_id);
$info_smarty->assign('MODULE_cross_selling', !empty($module) ? trim($module) : $module);

$module_smarty->clear_assign('module_content');
if (!$module_smarty->is_cached(CURRENT_TEMPLATE.'/module/reverse_cross_selling.html', $cache_id) || !$cache) {
  if (ACTIVATE_REVERSE_CROSS_SELLING == 'true') {
    $data = $product->getReverseCrossSells();	
    if (count($data) > 0) {
      $module_smarty->assign('module_content', $data);
    
      if (defined('PICTURESET_BOX')) {
        $module_smarty->assign('pictureset_box', get_pictureset_data(PICTURESET_BOX));
      }
      if (defined('PICTURESET_ROW')) {
        $module_smarty->assign('pictureset_row', get_pictureset_data(PICTURESET_ROW));
      }
    }
  }
}

$module = $module_smarty->fetch(CURRENT_TEMPLATE.'/module/reverse_cross_selling.html', $cache_id);
$info_smarty->assign('MODULE_reverse_cross_selling', !empty($module) ? trim($module) : $module);
