<?php
/* -----------------------------------------------------------------------------------------
   $Id: upcoming_products.php 15659 2023-12-30 09:52:02Z Markus $

   modified eCommerce Shopsoftware
   http://www.modified-shop.org

   Copyright (c) 2009 - 2016 [www.modified-shop.org]
   -----------------------------------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(upcoming_products.php,v 1.23 2003/02/12); www.oscommerce.com 
   (c) 2003	 nextcommerce (upcoming_products.php,v 1.7 2003/08/22); www.nextcommerce.org
   (c) 2003 XT-Commerce (upcoming_products.php r 1243 2005-09-25 ) www.xt-commerce.com

   Released under the GNU General Public License 
   ---------------------------------------------------------------------------------------*/

if (MAX_DISPLAY_UPCOMING_PRODUCTS != '0') {
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
    $cache_id = md5('lID:'.$_SESSION['language'].'|csID:'.$_SESSION['customers_status']['customers_status_id'].'|curr:'.$_SESSION['currency'].'|country:'.((isset($_SESSION['country'])) ? $_SESSION['country'] : ((isset($_SESSION['customer_country_id'])) ? $_SESSION['customer_country_id'] : STORE_COUNTRY)));
  }

  if (!$module_smarty->is_cached(CURRENT_TEMPLATE.'/module/upcoming_products.html', $cache_id) || !$cache) {
    $expected_query = xtDBquery("SELECT ".$product->default_select.",
                                        p.products_date_available,
                                        p.products_date_available as date_expected
                                   FROM ".TABLE_PRODUCTS." p
                                   JOIN ".TABLE_PRODUCTS_DESCRIPTION." pd
                                        ON p.products_id = pd.products_id
                                           AND pd.language_id = ".(int)$_SESSION['languages_id']."
                                           AND trim(pd.products_name) != ''
                                   JOIN ".TABLE_PRODUCTS_TO_CATEGORIES." p2c
                                        ON p.products_id = p2c.products_id
                                   JOIN ".TABLE_CATEGORIES." c
                                        ON c.categories_id = p2c.categories_id
                                           AND c.categories_status = 1
                                               ".CATEGORIES_CONDITIONS_C."
                                  WHERE p.products_status = 1
                                    AND to_days(products_date_available) >= to_days(now())
                                        ".PRODUCTS_CONDITIONS_P."
                               GROUP BY p.products_id
                               ORDER BY ".EXPECTED_PRODUCTS_FIELD." ".EXPECTED_PRODUCTS_SORT."
                                  LIMIT ".MAX_DISPLAY_UPCOMING_PRODUCTS);

    if (xtc_db_num_rows($expected_query, true) > 0) {
      $row = 0;
      $module_content = array ();
      while ($expected = xtc_db_fetch_array($expected_query, true)) {
        $module_content[$row] = $product->buildDataArray($expected);
        $module_content[$row]['PRODUCTS_DATE'] = xtc_date_short($expected['date_expected']);
        $row ++;
      }
      $module_smarty->assign('STARTPAGE', 'true');
      $module_smarty->assign('module_content', $module_content);
    }
  }
  
  $module = $module_smarty->fetch(CURRENT_TEMPLATE.'/module/upcoming_products.html', $cache_id);
  $default_smarty->assign('MODULE_upcoming_products', $module);
  $smarty->assign('MODULE_upcoming_products', $module);
}
