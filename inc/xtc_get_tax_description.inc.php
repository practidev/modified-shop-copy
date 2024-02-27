<?php
/* -----------------------------------------------------------------------------------------
   $Id: xtc_get_tax_description.inc.php 14670 2022-07-18 14:02:56Z GTB $   

   XT-Commerce - community made shopping
   http://www.xt-commerce.com

   Copyright (c) 2003 XT-Commerce
   -----------------------------------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(general.php,v 1.225 2003/05/29); www.oscommerce.com 
   (c) 2003	 nextcommerce (xtc_get_tax_description.inc.php); www.nextcommerce.org

   Released under the GNU General Public License 
   ---------------------------------------------------------------------------------------*/
   
  // include needed function
  require_once(DIR_FS_INC.'parse_multi_language_value.inc.php');

  function xtc_get_tax_description($class_id, $country_id= -1, $zone_id= -1) {
    global $PHP_SELF;
    static $tax_description_array;
    
    if (!isset($tax_description_array)) {
      $tax_description_array = array();
    }
        
    if (isset($_SESSION['country']) && strpos(basename($PHP_SELF), 'checkout') === false) {
      $country_id = $_SESSION['country'];
    }
  	
    if ($country_id == -1 && $zone_id == -1) {
      if (!isset($_SESSION['customer_id'])) {
        $country_id = STORE_COUNTRY;
        $zone_id = STORE_ZONE;
      } else {
        $country_id = $_SESSION['customer_country_id'];
        $zone_id = $_SESSION['customer_zone_id'];
      }
    }
  	
  	if (!isset($tax_description_array[$country_id][$zone_id][$class_id])) {
      $where = "AND (z2gz.zone_id = 0 ";
      if ($zone_id >= 0) {
        $where .= "OR z2gz.zone_id = '" . (int)$zone_id . "'";
      }
      $where .= ")";

      $tax_query = xtDBquery("SELECT sum(tax_rate) as tax_rate,
                                     tr.tax_description
                                FROM " . TABLE_TAX_RATES . " tr 
                                JOIN " . TABLE_GEO_ZONES . " tz 
                                     ON tz.geo_zone_id = tr.tax_zone_id
                                JOIN " . TABLE_ZONES_TO_GEO_ZONES . " z2gz 
                                     ON tr.tax_zone_id = z2gz.geo_zone_id
                                        AND z2gz.zone_country_id = '" . (int)$country_id . "'
                                        ".$where."
                               WHERE tr.tax_class_id = '" . (int)$class_id . "' 
                            GROUP BY tr.tax_priority");
      if (xtc_db_num_rows($tax_query,true)) {
        $tax_description = '';
        while ($tax = xtc_db_fetch_array($tax_query,true)) {
          $tax_description .= parse_multi_language_value($tax['tax_description'], $_SESSION['language_code']) . ' + ';
        }
        $tax_description = substr($tax_description, 0, -3);

        $tax_description_array[$country_id][$zone_id][$class_id] = $tax_description;
      } else {
        $tax_description_array[$country_id][$zone_id][$class_id] = TEXT_UNKNOWN_TAX_RATE;
      }
    }
    
    return $tax_description_array[$country_id][$zone_id][$class_id];
  }
