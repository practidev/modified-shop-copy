<?php
/* -----------------------------------------------------------------------------------------
   $Id: default.php 15558 2023-11-10 18:03:57Z Markus $

   modified eCommerce Shopsoftware
   http://www.modified-shop.org

   Copyright (c) 2009 - 2013 [www.modified-shop.org]
  -----------------------------------------------------------------------------------------
  based on:
  (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
  (c) 2002-2003 osCommerce(default.php,v 1.84 2003/05/07); www.oscommerce.com
  (c) 2003 nextcommerce (default.php,v 1.11 2003/08/22); www.nextcommerce.org
  (c) 2006 xt:Commerce (cross_selling.php 1243 2005-09-25); www.xt-commerce.de

  Released under the GNU General Public License
  -----------------------------------------------------------------------------------------
  Third Party contributions:
  Enable_Disable_Categories 1.3        Autor: Mikel Williams | mikel@ladykatcostumes.com
  Customers Status v3.x  (c) 2002-2003 Copyright Elari elari@free.fr | www.unlockgsm.com/dload-osc/
  | CVS : http://cvs.sourceforge.net/cgi-bin/viewcvs...by=date#dirlist

  Released under the GNU General Public License
  ---------------------------------------------------------------------------------------*/

// todo: move to configuration ?
defined('CATEGORIES_IMAGE_SHOW_NO_IMAGE') OR define('CATEGORIES_IMAGE_SHOW_NO_IMAGE', 'true');
defined('CATEGORIES_SHOW_PRODUCTS_SUBCATS') OR define('CATEGORIES_SHOW_PRODUCTS_SUBCATS', 'false');

// include needed functions
require_once (DIR_FS_INC.'xtc_get_path.inc.php');
require_once (DIR_FS_INC.'xtc_check_categories_status.inc.php');
require_once (DIR_FS_INC.'xtc_get_subcategories.inc.php');
require_once (DIR_FS_INC.'xtc_parse_search_string.inc.php');
require_once (DIR_FS_INC.'xtc_get_currencies_values.inc.php');
require_once (DIR_FS_INC.'check_whatsnew.inc.php');
require_once (DIR_FS_INC.'get_filter_tags.inc.php');

$default_smarty = new Smarty();
$default_smarty->assign('language', $_SESSION['language']);
$default_smarty->assign('tpl_path', DIR_WS_BASE.'templates/'.CURRENT_TEMPLATE.'/');
$default_smarty->assign('session', xtc_session_id());

// define defaults
$main_content = '';

$category_depth = 'top';
if (isset ($cPath) && xtc_not_null($cPath)) {

  // check categorie exist
  if (xtc_check_categories_status($current_category_id) === false) {
    $site_error = TEXT_CATEGORIE_NOT_FOUND;
    include (DIR_WS_MODULES.FILENAME_ERROR_HANDLER);

    // build breadcrumb
    $breadcrumb->add(NAVBAR_TITLE_ERROR, xtc_href_link(FILENAME_ERROR));

    return;
  }
  
  $subcategories_array = array();
  if (CATEGORIES_SHOW_PRODUCTS_SUBCATS == 'true') {
    xtc_get_subcategories($subcategories_array, $current_category_id);
  }
  $subcategories_array[] = (int)$current_category_id;
    
  $categories_products_query = "SELECT p2c.products_id
                                  FROM ".TABLE_PRODUCTS_TO_CATEGORIES." p2c
                                  JOIN ".TABLE_PRODUCTS." p
                                       ON p2c.products_id = p.products_id
                                          AND p2c.categories_id ".((count($subcategories_array) > 1) ? "IN (".implode(', ', $subcategories_array).") " : "= '".(int)$current_category_id."' ")."
                                 WHERE p.products_status = '1'
                                       ".PRODUCTS_CONDITIONS_P."
                                 LIMIT 1";
  $categories_products_result = xtDBquery($categories_products_query);
  if (xtc_db_num_rows($categories_products_result, true) > 0) {
    $category_depth = 'products'; // display products
  } else {
    $category_parent_query = "SELECT parent_id 
                                FROM ".TABLE_CATEGORIES." 
                               WHERE parent_id = ".(int)$current_category_id." 
                                 AND categories_status = '1'
                                     ".CATEGORIES_CONDITIONS;
    $category_parent_result = xtDBquery($category_parent_query);
    if (xtc_db_num_rows($category_parent_result, true) > 0) {
      $category_depth = 'nested'; // navigate through the categories
    } else {
      $category_depth = 'products'; // category has no products, but display the 'no products' message
    }
  }
} elseif (isset($_GET['manufacturers_id']) && (int)$_GET['manufacturers_id'] > 0) {
  $category_depth = 'products';
}

