<?php
/* -----------------------------------------------------------------------------------------
   $Id: check_whatsnew.inc.php 14483 2022-05-24 15:00:42Z GTB $

   modified eCommerce Shopsoftware
   http://www.modified-shop.org

   Copyright (c) 2009 - 2013 [www.modified-shop.org]
   -----------------------------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------------------------*/

function check_whatsnew() {
  static $whatsnew_check;
  
  if (!isset($whatsnew_check)) {
    $whatsnew_check = false;

    $days = '';
    if (MAX_DISPLAY_NEW_PRODUCTS_DAYS != '0') {
      $date_new_products = date("Y-m-d", mktime(1, 1, 1, date("m"), date("d") - MAX_DISPLAY_NEW_PRODUCTS_DAYS, date("Y")));
      $days = " AND p.products_date_added > '".$date_new_products."' ";
    }
    $products_new_query = xtDBquery("SELECT p.products_id
                                       FROM ".TABLE_PRODUCTS." p 
                                       JOIN ".TABLE_PRODUCTS_DESCRIPTION." pd
                                            ON p.products_id = pd.products_id
                                               AND pd.language_id = '".(int)$_SESSION['languages_id']."'
                                               AND trim(pd.products_name) != ''
                                       JOIN ".TABLE_PRODUCTS_TO_CATEGORIES." p2c 
                                            ON p.products_id = p2c.products_id
                                       JOIN ".TABLE_CATEGORIES." c
                                            ON c.categories_id = p2c.categories_id
                                               AND c.categories_status = 1
                                                   ".CATEGORIES_CONDITIONS_C."
                                      WHERE p.products_status = '1'
                                            ".PRODUCTS_CONDITIONS_P."
                                            ".$days);
    if (xtc_db_num_rows($products_new_query, true) > 0) {
      $whatsnew_check = true;
    }
  }
  
  return $whatsnew_check;
}
