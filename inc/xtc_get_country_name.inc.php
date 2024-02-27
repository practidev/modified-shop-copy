<?php
/* -----------------------------------------------------------------------------------------
   $Id: xtc_get_country_name.inc.php 13805 2021-11-08 10:26:22Z GTB $   

   XT-Commerce - community made shopping
   http://www.xt-commerce.com

   Copyright (c) 2003 XT-Commerce
   -----------------------------------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(general.php,v 1.225 2003/05/29); www.oscommerce.com 
   (c) 2003	 nextcommerce (xtc_get_country_name.inc.php,v 1.5 2003/08/13); www.nextcommerce.org

   Released under the GNU General Public License 
   ---------------------------------------------------------------------------------------*/
    
  function xtc_get_country_name($country_id) {
    static $countries_name_cache;
    
    if (!isset($countries_name_cache)) {
      $countries_name_cache = array();
    }
    
    if (!isset($countries_name_cache[$country_id])) {
      $countries_name_cache[$country_id] = $country_id;
      $country_query = xtDBquery("SELECT countries_name
                                    FROM ".TABLE_COUNTRIES."
                                   WHERE countries_id = '".(int)$country_id."'");
      if (xtc_db_num_rows($country_query, true) > 0) {
        $country = xtc_db_fetch_array($country_query, true);
        $countries_name_cache[$country_id] = $country['countries_name'];
      }
    }
    
    return $countries_name_cache[$country_id];
  }
