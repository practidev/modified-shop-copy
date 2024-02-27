<?php
/* -----------------------------------------------------------------------------------------
   $Id: xtc_get_zone_name.inc.php 14430 2022-05-07 06:00:42Z GTB $   

   XT-Commerce - community made shopping
   http://www.xt-commerce.com

   Copyright (c) 2003 XT-Commerce
   -----------------------------------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(general.php,v 1.225 2003/05/29); www.oscommerce.com 
   (c) 2003	 nextcommerce (xtc_get_zone_name.inc.php,v 1.3 2003/08/13); www.nextcommerce.org

   Released under the GNU General Public License 
   ---------------------------------------------------------------------------------------*/
   
  function xtc_get_zone_name($country_id, $zone_id, $default_zone) {
    $zone_name_query = xtDBquery("SELECT zone_name 
                                    FROM ".TABLE_ZONES." 
                                   WHERE zone_country_id = '".(int)$country_id."' 
                                     AND zone_id = '".(int)$zone_id."'");
    if (xtc_db_num_rows($zone_name_query, true) > 0) {
      $zone_name = xtc_db_fetch_array($zone_name_query, true);
      return $zone_name['zone_name'];
    }
    
    return $default_zone;
  }