if (isset($language_not_found) && $language_not_found === true) {
  if ($category_depth != 'top') {
    $site_error = TEXT_CATEGORIE_NOT_FOUND;
    include (DIR_WS_MODULES.FILENAME_ERROR_HANDLER);

    // build breadcrumb
    $breadcrumb->add(NAVBAR_TITLE_ERROR, xtc_href_link(FILENAME_ERROR));

    return;
  } else {
    header("HTTP/1.0 410 Gone"); 
    header("Status: 410 Gone");
  }
}

$category_depth_array = array(
  FILENAME_ADVANCED_SEARCH_RESULT,
  FILENAME_PRODUCTS_NEW,
  FILENAME_SPECIALS,
);
if (in_array(basename($PHP_SELF), $category_depth_array)
    || (isset($_GET['manufacturers_id']) && (int)$_GET['manufacturers_id'] > 0)
    )
{
  $category_depth = 'products';
}
foreach(auto_include(DIR_FS_CATALOG.'includes/extra/default/category_depth/','php') as $file) require ($file);

switch ($category_depth) {
  case 'nested':
    $category = xtc_get_category_data($current_category_id);

    //include Categorie Listing
    include (DIR_WS_MODULES. 'categories_listing.php');

    $new_products_category_id = $current_category_id;
    include (DIR_WS_MODULES.FILENAME_NEW_PRODUCTS);

    $image = $main->getImage($category['categories_image']);
    $image_list = $main->getImage($category['categories_image_list']);
    $image_mobile = $main->getImage($category['categories_image_mobile']);

    // get default template
    if ($category['categories_template'] == '' 
        || $category['categories_template'] == 'default'
        || !is_file(DIR_FS_CATALOG.'templates/'.CURRENT_TEMPLATE.'/module/categorie_listing/'.$category['categories_template'])
        )
    {
      $files = array_filter(auto_include(DIR_FS_CATALOG.'templates/'.CURRENT_TEMPLATE.'/module/categorie_listing/','html'), function($file) {
        return false === strpos($file, 'index.html');
      });
      $category['categories_template'] = basename($files[0]);
    }

    $default_smarty->assign('CATEGORIES_NAME', $category['categories_name']);
    $default_smarty->assign('CATEGORIES_HEADING_TITLE', $category['categories_heading_title']);
    $default_smarty->assign('CATEGORIES_IMAGE', (($image != '') ? DIR_WS_BASE . $image : ''));
    $default_smarty->assign('CATEGORIES_IMAGE_LIST', (($image_list != '') ? DIR_WS_BASE . $image_list : ''));
    $default_smarty->assign('CATEGORIES_IMAGE_MOBILE', (($image_mobile != '') ? DIR_WS_BASE . $image_mobile : ''));
    $default_smarty->assign('CATEGORIES_DESCRIPTION', $category['categories_description']);

    if ($messageStack->size('categorie_listing') > 0) {
      $default_smarty->assign('error_message', $messageStack->output('categorie_listing'));
    }
  
    foreach(auto_include(DIR_FS_CATALOG.'includes/extra/default/categories_smarty/','php') as $file) require_once ($file);

    $default_smarty->caching = 0;
    $main_content = $default_smarty->fetch(CURRENT_TEMPLATE.'/module/categorie_listing/'.$category['categories_template']);
    $smarty->assign('main_content', $main_content);
    $display_mode = 'category';
    break;

  case 'products':
    $select = '';
    $from = '';
    $filter_where = '';
    $where = '';
    $p2c_condition = '';
    $use_group_by = (isset($subcategories_array) && count($subcategories_array) > 1);
    $display_mode = 'listing';
    
    // sorting query
    if (isset($_GET['manufacturers_id']) && isset($_GET['filter_id'])) {
      $category_id = (int)$_GET['filter_id'];
    } else {
      $category_id = (int)$current_category_id;
    }

    $sorting_data = array(
      'products_sorting' => '',
      'products_sorting2' => '',
    );
    
    if (isset($category_id) && $category_id > 0) {
      $sorting_query = xtDBquery("SELECT products_sorting,
                                         products_sorting2
                                    FROM ".TABLE_CATEGORIES."
                                   WHERE categories_id = '".$category_id."'");
      if (xtc_db_num_rows($sorting_query, true) > 0) {
        $sorting_data = xtc_db_fetch_array($sorting_query, true);
      }
    }

    if (isset($_GET['manufacturers_id']) && (int)$_GET['manufacturers_id'] > 0) {
      $sorting_query = xtDBquery("SELECT products_sorting,
                                         products_sorting2
                                    FROM ".TABLE_MANUFACTURERS."
                                   WHERE manufacturers_id = '".(int)$_GET['manufacturers_id']."'");
      if (xtc_db_num_rows($sorting_query, true) > 0) {
        $sorting_data = xtc_db_fetch_array($sorting_query, true);
      }
    }
  
    //Fallback for products_sorting to products_name
    if (empty($sorting_data['products_sorting'])) { 
      $sorting_data['products_sorting'] = 'pd.products_name';
    }
  
    //Fallback for products_sorting2 to ascending
    if (empty($sorting_data['products_sorting2'])) { 
      $sorting_data['products_sorting2'] = 'ASC';
    }
    $sorting = ' ORDER BY '.$sorting_data['products_sorting'].' '.$sorting_data['products_sorting2'].' ';

    if (basename($PHP_SELF) == FILENAME_SPECIALS) {
      $display_mode = 'specials';
      $select .= "s.expires_date,
                  s.specials_new_products_price,
                  s.specials_new_products_price AS price, ";
      $from   .= "JOIN ".TABLE_SPECIALS." s
                    ON p.products_id = s.products_id 
                       ".SPECIALS_CONDITIONS_S." ";
      $sorting = ' ORDER BY '.SPECIALS_FIELD.' '.SPECIALS_SORT.' ';
      $use_group_by = true;
    } else {
      $select .= "IFNULL(s.specials_new_products_price, p.products_price) AS price, ";
      $from   .= "LEFT JOIN ".TABLE_SPECIALS." s
                         ON p.products_id = s.products_id 
                            ".SPECIALS_CONDITIONS_S." ";
    }
  
    if (basename($PHP_SELF) == FILENAME_PRODUCTS_NEW) {
      $display_mode = 'productsnew';
      if (MAX_DISPLAY_NEW_PRODUCTS_DAYS != '0') {
        $date_new_products = date("Y-m-d", mktime(1, 1, 1, date("m"), date("d") - MAX_DISPLAY_NEW_PRODUCTS_DAYS, date("Y")));
        $where .= " AND p.products_date_added > '".$date_new_products."' ";
        $daysfound = true;
      }
      $sorting = ' ORDER BY '.PRODUCTS_NEW_FIELD.' '.PRODUCTS_NEW_SORT.' ';
      $use_group_by = true;
    }

    if (isset($_GET['manufacturers_id'])) {
      if (basename($PHP_SELF) == FILENAME_DEFAULT) {
        $display_mode = 'manufacturer';
        
        // show the products of a specified manufacturer
        $select .= "m.manufacturers_name, ";
        $from   .= "JOIN ".TABLE_MANUFACTURERS." m 
                         ON p.manufacturers_id = m.manufacturers_id
                            AND m.manufacturers_status = 1
                            AND m.manufacturers_id = '".(int) $_GET['manufacturers_id']."' ";
        $use_group_by = true;
      }
      
      // We are asked to show only a specific category
      if (isset($_GET['filter_id']) && xtc_not_null($_GET['filter_id'])) {
        $p2c_condition = " AND p2c.categories_id = '".(int)$_GET['filter_id']."'  ";
      }
    } else {
      if (basename($PHP_SELF) == FILENAME_DEFAULT && count($subcategories_array) > 0) {
        // show the products in a given categorie
        $p2c_condition = " AND p2c.categories_id IN (".implode(', ', $subcategories_array).") ";
        $use_group_by = true;
        if (count($subcategories_array) == 1) {
          $p2c_condition = " AND p2c.categories_id = '".$subcategories_array[0]."' ";
          $use_group_by = false;
        }
      }
      
      // We are asked to show only specific manufacturer                    
      if (isset($_GET['filter_id']) && xtc_not_null($_GET['filter_id'])) {
        $select .= "m.manufacturers_name, ";
        $from .= "JOIN ".TABLE_MANUFACTURERS." m 
                       ON p.manufacturers_id = m.manufacturers_id
                          AND m.manufacturers_status = 1
                          AND m.manufacturers_id = '".(int)$_GET['filter_id']."' ";
      }
    }
  
    // filter
    $tags_array = get_filter_tags();
    if (count($tags_array) > 0) {
      $filter_where .= "AND p.products_id IN (".implode(', ', $tags_array).")";
    }
    
    $listing_sql = "SELECT ".$select."
                           ".ADD_SELECT_DEFAULT."
                           p.products_id,
                           p.products_ean,
                           p.products_quantity,
                           p.products_shippingtime,
                           p.products_model,
                           p.products_image,
                           p.products_price,
                           p.products_discount_allowed,
                           p.products_weight,
                           p.products_tax_class_id,
                           p.manufacturers_id,
                           p.products_fsk18,
                           p.products_vpe,
                           p.products_vpe_status,
                           p.products_vpe_value,
                           p.products_date_added,
                           pd.products_name,
                           pd.products_heading_title,
                           pd.products_description,
                           pd.products_short_description
                      FROM ".TABLE_PRODUCTS." p
                      JOIN ".TABLE_PRODUCTS_DESCRIPTION." pd
                           ON p.products_id = pd.products_id 
                              AND pd.language_id = '".(int) $_SESSION['languages_id']."'
                              AND trim(pd.products_name) != '' 
                      JOIN ".TABLE_PRODUCTS_TO_CATEGORIES." p2c 
                           ON p2c.products_id = pd.products_id
                              ".$p2c_condition."
                      JOIN ".TABLE_CATEGORIES." c
                           ON c.categories_id = p2c.categories_id
                              AND c.categories_status = 1
                                  ".CATEGORIES_CONDITIONS_C."
                           ".$from."
                     WHERE p.products_status = '1'
                           ".PRODUCTS_CONDITIONS_P."
                           ".$where."
                           ".$filter_where."
                           ".(($use_group_by === true) ? 'GROUP BY p.products_id' : '')."
                           ".((isset($_SESSION['filter_sorting'])) ? $_SESSION['filter_sorting'] : $sorting);
    
    foreach(auto_include(DIR_FS_CATALOG.'includes/extra/default/listing_sql/','php') as $file) require ($file);

    include (DIR_WS_MODULES.FILENAME_PRODUCT_LISTING);
    break;
    
  case 'top':
    $display_mode = 'home';
    $content_main_template = 'main_content.html';
    $shop_content_data = $main->getContentData(5, '', '', false, ADD_SELECT_CONTENT);
    if (!empty($shop_content_data['content_heading'])) {
      $default_smarty->assign('title', $shop_content_data['content_heading']);
    }
    
    foreach(auto_include(DIR_FS_CATALOG.'includes/extra/default/center_modules/','php') as $file) require_once ($file);

    if ($messageStack->size('default') > 0) {
      $error_message = $messageStack->output('default');
      $default_smarty->assign('error_message', $error_message);
      $smarty->assign('error_message', $error_message);
    }
    
    $default_smarty->caching = 0;
    $main_content = $default_smarty->fetch(CURRENT_TEMPLATE.'/module/'.$content_main_template);
    $smarty->assign('main_content', $main_content);
    break;
}
