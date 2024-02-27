<?php
/* -----------------------------------------------------------------------------------------
   $Id: currencies.php 15291 2023-07-06 11:46:25Z GTB $

   modified eCommerce Shopsoftware
   http://www.modified-shop.org

   Copyright (c) 2009 - 2013 [www.modified-shop.org]
   -----------------------------------------------------------------------------------------
   Released under the GNU General Public License 
   ---------------------------------------------------------------------------------------*/

  // include smarty
  include(DIR_FS_BOXES_INC . 'smarty_default.php');

  // set cache id
  $cache_id = md5('lID:'.$_SESSION['language'].'|curr:'.$_SESSION['currency'].'|site:'.basename($PHP_SELF).'|params:'.xtc_get_all_get_params(array('currency', 'language')));

  if (!$box_smarty->is_cached(CURRENT_TEMPLATE.'/boxes/box_currencies.html', $cache_id) || !$cache) {

    $currencies_array = array();
    if (isset($xtPrice) && is_object($xtPrice)) {
      foreach ($xtPrice->currencies as $key => $value) {
        $currencies_array[] = array('id' => $key, 'text' => $value['title']);
      }
    }

    // dont show box if there's only 1 currency
    if (count($currencies_array) > 1 ) {
      $box_content = xtc_draw_form('currencies', xtc_href_link(basename($PHP_SELF), '', $request_type, false), 'get', '')
                     . xtc_draw_pull_down_menu('currency', $currencies_array, $_SESSION['currency'], 'aria-label="currencies" onchange="this.form.submit();"')
                     . xtc_hide_session_id();
    
      parse_str(xtc_get_all_get_params(array('currency', 'language')), $params_array);
      if (is_array($params_array) && count($params_array) > 0) {
        foreach ($params_array as $k => $v) {
          if (is_array($v)) {
            foreach ($v as $kk => $vv) {
              $box_content .= xtc_draw_hidden_field($k.'['.$kk.']', $vv);
            }
          } else {
            $box_content .= xtc_draw_hidden_field($k, $v);
          }
        }
      }
        
      $box_content .= '</form>';

      $box_smarty->assign('BOX_CONTENT', $box_content);
    }
  }

  $box_currencies = $box_smarty->fetch(CURRENT_TEMPLATE.'/boxes/box_currencies.html', $cache_id);

  $smarty->assign('box_CURRENCIES', $box_currencies);
