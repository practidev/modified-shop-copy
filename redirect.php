<?php
/* -----------------------------------------------------------------------------------------
   $Id: redirect.php 14832 2022-12-15 16:03:16Z GTB $

   modified eCommerce Shopsoftware
   http://www.modified-shop.org

   Copyright (c) 2009 - 2013 [www.modified-shop.org]
   -----------------------------------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(redirect.php,v 1.9 2003/02/13); www.oscommerce.com 
   (c) 2003	 nextcommerce (redirect.php,v 1.7 2003/08/17); www.nextcommerce.org
   (c) 2003 XT-Commerce
   
   Released under the GNU General Public License 
   ---------------------------------------------------------------------------------------*/

include ('includes/application_top.php');

require_once (DIR_FS_INC.'xtc_update_banner_click_count.inc.php');
require_once (DIR_FS_INC.'xtc_get_banners_url.inc.php');

if (isset($_GET['action'])) {
  switch ($_GET['action']) {
    case 'banner' :
      $banner_query = xtDBquery("SELECT banners_url 
                                   FROM ".TABLE_BANNERS." 
                                  WHERE banners_id = '".(int) $_GET['goto']."'
                                    AND trim(banners_url) != ''");
      if (xtc_db_num_rows($banner_query, true)) {
        $banner = xtc_db_fetch_array($banner_query, true);
        xtc_update_banner_click_count($_GET['goto']);
      
        xtc_redirect(xtc_get_banners_url($banner['banners_url']));
      }
      break;

    case 'product' :
      if (isset ($_GET['id'])) {
        $product_query = xtDBquery("SELECT products_url 
                                      FROM ".TABLE_PRODUCTS_DESCRIPTION." 
                                     WHERE products_id='".(int) $_GET['id']."'
                                       AND trim(products_name) != ''           
                                       AND trim(products_url) != ''           
                                       AND language_id='".(int) $_SESSION['languages_id']."'");
        if (xtc_db_num_rows($product_query, true)) {
          $product = xtc_db_fetch_array($product_query, true);

          xtc_redirect(check_url_scheme($product['products_url']));
        }
      }
      break;

    case 'manufacturer' :
      if (isset ($_GET['manufacturers_id'])) {
        $manufacturer_query = xtDBquery("SELECT manufacturers_url 
                                           FROM ".TABLE_MANUFACTURERS_INFO." 
                                          WHERE manufacturers_id = '".(int) $_GET['manufacturers_id']."' 
                                            AND languages_id = '".(int) $_SESSION['languages_id']."'
                                            AND trim(manufacturers_url) != ''");
        if (xtc_db_num_rows($manufacturer_query, true) < 1) {
          // no url exists for the selected language, lets use the default language then
          $manufacturer_query = xtDBquery("SELECT mi.languages_id, 
                                                  mi.manufacturers_url 
                                             FROM ".TABLE_MANUFACTURERS_INFO." mi
                                             JOIN ".TABLE_LANGUAGES." l 
                                                  ON mi.languages_id = l.languages_id
                                                     AND l.code = '".DEFAULT_LANGUAGE."'
                                            WHERE mi.manufacturers_id = '".(int) $_GET['manufacturers_id']."'
                                              AND trim(mi.manufacturers_url) != ''");
          if (xtc_db_num_rows($manufacturer_query, true) > 0) {
            $manufacturer = xtc_db_fetch_array($manufacturer_query, true);
            xtc_db_query("UPDATE ".TABLE_MANUFACTURERS_INFO." 
                             SET url_clicked = url_clicked+1, 
                                 date_last_click = now() 
                           WHERE manufacturers_id = '".(int) $_GET['manufacturers_id']."' 
                             AND languages_id = '".$manufacturer['languages_id']."'");

            xtc_redirect(check_url_scheme($manufacturer['manufacturers_url']));
          }
        } else {
          // url exists in selected language
          $manufacturer = xtc_db_fetch_array($manufacturer_query, true);
          xtc_db_query("UPDATE ".TABLE_MANUFACTURERS_INFO." 
                           SET url_clicked = url_clicked+1, 
                               date_last_click = now() 
                         WHERE manufacturers_id = '".(int) $_GET['manufacturers_id']."' 
                           AND languages_id = '".(int)$_SESSION['languages_id']."'");

          xtc_redirect(check_url_scheme($manufacturer['manufacturers_url']));
        }
      }
      break;
  }
}

// default
xtc_redirect(xtc_href_link(FILENAME_DEFAULT));
