<?php
/* --------------------------------------------------------------
   $Id: group_prices.php 14846 2022-12-16 13:33:06Z GTB $

   modified eCommerce Shopsoftware
   http://www.modified-shop.org

   Copyright (c) 2009 - 2013 [www.modified-shop.org]
   --------------------------------------------------------------
   based on:
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(based on original files from OSCommerce CVS 2.2 2002/08/28 02:14:35); www.oscommerce.com
   (c) 2003 nextcommerce (group_prices.php,v 1.16 2003/08/21); www.nextcommerce.org
   (c) 2006 xt-commerce (group_prices.php 1307 2005-10-14); www.xt-commerce.com

   Released under the GNU General Public License
   --------------------------------------------------------------
   based on Third Party contribution:
   Customers Status v3.x  (c) 2002-2003 Copyright Elari elari@free.fr | www.unlockgsm.com/dload-osc/ | CVS : http://cvs.sourceforge.net/cgi-bin/viewcvs.cgi/elari/?sortby=date#dirlist

   Released under the GNU General Public License
   --------------------------------------------------------------*/
   
defined('_VALID_XTC') or die('Direct Access to this location is not allowed.');
require_once (DIR_FS_INC.'xtc_get_tax_rate.inc.php');

require_once (DIR_FS_CATALOG.DIR_WS_CLASSES.'xtcPrice.php');
$xtPrice = new xtcPrice(DEFAULT_CURRENCY, $_SESSION['customers_status']['customers_status_id']);

$price_precision = $xtPrice->currencies[DEFAULT_CURRENCY]['decimal_places'];

