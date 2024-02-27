<?php
/* --------------------------------------------------------------
   $Id: reviews.php 15186 2023-05-30 09:46:02Z GTB $   

   modified eCommerce Shopsoftware
   http://www.modified-shop.org

   Copyright (c) 2009 - 2013 [www.modified-shop.org]
   --------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(reviews.php,v 1.40 2003/03/22); www.oscommerce.com 
   (c) 2003	 nextcommerce (reviews.php,v 1.9 2003/08/18); www.nextcommerce.org

   Released under the GNU General Public License 
   --------------------------------------------------------------*/

  require('includes/application_top.php');

  //display per page
  $cfg_max_display_results_key = 'MAX_DISPLAY_REVIEWS_RESULTS';
  $page_max_display_results = xtc_cfg_save_max_display_results($cfg_max_display_results_key);

  $action = (isset($_GET['action']) ? $_GET['action'] : '');
  $page = (isset($_GET['page']) ? (int)$_GET['page'] : 1);
  
  // languages
  $lang = array();
  $languages_query = xtc_db_query("SELECT *
                                     FROM ".TABLE_LANGUAGES);
  while ($languages = xtc_db_fetch_array($languages_query)) {
    $lang[$languages['languages_id']] = $languages['name'];
  }

  if (xtc_not_null($action)) {
    switch ($action) {
      case 'setflag':
        xtc_db_query("UPDATE ".TABLE_REVIEWS."
                         SET reviews_status = '".(int)$_GET['flag']."'
                       WHERE reviews_id = '".(int)$_GET['rID']."'");
        xtc_redirect(xtc_href_link(FILENAME_REVIEWS, xtc_get_all_get_params(array('action','flag'))));
        break;
        
      case 'update':
        $reviews_rating = (int)$_POST['reviews_rating'];
        $reviews_text = xtc_db_prepare_input($_POST['reviews_text']);
        
        $sql_data_array = array(
          'reviews_rating' => $reviews_rating,
          'last_modified' => 'now()',
        );
        xtc_db_perform(TABLE_REVIEWS, $sql_data_array, 'update', "reviews_id = '".(int)$_GET['rID']."'");

        $sql_data_array = array(
          'reviews_text' => $reviews_text,
        );
        xtc_db_perform(TABLE_REVIEWS_DESCRIPTION, $sql_data_array, 'update', "reviews_id = '".(int)$_GET['rID']."'");

        xtc_redirect(xtc_href_link(FILENAME_REVIEWS, xtc_get_all_get_params(array('action'))));
        break;

      case 'deleteconfirm':
        xtc_db_query("DELETE FROM " . TABLE_REVIEWS . " WHERE reviews_id = '" . (int)$_GET['rID'] . "'");
        xtc_db_query("DELETE FROM " . TABLE_REVIEWS_DESCRIPTION . " WHERE reviews_id = '" . (int)$_GET['rID'] . "'");

        xtc_redirect(xtc_href_link(FILENAME_REVIEWS, xtc_get_all_get_params(array('action','rID'))));
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
        <div class="pageHeadingImage"><?php echo xtc_image(DIR_WS_ICONS.'page_white_star.png'); ?></div>
        <div class="flt-l">
          <div class="pageHeading pdg2"><?php echo HEADING_TITLE; ?></div>
          <div class="main pdg2"><?php echo ((isset($_GET['pID']) && $_GET['pID'] != '') ? xtc_get_products_name($_GET['pID']) : 'Products'); ?></div>
        </div>
        <?php if (isset($_GET['pID']) && $_GET['pID'] != '') { ?>
        <div class="main pdg2 flt-l" style="margin-left:100px;">
          <a class="button" href="<?php echo xtc_href_link(FILENAME_CATEGORIES, xtc_get_all_get_params(array('action', 'rID'))); ?>"><?php echo BUTTON_BACK; ?></a>
        </div>
        <?php } ?>
        <table class="tableCenter">
          <tr>
            <td class="boxCenterLeft">
              <table class="tableBoxCenter collapse">
                <tr class="dataTableHeadingRow">
                  <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_PRODUCTS; ?></td>
                  <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_CUSTOMER; ?></td>
                  <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_LANGUAGE; ?></td>
                  <td class="dataTableHeadingContent txta-c"><?php echo TABLE_HEADING_RATING; ?></td>
                  <td class="dataTableHeadingContent txta-c"><?php echo TABLE_HEADING_STATUS; ?></td>
                  <td class="dataTableHeadingContent txta-r"><?php echo TABLE_HEADING_DATE_ADDED; ?></td>
                  <td class="dataTableHeadingContent txta-r"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
                </tr>
                <?php
                $where = '';
                if (isset($_GET['pID']) && $_GET['pID'] != '') {
                  $where = " WHERE p.products_id = '".(int)$_GET['pID']."' ";
                }
                $reviews_query_raw = "SELECT r.*,
                                             rd.reviews_text,
                                             rd.languages_id,
                                             length(rd.reviews_text) as reviews_text_size,
                                             p.products_image,
                                             pd.products_name
                                        FROM ".TABLE_REVIEWS." r
                                        JOIN ".TABLE_REVIEWS_DESCRIPTION." rd 
                                             ON r.reviews_id = rd.reviews_id
                                   LEFT JOIN ".TABLE_PRODUCTS." p
                                             ON r.products_id = p.products_id
                                   LEFT JOIN ".TABLE_PRODUCTS_DESCRIPTION." pd
                                             ON r.products_id = pd.products_id
                                                AND language_id = '".(int)$_SESSION['languages_id']."'
                                             ".$where."
                                    ORDER BY r.date_added DESC";
                $reviews_split = new splitPageResults($page, $page_max_display_results, $reviews_query_raw, $reviews_query_numrows);
                $reviews_query = xtc_db_query($reviews_query_raw);
                while ($reviews = xtc_db_fetch_array($reviews_query)) {
                  if ((!isset($_GET['rID']) || ($_GET['rID'] == $reviews['reviews_id'])) && !isset($rInfo)) {
                    $reviews_average_query = xtc_db_query("SELECT (avg(reviews_rating) / 5 * 100) as average_rating 
                                                             FROM " . TABLE_REVIEWS . " 
                                                            WHERE products_id = '" . $reviews['products_id'] . "'");
                    $reviews_average = xtc_db_fetch_array($reviews_average_query);
                    $rInfo = new objectInfo(array_merge($reviews, $reviews_average));
                  }

                  if (isset($rInfo) && is_object($rInfo) && $reviews['reviews_id'] == $rInfo->reviews_id) {
                    echo '<tr class="dataTableRowSelected" onmouseover="this.style.cursor=\'pointer\'" onclick="document.location.href=\'' . xtc_href_link(FILENAME_REVIEWS, xtc_get_all_get_params(array('action', 'rID')) . 'rID=' . $rInfo->reviews_id . '&action=edit') . '\'">' . "\n";
                  } else {
                    echo '<tr class="dataTableRow" onmouseover="this.className=\'dataTableRowOver\';this.style.cursor=\'pointer\'" onmouseout="this.className=\'dataTableRow\'" onclick="document.location.href=\'' . xtc_href_link(FILENAME_REVIEWS, xtc_get_all_get_params(array('action', 'rID')) . '&rID=' . $reviews['reviews_id']) . '\'">' . "\n";
                  }
                  ?>
                    <td class="dataTableContent"><?php echo $reviews['products_name']; ?></td>
                    <td class="dataTableContent"><?php echo $reviews['customers_name']; ?></td>
                    <td class="dataTableContent"><?php echo $lang[$reviews['languages_id']]; ?></td>
                    <td class="dataTableContent txta-c" align="right"><?php echo xtc_image(DIR_WS_IMAGES.'stars_' . $reviews['reviews_rating'] . '.png'); ?></td>
                    <td class="dataTableContent txta-c">
                      <?php
                      if ($reviews['reviews_status'] == '1') {
                        echo xtc_image(DIR_WS_IMAGES . 'icon_status_green.gif', IMAGE_ICON_STATUS_GREEN, 12, 12, 'style="margin-right:5px;"') . '<a href="' . xtc_href_link(FILENAME_REVIEWS, xtc_get_all_get_params(array('action', 'rID', 'flag')) . 'action=setflag&flag=0&rID=' . $reviews['reviews_id'], 'NONSSL') . '">' . xtc_image(DIR_WS_IMAGES . 'icon_status_red_light.gif', IMAGE_ICON_STATUS_RED_LIGHT, 12, 12) . '</a>';
                      } else {
                        echo '<a href="' . xtc_href_link(FILENAME_REVIEWS, xtc_get_all_get_params(array('action', 'rID', 'flag')) . 'action=setflag&flag=1&rID=' . $reviews['reviews_id'], 'NONSSL') . '">' . xtc_image(DIR_WS_IMAGES . 'icon_status_green_light.gif', IMAGE_ICON_STATUS_GREEN_LIGHT, 12, 12, 'style="margin-right:5px;"') . '</a>' . xtc_image(DIR_WS_IMAGES . 'icon_status_red.gif', IMAGE_ICON_STATUS_RED, 12, 12);
                      }
                      ?>
                    </td>
                    <td class="dataTableContent txta-r" align="right"><?php echo xtc_date_short($reviews['date_added']); ?></td>
                    <td class="dataTableContent txta-r" align="right"><?php if (isset($rInfo) && is_object($rInfo) && $reviews['reviews_id'] == $rInfo->reviews_id) { echo xtc_image(DIR_WS_IMAGES . 'icon_arrow_right.gif', ICON_ARROW_RIGHT); } else { echo '<a href="' . xtc_href_link(FILENAME_REVIEWS, xtc_get_all_get_params(array('action', 'rID')) . '&rID=' . $reviews['reviews_id']) . '">' . xtc_image(DIR_WS_IMAGES . 'icon_arrow_grey.gif', IMAGE_ICON_INFO) . '</a>'; } ?>&nbsp;</td>
                  </tr>
                  <?php
                }
              ?>
              </table>             
              <div class="smallText pdg2 flt-l"><?php echo $reviews_split->display_count($reviews_query_numrows, $page_max_display_results, $page, TEXT_DISPLAY_NUMBER_OF_REVIEWS); ?></div>
              <div class="smallText pdg2 flt-r"><?php echo $reviews_split->display_links($reviews_query_numrows, $page_max_display_results, MAX_DISPLAY_PAGE_LINKS, $page); ?></div>
              <?php echo draw_input_per_page($PHP_SELF,$cfg_max_display_results_key,$page_max_display_results); ?>
            </td>
            <?php
            $heading = array();
            $contents = array();
            switch ($action) {
              case 'delete':
                $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_DELETE_REVIEW . '</b>');

                $contents = array('form' => xtc_draw_form('reviews', FILENAME_REVIEWS, xtc_get_all_get_params(array('action', 'rID')) . 'action=deleteconfirm&rID=' . $rInfo->reviews_id));
                $contents[] = array('text' => TEXT_INFO_DELETE_REVIEW_INTRO);
                $contents[] = array('text' => '<br /><b>' . $rInfo->products_name . '</b>');
                $contents[] = array('align' => 'center', 'text' => '<br /><input type="submit" class="button" onclick="this.blur();" value="' . BUTTON_DELETE . '"/> <a class="button" onclick="this.blur();" href="' . xtc_href_link(FILENAME_REVIEWS, xtc_get_all_get_params(array('action', 'rID')) . 'rID=' . $rInfo->reviews_id) . '">' . BUTTON_CANCEL . '</a>');
                break;

              case 'edit':
                $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_EDIT_REVIEW . '</b>');

                $contents = array('form' => xtc_draw_form('reviews', FILENAME_REVIEWS, xtc_get_all_get_params(array('action', 'rID')) . 'action=update&rID=' . $rInfo->reviews_id));
                $contents[] = array('text' => TEXT_INFO_EDIT_REVIEW_INTRO);
                $contents[] = array('text' => '<br />' . ENTRY_PRODUCT . '<br /><b>' . $rInfo->products_name . '</b>');
                $contents[] = array('text' => '<br />' . ENTRY_FROM . '<br /><b>' . $rInfo->customers_name . '</b>');
                $contents[] = array('text' => '<br />' . ENTRY_DATE . '<br /><b>' . xtc_date_short($rInfo->date_added) . '</b>');
                                    
                $reviews_rating = TEXT_BAD.'&nbsp;';
                for ($i=1; $i<=5; $i++) {
                  $reviews_rating .= xtc_draw_radio_field('reviews_rating', $i, '', $rInfo->reviews_rating).'&nbsp;';
                }
                $contents[] = array('text' => '<br />' . ENTRY_RATING . '<br />' . $reviews_rating . '&nbsp;' . TEXT_GOOD);
                $contents[] = array('text' => '<br />' . ENTRY_REVIEW . '<br />' . xtc_draw_textarea_field('reviews_text', 'soft', '60', '15', $rInfo->reviews_text, 'style="width:99%"'));
   
                $contents[] = array('align' => 'center', 'text' => '<br /><input type="submit" class="button" onclick="this.blur();" value="' . BUTTON_UPDATE . '"/> <a class="button" onclick="this.blur();" href="' . xtc_href_link(FILENAME_REVIEWS, xtc_get_all_get_params(array('action', 'rID')) . 'rID=' . $rInfo->reviews_id) . '">' . BUTTON_CANCEL . '</a>');
                break;

              default:
                if (isset($rInfo) && is_object($rInfo)) {
                  $heading[] = array('text' => '<b>' . $rInfo->products_name . '</b>');

                  $contents[] = array('align' => 'center', 'text' => '<a class="button" onclick="this.blur();" href="' . xtc_href_link(FILENAME_REVIEWS, xtc_get_all_get_params(array('action', 'rID')) . 'rID=' . $rInfo->reviews_id . '&action=edit') . '">' . BUTTON_EDIT . '</a> <a class="button" onclick="this.blur();" href="' . xtc_href_link(FILENAME_REVIEWS, xtc_get_all_get_params(array('action', 'rID')) . 'rID=' . $rInfo->reviews_id . '&action=delete') . '">' . BUTTON_DELETE . '</a>');
                  $contents[] = array('text' => '<br />' . TEXT_INFO_DATE_ADDED . ' ' . xtc_date_short($rInfo->date_added));
                  if (xtc_not_null($rInfo->last_modified)) $contents[] = array('text' => TEXT_INFO_LAST_MODIFIED . ' ' . xtc_date_short($rInfo->last_modified));
                  $contents[] = array('text' => '<br />' . xtc_product_thumb_image($rInfo->products_image, $rInfo->products_name));
                  $contents[] = array('text' => '<br />' . TEXT_INFO_REVIEW_AUTHOR . ' ' . $rInfo->customers_name);
                  $contents[] = array('text' => TEXT_INFO_REVIEW_RATING . ' ' . xtc_image(DIR_WS_IMAGES.'stars_'  . $rInfo->reviews_rating . '.png'));
                  $contents[] = array('text' => TEXT_INFO_REVIEW_READ . ' ' . $rInfo->reviews_read);
                  $contents[] = array('text' => '<br />' . TEXT_INFO_REVIEW_SIZE . ' ' . $rInfo->reviews_text_size . ' bytes');
                  $contents[] = array('text' => '<br />' . TEXT_INFO_PRODUCTS_AVERAGE_RATING . ' ' . number_format($rInfo->average_rating, 2) . '%');
                  $contents[] = array('text' => '<br><hr><br>' . ENTRY_REVIEW . '<br>' . strip_tags($rInfo->reviews_text) . '<br>');
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