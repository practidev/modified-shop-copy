<?php
/* -----------------------------------------------------------------------------------------
   $Id: checkout_address_layout.php 15676 2024-01-10 16:31:19Z GTB $

   modified eCommerce Shopsoftware
   http://www.modified-shop.org

   Copyright (c) 2009 - 2013 [www.modified-shop.org]
   -----------------------------------------------------------------------------------------
     Released under the GNU General Public License
   ---------------------------------------------------------------------------------------*/

  if ($addresses_count > 0) {
    require_once(DIR_FS_INC . 'xtc_get_zone_name.inc.php'); 
    require_once(DIR_FS_INC . 'xtc_get_country_name.inc.php'); 

    $address_content_array = array();
    $address_content = '<ol id="address_block">';
    $addresses_query = xtc_db_query("SELECT address_book_id,
                                            entry_firstname as firstname,
                                            entry_lastname as lastname,
                                            entry_company as company,
                                            entry_street_address as street_address,
                                            entry_suburb as suburb,
                                            entry_city as city,
                                            entry_postcode as postcode,
                                            entry_state as state,
                                            entry_zone_id as zone_id,
                                            entry_country_id as country_id
                                       FROM ".TABLE_ADDRESS_BOOK."
                                      WHERE customers_id = '".(int)$_SESSION['customer_id']."'");
    $i = 0;
    while ($addresses = xtc_db_fetch_array($addresses_query)) {
      $format_id = xtc_get_address_format_id($addresses['country_id']);
      $address_book_id = (isset($billto) && $billto ? $billto : $_SESSION['sendto']);
      
      foreach ($addresses as $k => $v) {
        $address_content_array[$i][strtoupper($k)] = $v;
      }
      $address_content_array[$i]['ADDRESS_FORMAT'] = xtc_address_format($format_id, $addresses, true, ' ', ', ');
      $address_content_array[$i]['ADDRESS_LABEL'] = xtc_address_label($_SESSION['customer_id'], $addresses['address_book_id'], true, ' ', '<br />');
      $address_content_array[$i]['RADIO_FIELD'] = xtc_draw_radio_field('address', $addresses['address_book_id'], ($addresses['address_book_id'] == $address_book_id), 'id="field_addresses_'.$addresses['address_book_id'].'"');
      $address_content_array[$i]['COUNTRY'] = xtc_get_country_name($addresses['country_id']);
      $address_content_array[$i]['STATE'] = xtc_get_zone_name($addresses['country_id'], $addresses['zone_id'], $addresses['state']);
      $address_content_array[$i]['SELECTED'] = (($addresses['address_book_id'] == $address_book_id) ? 1 : 0);
      $address_content_array[$i]['FORM_ACTION'] = xtc_draw_form('checkout_address', xtc_href_link(((isset($billto) && $billto) ? FILENAME_CHECKOUT_PAYMENT_ADDRESS : FILENAME_CHECKOUT_SHIPPING_ADDRESS), $params, 'SSL')).secure_form('checkout_address').xtc_draw_hidden_field('address', $addresses['address_book_id']);
      $address_content_array[$i]['BUTTON_CONTINUE'] = xtc_draw_hidden_field('action', 'submit').xtc_image_submit('small_continue.gif', IMAGE_BUTTON_CONTINUE);
      $address_content_array[$i]['FORM_END'] = '</form>';
      $address_content_array[$i]['BUTTON_EDIT'] = '<a href="'.xtc_href_link(basename($PHP_SELF), $params.'action=edit&id='.$addresses['address_book_id'], 'SSL').'">'.xtc_image_button('small_edit.gif', SMALL_IMAGE_BUTTON_EDIT).'</a>';
      
      $address_content .= sprintf('<li>%s<label for="field_addresses_%s"> %s %s</label><br /><span class="address">%s</span></li>', xtc_draw_radio_field('address',$addresses['address_book_id'], ($addresses['address_book_id'] == $address_book_id), 'id="field_addresses_'.$addresses['address_book_id'].'"'), $addresses['address_book_id'], $addresses['firstname'], $addresses['lastname'], xtc_address_format($format_id, $addresses, true, ' ', ', '));
      unset($address_book_id);
      
      $i ++;
    }
    $address_content .= '</ol>';

    $smarty->assign('BLOCK_ADDRESS_ARRAY', $address_content_array);
    $smarty->assign('BLOCK_ADDRESS', $address_content);
  }