$group_array = array();
$group_query = xtc_db_query("SELECT customers_status_image,
                                    customers_status_id,
                                    customers_status_name,
                                    customers_status_graduated_prices
                               FROM ".TABLE_CUSTOMERS_STATUS."
                              WHERE language_id = '".(int)$_SESSION['languages_id']."'
                              AND customers_status_id != '0'");
while ($group_values = xtc_db_fetch_array($group_query)) {
  // load data into array
  $group_array[] = array ('STATUS_NAME' => $group_values['customers_status_name'],
                          'STATUS_IMAGE' => $group_values['customers_status_image'],
                          'STATUS_ID' => $group_values['customers_status_id'],
                          'STATUS_GRADUATED' => $group_values['customers_status_graduated_prices'],
                          );
}

$products_tax_rate = xtc_get_tax_rate($pInfo->products_tax_class_id);
?>
<div style="padding:5px;">
  <div class="main div_header"><?php echo HEADING_PRICES_OPTIONS. draw_tooltip(TEXT_GRADUATED_PRICES_INFO) ?></div>
  <table class="tableInput">
    <tr>
      <td style="width:165px;vertical-align:top;line-height:30px;" class="main"><?php echo TEXT_PRODUCTS_PRICE; ?></td>
        <?php
        // calculate brutto price for display
        if (PRICE_IS_BRUTTO == 'true') {
          $products_price = xtc_round($pInfo->products_price * ((100 + $products_tax_rate) / 100), $price_precision);
          if ($products_price > 0) $products_price = sprintf("%01.".$price_precision."f", $products_price);
        } else {
          $products_price = xtc_round($pInfo->products_price, PRICE_PRECISION);
        }
        ?>
      <td class="main" style="width:160px;vertical-align:top;line-height:30px;"><?php echo xtc_draw_input_field('products_price', $products_price, 'style="width: 155px"'); ?></td>
      <td class="main" style="width:120px;vertical-align:top;line-height:30px; white-space: nowrap;">
        <?php
        if (PRICE_IS_BRUTTO == 'true') {
          echo TEXT_NETTO.'<strong>'.$xtPrice->xtcFormat($pInfo->products_price, false).'</strong>  ';
        } else {
          echo '&nbsp;';
        }
        ?>
      </td>
      <td class="main" style="vertical-align:top;line-height:30px;"><?php require_once("includes/modules/categories_specials.php"); ?></td>
    </tr>
  <?php
  foreach($group_array as $group_data) {
  ?>
    <tr>
      <td style="border-top: 1px solid #cccccc;vertical-align:top;line-height:30px;" class="main"><?php echo $group_data['STATUS_NAME']; ?></td>
        <?php
          if (PRICE_IS_BRUTTO == 'true') {
            $products_price = xtc_round(get_group_price($group_data['STATUS_ID'], $pInfo->products_id) * ((100 + $products_tax_rate) / 100), $price_precision);
            if ($products_price > 0) $products_price = sprintf("%01.".$price_precision."f", $products_price);
          } else {
            $products_price = xtc_round(get_group_price($group_data['STATUS_ID'], $pInfo->products_id), PRICE_PRECISION);
          }
        ?>
      <td style="border-top: 1px solid #cccccc; vertical-align:top;line-height:30px;" class="main">
        <?php
          echo xtc_draw_input_field('products_price_'.$group_data['STATUS_ID'], $products_price, 'style="width: 155px"');
        ?>
      </td>
      <td style="border-top: 1px solid #cccccc; white-space: nowrap;vertical-align:top;line-height:30px;" class="main">
        <?php
          if (PRICE_IS_BRUTTO == 'true' && get_group_price($group_data['STATUS_ID'], $pInfo->products_id) != '0') {
            echo TEXT_NETTO.'<strong>'.$xtPrice->xtcFormat(get_group_price($group_data['STATUS_ID'], $pInfo->products_id), false).'</strong>';
          } else {
            echo '&nbsp;';
          }
        ?>
      </td>
      <td style="border-top: 1px solid #cccccc;vertical-align:top;line-height:30px;" class="main">
        <?php
          echo '<span'.(($group_data['STATUS_GRADUATED'] != '1') ? ' class="colorRed"' : '').'>'.TXT_STAFFELPREIS.'</span>';         
          if (isset($pInfo->products_id) && $pInfo->products_id > 0) {
            // ok, lets check if there is already a staffelpreis
            $staffel_query = xtc_db_query("SELECT price_id,
                                                  products_id,
                                                  quantity,
                                                  personal_offer
                                             FROM ".TABLE_PERSONAL_OFFERS_BY.$group_data['STATUS_ID']."
                                            WHERE products_id = '".$pInfo->products_id."'
                                              AND quantity != '1'
                                         ORDER BY quantity ASC");
          }
          ?> 
          <img onMouseOver="javascript:this.style.cursor='pointer';" src="images/<?php echo ((isset($pInfo->products_id) && $pInfo->products_id > 0 && xtc_db_num_rows($staffel_query) > 0) ? 'arrow_down_green.gif' : 'arrow_down.gif'); ?>" height="16" width="16" onclick="javascript:toggleBox('staffel_<?php echo $group_data['STATUS_ID']; ?>');" style="vertical-align: middle;"><?php echo (($group_data['STATUS_GRADUATED'] != '1') ? draw_tooltip(TEXT_GRADUATED_PRICES_GROUP_INFO) : ''); ?>
          <div id="staffel_<?php echo $group_data['STATUS_ID']; ?>" class="longDescription">
            <table class="tableConfig borderall">
              <tr class="dataTableHeadingRow lh14">
                <td class="dataTableHeadingContent" style="width:55px;"><b><?php echo TXT_STK; ?></b></td>
                <td class="dataTableHeadingContent"><b><?php echo TXT_STAFFELPREIS; ?></b></td>
                <td class="dataTableHeadingContent" style="width:55px;"><b><?php echo BUTTON_DELETE; ?></b></td>
              </tr>
              <?php
              $count = 0;
              if (isset($pInfo->products_id) && $pInfo->products_id > 0) {
                if (xtc_db_num_rows($staffel_query) > 0) {
                  while ($staffel_values = xtc_db_fetch_array($staffel_query)) {
                    ?>
                    <tr class="dataTableRow">
                      <td class="dataTableContent"><?php echo xtc_draw_input_field('products_staffel['.$group_data['STATUS_ID'].']['.$count.'][quantity]', $staffel_values['quantity'], 'style="width:50px;"'); ?></td>            
                      <td class="dataTableContent">
                        <?php
                        if (PRICE_IS_BRUTTO == 'true') {
                          $products_price = xtc_round($staffel_values['personal_offer'] * ((100 + xtc_get_tax_rate($pInfo->products_tax_class_id)) / 100), $price_precision);
                          if ($products_price > 0) $products_price = sprintf("%01.".$price_precision."f", $products_price);
                        } else {
                          $products_price = xtc_round($staffel_values['personal_offer'], PRICE_PRECISION);
                        }
                        echo xtc_draw_input_field('products_staffel['.$group_data['STATUS_ID'].']['.$count.'][personal_offer]', $products_price, 'style="width: 135px"');
                        if (PRICE_IS_BRUTTO == 'true') {
                          echo '&nbsp;<span style="white-space: nowrap;">'.TEXT_NETTO.'<strong>'.$xtPrice->xtcFormat($staffel_values['personal_offer'], false).'</strong></span>';
                        }
                        echo xtc_draw_hidden_field('products_staffel['.$group_data['STATUS_ID'].']['.$count.'][price_id]', $staffel_values['price_id']);
                        ?>
                      </td>
                      <td class="dataTableContent txta-c"><?php echo xtc_draw_checkbox_field('products_staffel['.$group_data['STATUS_ID'].']['.$count.'][delete]'); ?></td>
                    </tr>          
                    <?php
                    $count++;
                  }
                }
              }
              $max_staffel = MIN_GROUP_PRICE_STAFFEL + $count;
              //if ($count >= $max_staffel) {
                //$max_staffel = $count+1;
                //xtc_db_query("UPDATE " . TABLE_CONFIGURATION . " SET configuration_value='" . (int)$max_staffel . "', last_modified = NOW() where configuration_key='" . 'MIN_GROUP_PRICE_STAFFEL' . "'");
              //}
              for ($is=$count; $is<$max_staffel; $is++) {
              ?>
              <tr>
                <td class="main"><?php echo xtc_draw_input_field('products_staffel['.$group_data['STATUS_ID'].']['.$is.'][quantity]', '', 'style="width:50px;"'); ?></td>            
                <td class="main"><?php echo xtc_draw_input_field('products_staffel['.$group_data['STATUS_ID'].']['.$is.'][personal_offer]', '', 'style="width: 135px"'); ?></td>
                <td class="main">&nbsp;</td>
              </tr>
              <?php
              }
              ?>
          </table>
        </div>
      </td>
    </tr>
  <?php
  }
  ?>
    <tr>
      <td class="main" style="border-top: 1px solid #cccccc;"><?php echo TEXT_PRODUCTS_DISCOUNT_ALLOWED; ?></td>
      <td class="main" colspan="3" style="border-top: 1px solid #cccccc;"><?php echo xtc_draw_input_field('products_discount_allowed', $pInfo->products_discount_allowed, 'style="width: 155px"'); ?></td>
    </tr>
    <tr>
      <td class="main"><?php echo TEXT_PRODUCTS_TAX_CLASS; ?></td>
      <td class="main" colspan="3"><?php echo xtc_draw_pull_down_menu('products_tax_class_id', $tax_class_array, $pInfo->products_tax_class_id, 'style="width: 155px"').xtc_draw_hidden_field('products_tax_class_id_old', $pInfo->products_tax_class_id); ?></td>
    </tr>
    <?php      
      $products_tax_class_array = array();
      $products_tax_class_query = xtc_db_query("SELECT *
                                                  FROM ".TABLE_PRODUCTS_GEO_ZONES_TO_TAX_CLASS."
                                                 WHERE products_id = '".$pInfo->products_id."'");
      while ($products_tax_class = xtc_db_fetch_array($products_tax_class_query)) {
        $products_tax_class_array[$products_tax_class['geo_zone_id']] = $products_tax_class['tax_class_id'];
      }

      $geo_zones_query = xtc_db_query("SELECT *
                                         FROM ".TABLE_GEO_ZONES."
                                        WHERE geo_zone_tax = 1");
      while ($geo_zones = xtc_db_fetch_array($geo_zones_query)) {
        $tax_class_geo_array = array(array ('id' => '0', 'text' => TEXT_NONE));
        $tax_class_query = xtc_db_query("SELECT tc.*
                                           FROM ".TABLE_TAX_CLASS." tc
                                           JOIN ".TABLE_TAX_RATES." tr
                                                ON tr.tax_class_id = tc.tax_class_id
                                                   AND tr.tax_zone_id = ".$geo_zones['geo_zone_id']."
                                       ORDER BY tc.sort_order, tc.tax_class_id");
        while ($tax_class = xtc_db_fetch_array($tax_class_query)) {
          $tax_class_geo_array[] = array ('id' => $tax_class['tax_class_id'], 'text' => parse_multi_language_value($tax_class['tax_class_title'], $_SESSION['language_code']));
        }
        ?>
        <tr>
          <td class="main"><?php echo TEXT_PRODUCTS_TAX_CLASS_FOR . parse_multi_language_value($geo_zones['geo_zone_name'], $_SESSION['language_code']); ?>:</td>
          <td class="main" colspan="3"><?php echo xtc_draw_pull_down_menu('products_geo_to_tax['.$geo_zones['geo_zone_id'].']', $tax_class_geo_array, ((isset($products_tax_class_array[$geo_zones['geo_zone_id']])) ? $products_tax_class_array[$geo_zones['geo_zone_id']] : $pInfo->products_tax_class_id), 'style="width: 155px"'); ?></td>
        </tr>
        <?php
      }
    ?>
    <tr>
      <td class="main"><?php echo TEXT_PRODUCTS_ATTRIBUTES_RECALCULATE; ?></td>
      <td class="main" colspan="3"><?php echo draw_on_off_selection('products_attributes_recalculate', 'checkbox', false) ?></td>
    </tr>
  </table>
</div>