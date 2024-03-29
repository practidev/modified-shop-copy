<?php
/* --------------------------------------------------------------
   $Id: shipping_status.php 15189 2023-06-01 12:42:01Z GTB $

   XT-Commerce - community made shopping
   http://www.xt-commerce.com

   Copyright (c) 2003 XT-Commerce
   --------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(orders_status.php,v 1.19 2003/02/06); www.oscommerce.com
   (c) 2003	 nextcommerce (orders_status.php,v 1.9 2003/08/18); www.nextcommerce.org

   Released under the GNU General Public License 
   --------------------------------------------------------------*/

  require('includes/application_top.php');

  //display per page
  $cfg_max_display_results_key = 'MAX_DISPLAY_SHIPPING_STATUS_RESULTS';
  $page_max_display_results = xtc_cfg_save_max_display_results($cfg_max_display_results_key);

  $action = (isset($_GET['action']) ? $_GET['action'] : '');
  $page = (isset($_GET['page']) ? (int)$_GET['page'] : 1);
  
  switch ($action) {
    case 'insert':
    case 'save':
      $oID = ((isset($_GET['oID'])) ? (int)$_GET['oID'] : 0);

      $languages = xtc_get_languages();
      for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {
        $shipping_status_name_array = $_POST['shipping_status_name'];
        $language_id = $languages[$i]['id'];

        $sql_data_array = array(
          'shipping_status_name' => xtc_db_prepare_input($shipping_status_name_array[$language_id]),
          'sort_order' => (int)$_POST['sort_order']
        );

        if ($action == 'insert') {
          if ($oID == 0) {
            $next_id_query = xtc_db_query("SELECT max(shipping_status_id) as shipping_status_id FROM " . TABLE_SHIPPING_STATUS);
            $next_id = xtc_db_fetch_array($next_id_query);
            $oID = $next_id['shipping_status_id'] + 1;
          }

          $insert_sql_data = array(
            'shipping_status_id' => $oID,
            'language_id' => $language_id
          );
          $sql_data_array = array_merge($sql_data_array, $insert_sql_data);
          xtc_db_perform(TABLE_SHIPPING_STATUS, $sql_data_array);
        } elseif ($action == 'save') {
          $shipping_status_query = xtc_db_query("SELECT * 
                                                   FROM ".TABLE_SHIPPING_STATUS." 
                                                  WHERE language_id = '".$language_id."' 
                                                    AND shipping_status_id = '".$oID."'");
          if (xtc_db_num_rows($shipping_status_query) == 0) {
            xtc_db_perform(TABLE_SHIPPING_STATUS, array('shipping_status_id' => $oID, 'language_id' => $language_id));
          }
          xtc_db_perform(TABLE_SHIPPING_STATUS, $sql_data_array, 'update', "shipping_status_id = '" . $oID . "' AND language_id = '" . $language_id . "'");
        }
      }

      if (isset($_POST['delete_image']) && $_POST['delete_image'] == 'on') {
        $image_location = DIR_FS_DOCUMENT_ROOT . DIR_WS_IMAGES . $_POST['shipping_image'];        
        if (is_file($image_location)) {
          unlink($image_location);
          xtc_db_query("UPDATE " . TABLE_SHIPPING_STATUS . " 
                           SET shipping_status_image = '' 
                         WHERE shipping_status_id = '" . xtc_db_input($oID) . "'");
        }
      }

      require(DIR_WS_INCLUDES.'upload_types.php');
      
      if ($shipping_status_image = xtc_try_upload('shipping_status_image', DIR_FS_DOCUMENT_ROOT.DIR_WS_IMAGES, '644', $accepted_image_extensions, $accepted_image_mime_types)) {
        xtc_db_query("UPDATE " . TABLE_SHIPPING_STATUS . " 
                         SET shipping_status_image = '" . $shipping_status_image->filename . "' 
                       WHERE shipping_status_id = '" . xtc_db_input($oID) . "'");
      }
      xtc_redirect(xtc_href_link(FILENAME_SHIPPING_STATUS, 'page=' . $page . '&oID=' . $oID));
      break;

    case 'deleteconfirm':
      $oID = (int)$_GET['oID'];

      $shipping_status_query = xtc_db_query("SELECT configuration_value 
                                               FROM " . TABLE_CONFIGURATION . " 
                                              WHERE configuration_key = 'DEFAULT_SHIPPING_STATUS_ID'");
      $shipping_status = xtc_db_fetch_array($shipping_status_query);
      if ($shipping_status['configuration_value'] == $oID) {
        xtc_db_query("UPDATE " . TABLE_CONFIGURATION . " 
                         SET configuration_value = '' 
                       WHERE configuration_key = 'DEFAULT_SHIPPING_STATUS_ID'");
      }
      $shipping_status_image_query = xtc_db_query("SELECT shipping_status_image 
                                                     FROM " . TABLE_SHIPPING_STATUS . " 
                                                    WHERE shipping_status_id='".(int)$oID."'");
      $shipping_status_image = xtc_db_fetch_array($shipping_status_image_query);
      $image_location = DIR_FS_DOCUMENT_ROOT . DIR_WS_IMAGES . $shipping_status_image['shipping_status_image'];        
      if (is_file($image_location)) {
        unlink($image_location);
      }
      xtc_db_query("DELETE FROM " . TABLE_SHIPPING_STATUS . " WHERE shipping_status_id = '" . xtc_db_input($oID) . "'");

      xtc_redirect(xtc_href_link(FILENAME_SHIPPING_STATUS, 'page=' . $page));
      break;

    case 'delete':
      $oID = (int)$_GET['oID'];

      $status_query = xtc_db_query("SELECT count(*) as count 
                                      FROM " . TABLE_PRODUCTS . " 
                                     WHERE products_shippingtime = '" . (int)$oID . "'");
      $status = xtc_db_fetch_array($status_query);
      
      $remove_status = true;
      if ($status['count'] > 0) {
        $remove_status = false;
        $messageStack->add(ERROR_STATUS_USED_IN_PRODUCTS, 'error');
      } 
      break;
  }
  require (DIR_WS_INCLUDES.'head.php');
?>
<script type="text/javascript" src="includes/general.js"></script>
</head>
<body>
  <!-- header //-->
  <?php require(DIR_WS_INCLUDES . 'header.php'); ?>
  <!-- header_eof //-->
  <!-- body //-->
  <table class="tableBody">
    <tr>
      <?php //left_navigation
      if (USE_ADMIN_TOP_MENU == 'false') {
        echo '<td class="columnLeft2">'.PHP_EOL;
        echo '<!-- left_navigation //-->'.PHP_EOL;       
        require_once(DIR_WS_INCLUDES . 'column_left.php');
        echo '<!-- left_navigation eof //-->'.PHP_EOL; 
        echo '</td>'.PHP_EOL;      
      }
      ?>
      <!-- body_text //-->
      <td class="boxCenter">
        <div class="pageHeadingImage"><?php echo xtc_image(DIR_WS_ICONS.'heading/icon_configuration.png'); ?></div>
        <div class="pageHeading"><?php echo HEADING_TITLE; ?></div>       
        <div class="main pdg2 flt-l">Configuration</div>       
        <table class="tableCenter">      
          <tr>
            <td class="boxCenterLeft">
              <table class="tableBoxCenter collapse">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent"><?php echo 'ID'; ?></td>
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_SHIPPING_STATUS_IMAGE; ?></td>
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_SHIPPING_STATUS; ?></td>
                <td class="dataTableHeadingContent txta-r"><?php echo TABLE_HEADING_SORT; ?>&nbsp;</td>
                <td class="dataTableHeadingContent txta-r"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
              </tr>
              <?php
                $shipping_status_query_raw = "SELECT * 
                                                FROM " . TABLE_SHIPPING_STATUS . " 
                                               WHERE language_id = '" . (int)$_SESSION['languages_id'] . "' 
                                            ORDER BY sort_order, shipping_status_id";
                $shipping_status_split = new splitPageResults($page, $page_max_display_results, $shipping_status_query_raw, $shipping_status_query_numrows, 'shipping_status_id', 'oID');
                $shipping_status_query = xtc_db_query($shipping_status_query_raw);
                while ($shipping_status = xtc_db_fetch_array($shipping_status_query)) {
                  if ((!isset($_GET['oID']) || $_GET['oID'] == $shipping_status['shipping_status_id']) && !isset($oInfo) && (substr($action, 0, 3) != 'new')) {
                    $oInfo = new objectInfo($shipping_status);
                  }

                  if (isset($oInfo) && is_object($oInfo) && $shipping_status['shipping_status_id'] == $oInfo->shipping_status_id) {
                    echo '<tr class="dataTableRowSelected" onmouseover="this.style.cursor=\'pointer\'" onclick="document.location.href=\'' . xtc_href_link(FILENAME_SHIPPING_STATUS, 'page=' . $page . '&oID=' . $oInfo->shipping_status_id . '&action=edit') . '\'">' . "\n";
                  } else {
                    echo '<tr class="dataTableRow" onmouseover="this.className=\'dataTableRowOver\';this.style.cursor=\'pointer\'" onmouseout="this.className=\'dataTableRow\'" onclick="document.location.href=\'' . xtc_href_link(FILENAME_SHIPPING_STATUS, 'page=' . $page . '&oID=' . $shipping_status['shipping_status_id']) . '\'">' . "\n";
                  }
                ?>
                <td class="dataTableContent"><?php echo $shipping_status['shipping_status_id']; ?></td>
                <td class="dataTableContent"><?php echo (($shipping_status['shipping_status_image'] != '') ? xtc_image(DIR_WS_CATALOG.DIR_WS_IMAGES . $shipping_status['shipping_status_image'] , IMAGE_ICON_INFO, '', '', 'style="border:0;max-width:200px;max-height:60px;"') : ''); ?></td>
                <td class="dataTableContent"><?php echo $shipping_status['shipping_status_name']; ?></td>
                <td class="dataTableContent txta-r"><?php echo $shipping_status['sort_order']; ?></td>
                <td class="dataTableContent txta-r"><?php if (isset($oInfo) && is_object($oInfo) && $shipping_status['shipping_status_id'] == $oInfo->shipping_status_id) { echo xtc_image(DIR_WS_IMAGES . 'icon_arrow_right.gif', ICON_ARROW_RIGHT); } else { echo '<a href="' . xtc_href_link(FILENAME_SHIPPING_STATUS, 'page=' . $page . '&oID=' . $shipping_status['shipping_status_id']) . '">' . xtc_image(DIR_WS_IMAGES . 'icon_arrow_grey.gif', IMAGE_ICON_INFO) . '</a>'; } ?>&nbsp;</td>
              </tr>
              <?php
                }
              ?>
              </table>
              
              <div class="smallText pdg2 flt-l"><?php echo $shipping_status_split->display_count($shipping_status_query_numrows, $page_max_display_results, $page, TEXT_DISPLAY_NUMBER_OF_SHIPPING_STATUS); ?></div>
              <div class="smallText pdg2 flt-r"><?php echo $shipping_status_split->display_links($shipping_status_query_numrows, $page_max_display_results, MAX_DISPLAY_PAGE_LINKS, $page); ?></div>
              <?php echo draw_input_per_page($PHP_SELF,$cfg_max_display_results_key,$page_max_display_results); ?>

              <?php
              if (substr($action, 0, 3) != 'new') {
              ?>
              <div class="clear"></div>
              <div class="pdg2 flt-r smallText"><?php echo '<a class="button" onclick="this.blur();" href="' . xtc_href_link(FILENAME_SHIPPING_STATUS, 'page=' . $page . '&action=new') . '">' . BUTTON_INSERT . '</a>'; ?></div>
              <?php
              }
              ?>
            </td>
          <?php
            $heading = array();
            $contents = array();
            switch ($action) {
              case 'new':
                $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_NEW_SHIPPING_STATUS . '</b>');

                $contents = array('form' => xtc_draw_form('status', FILENAME_SHIPPING_STATUS, 'page=' . $page . '&action=insert', 'post', 'enctype="multipart/form-data"'));
                $contents[] = array('text' => TEXT_INFO_INSERT_INTRO);

                $shipping_status_inputs_string = '';
                $languages = xtc_get_languages();
                for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {
                  $shipping_status_inputs_string .= '<br />' . xtc_image(DIR_WS_LANGUAGES.$languages[$i]['directory'].'/admin/images/'.$languages[$i]['image']) . '&nbsp;' . xtc_draw_input_field('shipping_status_name[' . $languages[$i]['id'] . ']');
                }
                $contents[] = array('text' => '<br />' . TEXT_INFO_SHIPPING_STATUS_IMAGE . '<br />' . xtc_draw_file_field('shipping_status_image'));
                $contents[] = array('text' => '<br />' . TEXT_INFO_SHIPPING_STATUS_NAME . $shipping_status_inputs_string);
                $contents[] = array('text' => '<br />' . TEXT_INFO_SHIPPING_STATUS_SORT_ORDER . '<br />' . xtc_draw_input_field('sort_order', ''));
                $contents[] = array('align' => 'center', 'text' => '<br /><input type="submit" class="button" onclick="this.blur();" value="' . BUTTON_INSERT . '"/> <a class="button" onclick="this.blur();" href="' . xtc_href_link(FILENAME_SHIPPING_STATUS, 'page=' . $page) . '">' . BUTTON_CANCEL . '</a>');
                break;

              case 'edit':
                $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_EDIT_SHIPPING_STATUS . '</b>');

                $contents = array('form' => xtc_draw_form('status', FILENAME_SHIPPING_STATUS, 'page=' . $page . '&oID=' . $oInfo->shipping_status_id  . '&action=save', 'post', 'enctype="multipart/form-data"'));
                $contents[] = array('text' => TEXT_INFO_EDIT_INTRO);
                $contents[] = array('text' => '<br />' . TEXT_INFO_SHIPPING_STATUS_IMAGE . '<br />' . xtc_draw_file_field('shipping_status_image') . 
                                              '<br />' . $oInfo->shipping_status_image . xtc_draw_hidden_field('shipping_image', $oInfo->shipping_status_image) .
                                              '<br />' . xtc_draw_checkbox_field('delete_image', '', false) . ' ' . TEXT_DELETE_IMAGE);

                $shipping_status_inputs_string = '';
                $languages = xtc_get_languages();
                for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {
                  $shipping_status_inputs_string .= '<br />' . xtc_image(DIR_WS_LANGUAGES.$languages[$i]['directory'].'/admin/images/'.$languages[$i]['image']) . '&nbsp;' . xtc_draw_input_field('shipping_status_name[' . $languages[$i]['id'] . ']', xtc_get_shipping_status_name($oInfo->shipping_status_id, $languages[$i]['id']));
                }

                $contents[] = array('text' => '<br />' . TEXT_INFO_SHIPPING_STATUS_NAME . $shipping_status_inputs_string);
                $contents[] = array('text' => '<br />' . TEXT_INFO_SHIPPING_STATUS_SORT_ORDER . '<br />' . xtc_draw_input_field('sort_order', $oInfo->sort_order));
                $contents[] = array('align' => 'center', 'text' => '<br /><input type="submit" class="button" onclick="this.blur();" value="' . BUTTON_UPDATE . '"/> <a class="button" onclick="this.blur();" href="' . xtc_href_link(FILENAME_SHIPPING_STATUS, 'page=' . $page . '&oID=' . $oInfo->shipping_status_id) . '">' . BUTTON_CANCEL . '</a>');
                break;

              case 'delete':
                $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_DELETE_SHIPPING_STATUS . '</b>');

                $contents = array('form' => xtc_draw_form('status', FILENAME_SHIPPING_STATUS, 'page=' . $page . '&oID=' . $oInfo->shipping_status_id  . '&action=deleteconfirm'));
                $contents[] = array('text' => TEXT_INFO_DELETE_INTRO);
                $contents[] = array('text' => '<br /><b>' . $oInfo->shipping_status_name . '</b>');
                if ($remove_status) {
                  $contents[] = array('align' => 'center', 'text' => '<br /><input type="submit" class="button" onclick="this.blur();" value="' . BUTTON_DELETE . '"/> <a class="button" onclick="this.blur();" href="' . xtc_href_link(FILENAME_SHIPPING_STATUS, 'page=' . $page . '&oID=' . $oInfo->shipping_status_id) . '">' . BUTTON_CANCEL . '</a>');
                } else {
                  $contents[] = array('align' => 'center', 'text' => '<br /><a class="button" onclick="this.blur();" href="' . xtc_href_link(FILENAME_SHIPPING_STATUS, 'page=' . $page . '&oID=' . $oInfo->shipping_status_id) . '">' . BUTTON_CANCEL . '</a>');
                }
                break;

              default:
                if (isset($oInfo) && is_object($oInfo)) {
                  $heading[] = array('text' => '<b>' . $oInfo->shipping_status_name . '</b>');

                  $contents[] = array('align' => 'center', 'text' => '<a class="button" onclick="this.blur();" href="' . xtc_href_link(FILENAME_SHIPPING_STATUS, 'page=' . $page . '&oID=' . $oInfo->shipping_status_id . '&action=edit') . '">' . BUTTON_EDIT . '</a> <a class="button" onclick="this.blur();" href="' . xtc_href_link(FILENAME_SHIPPING_STATUS, 'page=' . $page . '&oID=' . $oInfo->shipping_status_id . '&action=delete') . '">' . BUTTON_DELETE . '</a>');
                  $shipping_status_inputs_string = '';
                  $languages = xtc_get_languages();
                  for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {
                    $shipping_status_inputs_string .= '<br />' . xtc_image(DIR_WS_LANGUAGES.$languages[$i]['directory'].'/admin/images/'.$languages[$i]['image']) . '&nbsp;' . xtc_get_shipping_status_name($oInfo->shipping_status_id, $languages[$i]['id']);
                  }

                  $contents[] = array('text' => $shipping_status_inputs_string);
                }
                break;
            }

            if ( (xtc_not_null($heading)) && (xtc_not_null($contents)) ) {
              echo '            <td class="boxRight">' . "\n";
              $box = new box;
              echo $box->infoBox($heading, $contents);
              echo '            </td>' . "\n";
            }
          ?>
          </tr>
        </table>
      </td>
      <!-- body_text_eof //-->
    </tr>
  </table>
  <!-- body_eof //-->
  <!-- footer //-->
  <?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
  <!-- footer_eof //-->
  <br />
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>