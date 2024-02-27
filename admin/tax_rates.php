<?php
/* --------------------------------------------------------------
   $Id: tax_rates.php 15594 2023-11-27 13:08:32Z GTB $   

   XT-Commerce - community made shopping
   http://www.xt-commerce.com

   Copyright (c) 2003 XT-Commerce
   --------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(tax_rates.php,v 1.28 2003/03/12); www.oscommerce.com 
   (c) 2003	 nextcommerce (tax_rates.php,v 1.9 2003/08/18); www.nextcommerce.org

   Released under the GNU General Public License 
   --------------------------------------------------------------*/

  require('includes/application_top.php');

  // include needed function
  require_once(DIR_FS_INC.'parse_multi_language_value.inc.php');

  // set languages
  $languages = xtc_get_languages();

  $cfg_max_display_results_key = 'MAX_DISPLAY_TAX_RATES_RESULTS';
  $page_max_display_results = xtc_cfg_save_max_display_results($cfg_max_display_results_key);

  $action = (isset($_GET['action']) ? $_GET['action'] : '');
  $page = (isset($_GET['page']) ? (int)$_GET['page'] : 1);
  $sorting = (isset($_GET['sorting']) ? $_GET['sorting'] : '');

  if (xtc_not_null($action)) {
    switch ($action) {
      case 'insert':
        $tax_zone_id = xtc_db_prepare_input($_POST['tax_zone_id']);
        $tax_class_id = xtc_db_prepare_input($_POST['tax_class_id']);
        $tax_rate = xtc_db_prepare_input($_POST['tax_rate']);
        $tax_priority = xtc_db_prepare_input($_POST['tax_priority']);
        $tax_description = xtc_db_prepare_input($_POST['tax_description']);
        
        $tax_description_array = array();
        foreach ($tax_description as $key => $value) {
          if (xtc_not_null($value)) {
            $tax_description_array[] =  $key . '::' . $value;
          }
        }
        $tax_description = implode('||', $tax_description_array);

        $sql_data_array = array(
          'tax_zone_id' => $tax_zone_id, 
          'tax_class_id' => $tax_class_id, 
          'tax_rate' => $tax_rate, 
          'tax_description' => $tax_description, 
          'tax_priority' => $tax_priority, 
          'date_added' => 'now()'
        );
        xtc_db_perform(TABLE_TAX_RATES, $sql_data_array);
        $tax_rates_id = xtc_db_insert_id();
        
        xtc_redirect(xtc_href_link(FILENAME_TAX_RATES, 'page=' . $page . '&tID=' . $tax_rates_id));
        break;

      case 'save':
        $tax_rates_id = xtc_db_prepare_input($_GET['tID']);
        $tax_zone_id = xtc_db_prepare_input($_POST['tax_zone_id']);
        $tax_class_id = xtc_db_prepare_input($_POST['tax_class_id']);
        $tax_rate = xtc_db_prepare_input($_POST['tax_rate']);
        $tax_priority = xtc_db_prepare_input($_POST['tax_priority']);
        $tax_description = xtc_db_prepare_input($_POST['tax_description']);

        $tax_description_array = array();
        foreach ($tax_description as $key => $value) {
          if (xtc_not_null($value)) {
            $tax_description_array[] =  $key . '::' . $value;
          }
        }
        $tax_description = implode('||', $tax_description_array);
        
        $sql_data_array = array(
          'tax_zone_id' => $tax_zone_id, 
          'tax_class_id' => $tax_class_id, 
          'tax_rate' => $tax_rate, 
          'tax_description' => $tax_description, 
          'tax_priority' => $tax_priority, 
          'last_modified' => 'now()'
        );        
        xtc_db_perform(TABLE_TAX_RATES, $sql_data_array, 'update', "tax_rates_id = '" . (int)$tax_rates_id . "'");

        xtc_redirect(xtc_href_link(FILENAME_TAX_RATES, 'page=' . $page . '&tID=' . $tax_rates_id));
        break;

      case 'deleteconfirm':
        $tax_rates_id = xtc_db_prepare_input($_GET['tID']);

        xtc_db_query("delete from " . TABLE_TAX_RATES . " where tax_rates_id = '" . xtc_db_input($tax_rates_id) . "'");
        xtc_redirect(xtc_href_link(FILENAME_TAX_RATES, 'page=' . $page));
        break;
    }
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
                  <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_TAX_RATE_PRIORITY.xtc_sorting(FILENAME_TAX_RATES, 'prio'); ?></td>
                  <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_TAX_CLASS_TITLE.xtc_sorting(FILENAME_TAX_RATES, 'title'); ?></td>
                  <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_ZONE.xtc_sorting(FILENAME_TAX_RATES, 'name'); ?></td>
                  <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_TAX_RATE.xtc_sorting(FILENAME_TAX_RATES, 'tax'); ?></td>
                  <td class="dataTableHeadingContent txta-r"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
                </tr>
                  <?php
                    switch ($sorting) {
                      case 'prio':
                        $tsort = 'tr.tax_priority ASC';
                        break;
                      case 'prio-desc':
                        $tsort = 'tr.tax_priority DESC';
                        break;
                      case 'title':
                        $tsort = 'tc.tax_class_title ASC';
                        break;
                      case 'title-desc':
                        $tsort = 'tc.tax_class_title DESC';
                        break;
                      case 'name':
                        $tsort = 'gz.geo_zone_name ASC';
                        break;
                      case 'name-desc':
                        $tsort = 'gz.geo_zone_name DESC';
                        break;
                      case 'tax':
                        $tsort = 'tr.tax_rate ASC';
                        break;
                      case 'tax-desc':
                        $tsort = 'tr.tax_rate DESC';
                        break;
                      default:
                        $tsort = 'tr.tax_rates_id ASC';
                        break;
                    }
                    $tsort .= ', tr.tax_rates_id ASC';
                    
                    $rates_query_raw = "SELECT tr.*, 
                                               gz.geo_zone_id, 
                                               gz.geo_zone_name, 
                                               tc.tax_class_title, 
                                               tc.tax_class_id
                                          FROM " . TABLE_TAX_CLASS . " tc
                                          JOIN " . TABLE_TAX_RATES . " tr 
                                               ON tr.tax_class_id = tc.tax_class_id
                                     LEFT JOIN " . TABLE_GEO_ZONES . " gz 
                                               ON tr.tax_zone_id = gz.geo_zone_id
                                         ORDER BY ".$tsort;
                    $rates_split = new splitPageResults($page, $page_max_display_results, $rates_query_raw, $rates_query_numrows, 'tr.tax_rates_id', 'tID');
                    $rates_query = xtc_db_query($rates_query_raw);
                    while ($rates = xtc_db_fetch_array($rates_query)) {
                      if ((!isset($_GET['tID']) || $_GET['tID'] == $rates['tax_rates_id']) && !isset($trInfo) && (substr($action, 0, 3) != 'new')) {
                        $trInfo = new objectInfo($rates);
                      }

                      if (isset($trInfo) && is_object($trInfo) && $rates['tax_rates_id'] == $trInfo->tax_rates_id) {
                        echo '<tr class="dataTableRowSelected" onmouseover="this.style.cursor=\'pointer\'" onclick="document.location.href=\'' . xtc_href_link(FILENAME_TAX_RATES, xtc_get_all_get_params(array('action', 'tID')) . 'tID=' . $trInfo->tax_rates_id . '&action=edit') . '\'">' . "\n";
                      } else {
                        echo '<tr class="dataTableRow" onmouseover="this.className=\'dataTableRowOver\';this.style.cursor=\'pointer\'" onmouseout="this.className=\'dataTableRow\'" onclick="document.location.href=\'' . xtc_href_link(FILENAME_TAX_RATES, xtc_get_all_get_params(array('action', 'tID')) . 'tID=' . $rates['tax_rates_id']) . '\'">' . "\n";
                      }
                  ?>
                  <td class="dataTableContent"><?php echo $rates['tax_priority']; ?></td>
                  <td class="dataTableContent"><?php echo parse_multi_language_value($rates['tax_class_title'], $_SESSION['language_code']); ?></td>
                  <td class="dataTableContent"><?php echo parse_multi_language_value($rates['geo_zone_name'], $_SESSION['language_code']); ?></td>
                  <td class="dataTableContent"><?php echo xtc_display_tax_value($rates['tax_rate']); ?>%</td>
                  <td class="dataTableContent txta-r"><?php if (isset($trInfo) && is_object($trInfo) && $rates['tax_rates_id'] == $trInfo->tax_rates_id) { echo xtc_image(DIR_WS_IMAGES . 'icon_arrow_right.gif', ICON_ARROW_RIGHT); } else { echo '<a href="' . xtc_href_link(FILENAME_TAX_RATES, xtc_get_all_get_params(array('action', 'tID')) . 'tID=' . $rates['tax_rates_id']) . '">' . xtc_image(DIR_WS_IMAGES . 'icon_arrow_grey.gif', IMAGE_ICON_INFO) . '</a>'; } ?>&nbsp;</td>
                </tr>
                <?php
                  }
                ?>
              </table>
                
              <div class="smallText pdg2 flt-l"><?php echo $rates_split->display_count($rates_query_numrows, $page_max_display_results, $page, TEXT_DISPLAY_NUMBER_OF_TAX_RATES); ?></div> 
              <div class="smallText pdg2 flt-r"><?php echo $rates_split->display_links($rates_query_numrows, $page_max_display_results, MAX_DISPLAY_PAGE_LINKS, $page, xtc_get_all_get_params(array('page', 'tID'))); ?></div> 
              <?php echo draw_input_per_page($PHP_SELF, $cfg_max_display_results_key, $page_max_display_results); ?>

              <?php
              if (!xtc_not_null($action)) {
              ?>
                <div class="clear"></div>   
                <div class="smallText pdg2 flt-r"><?php echo '<a class="button" onclick="this.blur();" href="' . xtc_href_link(FILENAME_TAX_RATES, xtc_get_all_get_params(array('action', 'tID')) . 'action=new') . '">' . BUTTON_NEW_TAX_RATE . '</a>'; ?></div> 
              <?php
              }
              ?>
            </td>
            <?php
              $heading = array();
              $contents = array();
              switch ($action) {
                case 'new':
                  $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_NEW_TAX_RATE . '</b>');

                  $contents = array('form' => xtc_draw_form('rates', FILENAME_TAX_RATES, xtc_get_all_get_params(array('action', 'tID')) . '&action=insert'));
                  $contents[] = array('text' => TEXT_INFO_INSERT_INTRO);
                  $contents[] = array('text' => '<br />' . TEXT_INFO_CLASS_TITLE . '<br />' . xtc_tax_classes_pull_down('name="tax_class_id" style="font-size:12px" class="SlectBox"'));
                  $contents[] = array('text' => '<br />' . TEXT_INFO_ZONE_NAME . '<br />' . xtc_geo_zones_pull_down('name="tax_zone_id" style="font-size:12px" class="SlectBox"'));
                  $contents[] = array('text' => '<br />' . TEXT_INFO_TAX_RATE . '<br />' . xtc_draw_input_field('tax_rate'));

                  $description = '';
                  for ($i=0, $n=count($languages); $i<$n; $i++) {
                    $description .= xtc_image(DIR_WS_LANGUAGES . $languages[$i]['directory'] .'/admin/images/'. $languages[$i]['image'], $languages[$i]['name'], '18px');
                    $description .= xtc_draw_input_field('tax_description[' . strtoupper($languages[$i]['code']) . ']', '', 'style="margin-left:2px; width:200px"').'<br>';
                  }
                  $contents[] = array('text' => '<br />' . TEXT_INFO_RATE_DESCRIPTION . '<br />' . $description);

                  $contents[] = array('text' => '<br />' . TEXT_INFO_TAX_RATE_PRIORITY . '<br />' . xtc_draw_input_field('tax_priority'));
                  $contents[] = array('align' => 'center', 'text' => '<br /><input type="submit" class="button" onclick="this.blur();" value="' . BUTTON_INSERT . '"/>&nbsp;<a class="button" onclick="this.blur();" href="' . xtc_href_link(FILENAME_TAX_RATES, xtc_get_all_get_params(array('action', 'tID'))) . '">' . BUTTON_CANCEL . '</a>');
                  break;

                case 'edit':
                  $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_EDIT_TAX_RATE . '</b>');

                  $contents = array('form' => xtc_draw_form('rates', FILENAME_TAX_RATES, 'page=' . $page . '&tID=' . $trInfo->tax_rates_id  . '&action=save'));
                  $contents[] = array('text' => TEXT_INFO_EDIT_INTRO);
                  $contents[] = array('text' => '<br />' . TEXT_INFO_CLASS_TITLE . '<br />' . xtc_tax_classes_pull_down('name="tax_class_id" style="font-size:12px" class="SlectBox"', $trInfo->tax_class_id));
                  $contents[] = array('text' => '<br />' . TEXT_INFO_ZONE_NAME . '<br />' . xtc_geo_zones_pull_down('name="tax_zone_id" style="font-size:12px" class="SlectBox"', $trInfo->geo_zone_id));
                  $contents[] = array('text' => '<br />' . TEXT_INFO_TAX_RATE . '<br />' . xtc_draw_input_field('tax_rate', $trInfo->tax_rate));
                                    
                  $description = '';
                  for ($i=0, $n=count($languages); $i<$n; $i++) {
                    $description .= xtc_image(DIR_WS_LANGUAGES . $languages[$i]['directory'] .'/admin/images/'. $languages[$i]['image'], $languages[$i]['name'], '18px');
                    $description .= xtc_draw_input_field('tax_description[' . strtoupper($languages[$i]['code']) . ']', parse_multi_language_value($trInfo->tax_description, $languages[$i]['code'], true), 'style="margin-left:2px; width:200px"').'<br>';
                  }
                  $contents[] = array('text' => '<br />' . TEXT_INFO_RATE_DESCRIPTION . '<br />' . $description);
                  
                  $contents[] = array('text' => '<br />' . TEXT_INFO_TAX_RATE_PRIORITY . '<br />' . xtc_draw_input_field('tax_priority', $trInfo->tax_priority));
                  $contents[] = array('align' => 'center', 'text' => '<br /><input type="submit" class="button" onclick="this.blur();" value="' . BUTTON_UPDATE . '"/>&nbsp;<a class="button" onclick="this.blur();" href="' . xtc_href_link(FILENAME_TAX_RATES, xtc_get_all_get_params(array('action', 'tID')) . 'tID=' . $trInfo->tax_rates_id) . '">' . BUTTON_CANCEL . '</a>');
                  break;

                case 'delete':
                  $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_DELETE_TAX_RATE . '</b>');

                  $contents = array('form' => xtc_draw_form('rates', FILENAME_TAX_RATES, 'page=' . $page . '&tID=' . $trInfo->tax_rates_id  . '&action=deleteconfirm'));
                  $contents[] = array('text' => TEXT_INFO_DELETE_INTRO);
                  $contents[] = array('text' => '<br /><b>' . parse_multi_language_value($trInfo->tax_class_title, $_SESSION['language_code']) . ' ' . number_format($trInfo->tax_rate, TAX_DECIMAL_PLACES) . '%</b>');
                  $contents[] = array('align' => 'center', 'text' => '<br /><input type="submit" class="button" onclick="this.blur();" value="' . BUTTON_DELETE . '"/>&nbsp;<a class="button" onclick="this.blur();" href="' . xtc_href_link(FILENAME_TAX_RATES, xtc_get_all_get_params(array('action', 'tID')) . 'tID=' . $trInfo->tax_rates_id) . '">' . BUTTON_CANCEL . '</a>');
                  break;

                default:
                  if (isset($trInfo) && is_object($trInfo)) {
                    $heading[] = array('text' => '<b>' . parse_multi_language_value($trInfo->tax_class_title, $_SESSION['language_code']) . '</b>');
                    $contents[] = array('align' => 'center', 'text' => '<a class="button" onclick="this.blur();" href="' . xtc_href_link(FILENAME_TAX_RATES, xtc_get_all_get_params(array('action', 'tID')) . 'tID=' . $trInfo->tax_rates_id . '&action=edit') . '">' . BUTTON_EDIT . '</a> <a class="button" onclick="this.blur();" href="' . xtc_href_link(FILENAME_TAX_RATES, xtc_get_all_get_params(array('action', 'tID')) . 'tID=' . $trInfo->tax_rates_id . '&action=delete') . '">' . BUTTON_DELETE . '</a>');
                    $contents[] = array('text' => '<br />' . TEXT_INFO_DATE_ADDED . ' ' . xtc_date_short($trInfo->date_added));
                    $contents[] = array('text' => '' . TEXT_INFO_LAST_MODIFIED . ' ' . xtc_date_short($trInfo->last_modified));
                    $contents[] = array('text' => '<br />' . TEXT_INFO_RATE_DESCRIPTION . '<br />' . parse_multi_language_value($trInfo->tax_description, $_SESSION['language_code']));
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