<?php
/* --------------------------------------------------------------
   $Id: cross_sell_groups.php 15139 2023-05-02 16:39:59Z GTB $   

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
  $cfg_max_display_results_key = 'MAX_DISPLAY_CROSS_SELL_GROUPS_RESULTS';
  $page_max_display_results = xtc_cfg_save_max_display_results($cfg_max_display_results_key);

  $action = (isset($_GET['action']) ? $_GET['action'] : '');
  $page = (isset($_GET['page']) ? (int)$_GET['page'] : 1);

  switch ($action) {
    case 'insert':
    case 'save':
      $cross_sell_id = ((isset($_GET['oID'])) ? (int)$_GET['oID'] : 0);

      $languages = xtc_get_languages();
      for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {
        $cross_sell_name_array = $_POST['cross_sell_group_name'];

        $sql_data_array = array(
          'groupname' => xtc_db_prepare_input($cross_sell_name_array[$languages[$i]['id']]),
          'xsell_sort_order' => (int)$_POST['xsell_sort_order']
        );

        if ($action == 'insert') {
          if ($cross_sell_id < 1) {
            $next_id_query = xtc_db_query("SELECT MAX(products_xsell_grp_name_id) as products_xsell_grp_name_id 
                                             FROM " . TABLE_PRODUCTS_XSELL_GROUPS);
            $next_id = xtc_db_fetch_array($next_id_query);
            $cross_sell_id = $next_id['products_xsell_grp_name_id'] + 1;
          }
          $insert_sql_data = array(
            'products_xsell_grp_name_id' => $cross_sell_id,
            'language_id' => $languages[$i]['id'],
          );
          $sql_data_array = array_merge($sql_data_array, $insert_sql_data);
          xtc_db_perform(TABLE_PRODUCTS_XSELL_GROUPS, $sql_data_array);
        } elseif ($action == 'save') {
          $cross_sell_query = xtc_db_query("SELECT * 
                                              FROM ".TABLE_PRODUCTS_XSELL_GROUPS." 
                                             WHERE language_id = '".$languages[$i]['id']."' 
                                               AND products_xsell_grp_name_id = '".$cross_sell_id."'");
          if (xtc_db_num_rows($cross_sell_query) == 0) {
            xtc_db_perform(TABLE_PRODUCTS_XSELL_GROUPS, array ('products_xsell_grp_name_id' => $cross_sell_id, 'language_id' => $languages[$i]['id']));
          }
          xtc_db_perform(TABLE_PRODUCTS_XSELL_GROUPS, $sql_data_array, 'update', "products_xsell_grp_name_id = '" . $cross_sell_id . "' and language_id = '" . $languages[$i]['id'] . "'");
        }
      }

      xtc_redirect(xtc_href_link(FILENAME_XSELL_GROUPS, 'page=' . $page . '&oID=' . $cross_sell_id));
      break;

    case 'deleteconfirm':
      $cross_sell_id = (int)$_GET['oID'];
      xtc_db_query("DELETE FROM " . TABLE_PRODUCTS_XSELL_GROUPS . " 
                          WHERE products_xsell_grp_name_id = '" . $cross_sell_id . "'");

      xtc_redirect(xtc_href_link(FILENAME_XSELL_GROUPS, 'page=' . $page));
      break;

    case 'delete':
      $cross_sell_id = (int)$_GET['oID'];
      $cross_sell_query = xtc_db_query("SELECT COUNT(*) as count 
                                          FROM " . TABLE_PRODUCTS_XSELL . " 
                                         WHERE products_xsell_grp_name_id = '" . $cross_sell_id . "'");
      $cross_sell = xtc_db_fetch_array($cross_sell_query);

      $remove_group = true;
      if ($cross_sell['count'] > 0) {
        $remove_group = false;
        $messageStack->add(ERROR_STATUS_USED_IN_CROSS_SELLS, 'error');
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
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_XSELL_GROUP_ID; ?></td>
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_XSELL_GROUP_NAME; ?></td>
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_XSELL_GROUP_SORT_ORDER; ?></td>
                <td class="dataTableHeadingContent txta-r"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
              </tr>
              <?php
                $cross_sell_query_raw = "SELECT *
                                           FROM " . TABLE_PRODUCTS_XSELL_GROUPS . " 
                                          WHERE language_id = '" . (int)$_SESSION['languages_id'] . "' 
                                       ORDER BY xsell_sort_order, products_xsell_grp_name_id";
                $cross_sell_split = new splitPageResults($page, $page_max_display_results, $cross_sell_query_raw, $cross_sell_query_numrows, 'products_xsell_grp_name_id', 'oID');
                $cross_sell_query = xtc_db_query($cross_sell_query_raw);
                while ($cross_sell = xtc_db_fetch_array($cross_sell_query)) {
                  if ((!isset($_GET['oID']) || ($_GET['oID'] == $cross_sell['products_xsell_grp_name_id'])) && !isset($oInfo) && (substr($action, 0, 3) != 'new')) {
                    $oInfo = new objectInfo($cross_sell);
                  }

                  if (isset($oInfo) && is_object($oInfo) && $cross_sell['products_xsell_grp_name_id'] == $oInfo->products_xsell_grp_name_id) {
                    echo '<tr class="dataTableRowSelected" onmouseover="this.style.cursor=\'pointer\'" onclick="document.location.href=\'' . xtc_href_link(FILENAME_XSELL_GROUPS, 'page=' . $page . '&oID=' . $oInfo->products_xsell_grp_name_id . '&action=edit') . '\'">' . "\n";
                  } else {
                    echo '<tr class="dataTableRow" onmouseover="this.className=\'dataTableRowOver\';this.style.cursor=\'pointer\'" onmouseout="this.className=\'dataTableRow\'" onclick="document.location.href=\'' . xtc_href_link(FILENAME_XSELL_GROUPS, 'page=' . $page . '&oID=' . $cross_sell['products_xsell_grp_name_id']) . '\'">' . "\n";
                  }

                  echo '<td class="dataTableContent">' . $cross_sell['products_xsell_grp_name_id'] . '</td>' . "\n";
                  echo '<td class="dataTableContent">' . $cross_sell['groupname'] . '</td>' . "\n";
                  echo '<td class="dataTableContent">' . $cross_sell['xsell_sort_order'] . '</td>' . "\n";
                  
              ?>
                <td class="dataTableContent" align="right"><?php if (isset($oInfo) && is_object($oInfo) && $cross_sell['products_xsell_grp_name_id'] == $oInfo->products_xsell_grp_name_id) { echo xtc_image(DIR_WS_IMAGES . 'icon_arrow_right.gif', ICON_ARROW_RIGHT); } else { echo '<a href="' . xtc_href_link(FILENAME_XSELL_GROUPS, 'page=' . $page . '&oID=' . $cross_sell['products_xsell_grp_name_id']) . '">' . xtc_image(DIR_WS_IMAGES . 'icon_arrow_grey.gif', IMAGE_ICON_INFO) . '</a>'; } ?>&nbsp;</td>
              </tr>
              <?php
                }
              ?>
              </table>
              <div class="smallText pdg2 flt-l"><?php echo $cross_sell_split->display_count($cross_sell_query_numrows, $page_max_display_results, $page, TEXT_DISPLAY_NUMBER_OF_XSELL_GROUP); ?></div>
              <div class="smallText pdg2 flt-r"><?php echo $cross_sell_split->display_links($cross_sell_query_numrows, $page_max_display_results, MAX_DISPLAY_PAGE_LINKS, $page); ?></div>
              <?php echo draw_input_per_page($PHP_SELF,$cfg_max_display_results_key,$page_max_display_results); ?>

              <?php
              if (substr($action, 0, 3) != 'new') {
                ?>
                <div class="clear"></div>
                <div class="pdg2 flt-r smallText"><?php echo '<a class="button" onclick="this.blur();" href="' . xtc_href_link(FILENAME_XSELL_GROUPS, 'page=' . $page . '&action=new') . '">' . BUTTON_INSERT . '</a>'; ?></div>
                <?php
              }
              ?>
            </td>
          <?php
            $heading = array();
            $contents = array();
            switch ($action) {
              case 'new':
                $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_NEW_XSELL_GROUP . '</b>');

                $contents = array('form' => xtc_draw_form('status', FILENAME_XSELL_GROUPS, 'page=' . $page . '&action=insert'));
                $contents[] = array('text' => TEXT_INFO_INSERT_INTRO);

                $cross_sell_inputs_string = '';
                $languages = xtc_get_languages();
                for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {
                  $cross_sell_inputs_string .= '<br />' . xtc_image(DIR_WS_LANGUAGES.$languages[$i]['directory'].'/admin/images/'.$languages[$i]['image']) . '&nbsp;' . xtc_draw_input_field('cross_sell_group_name[' . $languages[$i]['id'] . ']');
                }

                $contents[] = array('text' => '<br />' . TEXT_INFO_XSELL_GROUP_NAME . $cross_sell_inputs_string);
                $contents[] = array('text' => '<br />' . TEXT_INFO_XSELL_GROUP_SORT_ORDER . '<br />' . xtc_draw_input_field('xsell_sort_order'));
                $contents[] = array('align' => 'center', 'text' => '<br /><input type="submit" class="button" onclick="this.blur();" value="' . BUTTON_INSERT . '"/> <a class="button" onclick="this.blur();" href="' . xtc_href_link(FILENAME_XSELL_GROUPS, 'page=' . $page) . '">' . BUTTON_CANCEL . '</a>');
                break;

              case 'edit':
                $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_EDIT_XSELL_GROUP . '</b>');

                $contents = array('form' => xtc_draw_form('status', FILENAME_XSELL_GROUPS, 'page=' . $page . '&oID=' . $oInfo->products_xsell_grp_name_id  . '&action=save'));
                $contents[] = array('text' => TEXT_INFO_EDIT_INTRO);

                $cross_sell_inputs_string = '';
                $languages = xtc_get_languages();
                for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {
                  $cross_sell_inputs_string .= '<br />' . xtc_image(DIR_WS_LANGUAGES.$languages[$i]['directory'].'/admin/images/'.$languages[$i]['image']) . '&nbsp;' . xtc_draw_input_field('cross_sell_group_name[' . $languages[$i]['id'] . ']', xtc_get_cross_sell_name($oInfo->products_xsell_grp_name_id, $languages[$i]['id']));
                }

                $contents[] = array('text' => '<br />' . TEXT_INFO_XSELL_GROUP_NAME . $cross_sell_inputs_string);
                $contents[] = array('text' => '<br />' . TEXT_INFO_XSELL_GROUP_SORT_ORDER . '<br />' . xtc_draw_input_field('xsell_sort_order', $oInfo->xsell_sort_order));
                
                $contents[] = array('align' => 'center', 'text' => '<br /><input type="submit" class="button" onclick="this.blur();" value="' . BUTTON_UPDATE . '"/> <a class="button" onclick="this.blur();" href="' . xtc_href_link(FILENAME_XSELL_GROUPS, 'page=' . $page . '&oID=' . $oInfo->products_xsell_grp_name_id) . '">' . BUTTON_CANCEL . '</a>');
                break;

              case 'delete':
                $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_DELETE_XSELL_GROUP . '</b>');

                $contents = array('form' => xtc_draw_form('status', FILENAME_XSELL_GROUPS, 'page=' . $page . '&oID=' . $oInfo->products_xsell_grp_name_id  . '&action=deleteconfirm'));
                $contents[] = array('text' => TEXT_INFO_DELETE_INTRO);
                $contents[] = array('text' => '<br /><b>' . $oInfo->groupname . '</b>');
                if ($remove_group) $contents[] = array('align' => 'center', 'text' => '<br /><input type="submit" class="button" onclick="this.blur();" value="' . BUTTON_DELETE . '"/> <a class="button" onclick="this.blur();" href="' . xtc_href_link(FILENAME_XSELL_GROUPS, 'page=' . $page . '&oID=' . $oInfo->products_xsell_grp_name_id) . '">' . BUTTON_CANCEL . '</a>');
                break;

              default:
                if (isset($oInfo) && is_object($oInfo)) {
                  $heading[] = array('text' => '<b>' . $oInfo->groupname . '</b>');

                  $contents[] = array('align' => 'center', 'text' => '<a class="button" onclick="this.blur();" href="' . xtc_href_link(FILENAME_XSELL_GROUPS, 'page=' . $page . '&oID=' . $oInfo->products_xsell_grp_name_id . '&action=edit') . '">' . BUTTON_EDIT . '</a> <a class="button" onclick="this.blur();" href="' . xtc_href_link(FILENAME_XSELL_GROUPS, 'page=' . $page . '&oID=' . $oInfo->products_xsell_grp_name_id . '&action=delete') . '">' . BUTTON_DELETE . '</a>');

                  $cross_sell_inputs_string = '';
                  $languages = xtc_get_languages();
                  for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {
                    $cross_sell_inputs_string .= '<br />' . xtc_image(DIR_WS_LANGUAGES.$languages[$i]['directory'].'/admin/images/'.$languages[$i]['image']) . '&nbsp;' . xtc_get_cross_sell_name($oInfo->products_xsell_grp_name_id, $languages[$i]['id']);
                  }

                  $contents[] = array('text' => $cross_sell_inputs_string);
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