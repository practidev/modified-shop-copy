<?php
/* -----------------------------------------------------------------------------------------
   $Id: xtc_activate_banners.inc.php 15467 2023-09-20 14:08:37Z GTB $

   XT-Commerce - community made shopping
   http://www.xt-commerce.com

   Copyright (c) 2003 XT-Commerce
   -----------------------------------------------------------------------------------------
   based on:
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(banner.php,v 1.10 2003/02/11); www.oscommerce.com
   (c) 2003     nextcommerce (xtc_activate_banners.inc.php,v 1.3 2003/08/13); www.nextcommerce.org

   Released under the GNU General Public License
   ---------------------------------------------------------------------------------------*/

  // Auto activate banners
  function xtc_activate_banners() {
    $banners_query = xtc_db_query("SELECT banners_group_id, 
                                          date_scheduled 
                                     FROM " . TABLE_BANNERS . " 
                                    WHERE date_scheduled IS NOT NULL");
    if (xtc_db_num_rows($banners_query)) {
      while ($banners = xtc_db_fetch_array($banners_query)) {
        if (date('Y-m-d H:i:s') >= $banners['date_scheduled']) {
          xtc_set_banner_status($banners['banners_group_id'], '1');
        }
      }
    }
  }
?>