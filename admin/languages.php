<?php
  /* --------------------------------------------------------------
   $Id: languages.php 15643 2023-12-21 06:35:39Z GTB $

   modified eCommerce Shopsoftware
   http://www.modified-shop.org

   Copyright (c) 2009 - 2013 [www.modified-shop.org]
   --------------------------------------------------------------
   based on:
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(languages.php,v 1.33 2003/05/07); www.oscommerce.com
   (c) 2003 nextcommerce (languages.php,v 1.10 2003/08/18); www.nextcommerce.org
   (c) 2006 XT-Commerce (languages.php 1180 2005-08-26)

   Released under the GNU General Public License
   --------------------------------------------------------------*/

  require('includes/application_top.php');

  //display per page
  $cfg_max_display_results_key = 'MAX_DISPLAY_LANGUAGES_RESULTS';
  $page_max_display_results = xtc_cfg_save_max_display_results($cfg_max_display_results_key);

  $action = (isset($_GET['action']) ? $_GET['action'] : '');
  $page = (isset($_GET['page']) ? (int)$_GET['page'] : 1);

  if (xtc_not_null($action)) {
    switch ($action) {
      case 'setlflag':
        $lID = (int)$_GET['lID'];
        $status = (int)$_GET['flag'];
        xtc_db_query("update " . TABLE_LANGUAGES . " set status = '" . xtc_db_input($status) . "' WHERE languages_id = '" . xtc_db_input($lID) . "'");
        xtc_redirect(xtc_href_link(FILENAME_LANGUAGES, 'page=' . $page . '&lID=' . $lID));
        break;
       case 'setladminflag':
        $lID = (int)$_GET['lID'];
        $status_admin = (int)$_GET['adminflag'];
        xtc_db_query("update " . TABLE_LANGUAGES . " set status_admin = '" . xtc_db_input($status_admin) . "' WHERE languages_id = '" . xtc_db_input($lID) . "'");
        xtc_redirect(xtc_href_link(FILENAME_LANGUAGES, 'page=' . $page . '&lID=' . $lID));
        break;
      case 'insert':
        $sql_data_array = array(
            'name' => xtc_db_prepare_input($_POST['name']), 
            'code' => xtc_db_prepare_input($_POST['code']),  
            'image' => xtc_db_prepare_input($_POST['image']),  
            'directory' => xtc_db_prepare_input($_POST['directory']),  
            'sort_order' => xtc_db_prepare_input($_POST['sort_order']), 
            'language_charset' => xtc_db_prepare_input($_POST['language_charset']),
          );
        xtc_db_perform(TABLE_LANGUAGES, $sql_data_array);      
        $lID = xtc_db_insert_id();

        // create additional customers status
        $customers_status_query=xtc_db_query("SELECT DISTINCT customers_status_id
                                                FROM ".TABLE_CUSTOMERS_STATUS
                                            );
        while ($data=xtc_db_fetch_array($customers_status_query)) {
          $customers_status_data_query = xtc_db_query("SELECT *
                                                         FROM ".TABLE_CUSTOMERS_STATUS."
                                                        WHERE customers_status_id='".$data['customers_status_id']."'");
          $c_data = xtc_db_fetch_array($customers_status_data_query);
          $c_data['language_id'] = $lID;
          xtc_db_perform(TABLE_CUSTOMERS_STATUS, $c_data);
        }
        
        if (isset($_POST['default']) && $_POST['default'] == 'on') {
          xtc_db_query("UPDATE " . TABLE_CONFIGURATION . " 
                           SET configuration_value = '" . xtc_db_input($sql_data_array['code']) . "' 
                         WHERE configuration_key = 'DEFAULT_LANGUAGE'");
        }
        
        unset($_SESSION['language_charset']);
        
        xtc_redirect(xtc_href_link(FILENAME_LANGUAGES, 'page=' . $page . '&lID=' . $lID));
        break;
      case 'save':
        $lID = (int)$_GET['lID'];
       
        $sql_data_array = array(
          'name' => xtc_db_prepare_input($_POST['name']), 
          'code' => xtc_db_prepare_input($_POST['code']),  
          'image' => xtc_db_prepare_input($_POST['image']),  
          'directory' => xtc_db_prepare_input($_POST['directory']),  
          //'status' => xtc_db_prepare_input($_POST['status']),  
          'sort_order' => xtc_db_prepare_input($_POST['sort_order']), 
          'language_charset' => xtc_db_prepare_input($_POST['language_charset']),
          //'status_admin' => xtc_db_prepare_input($_POST['status_admin'])
        ); 
        xtc_db_perform(TABLE_LANGUAGES, $sql_data_array, 'update', 'languages_id = \''.$lID.'\'');        
        
        if (isset($_POST['default']) && $_POST['default'] == 'on') {
          xtc_db_query("UPDATE " . TABLE_CONFIGURATION . " 
                           SET configuration_value = '" . xtc_db_input($sql_data_array['code']) . "' 
                         WHERE configuration_key = 'DEFAULT_LANGUAGE'");
        }
        
        unset($_SESSION['language_charset']);
        
        xtc_redirect(xtc_href_link(FILENAME_LANGUAGES, 'page=' . $page . '&lID=' . $lID));
        break;
      case 'deleteconfirm':
        $lID = (int)$_GET['lID'];
        xtc_db_query("DELETE FROM " . TABLE_CATEGORIES_DESCRIPTION . " WHERE language_id = '" . $lID . "'");
        xtc_db_query("DELETE FROM " . TABLE_COUPONS_DESCRIPTION . " WHERE language_id = '" . $lID . "'");
        xtc_db_query("DELETE FROM " . TABLE_CUSTOMERS_STATUS . " WHERE language_id = '" . $lID . "'");
        xtc_db_query("DELETE FROM " . TABLE_ORDERS_STATUS . " WHERE language_id = '" . $lID . "'");
        xtc_db_query("DELETE FROM " . TABLE_PRODUCTS_DESCRIPTION . " WHERE language_id = '" . $lID . "'");
        xtc_db_query("DELETE FROM " . TABLE_PRODUCTS_OPTIONS . " WHERE language_id = '" . $lID . "'");
        xtc_db_query("DELETE FROM " . TABLE_PRODUCTS_OPTIONS_VALUES . " WHERE language_id = '" . $lID . "'");
        xtc_db_query("DELETE FROM " . TABLE_PRODUCTS_VPE . " WHERE language_id = '" . $lID . "'");
        xtc_db_query("DELETE FROM " . TABLE_PRODUCTS_XSELL_GROUPS . " WHERE language_id = '" . $lID . "'");
        xtc_db_query("DELETE FROM " . TABLE_SHIPPING_STATUS . " WHERE language_id = '" . $lID . "'");

        xtc_db_query("DELETE FROM " . TABLE_BANNERS . " WHERE languages_id = '" . $lID . "'");
        xtc_db_query("DELETE FROM " . TABLE_CONTENT_MANAGER . " WHERE languages_id = '" . $lID . "'");
        xtc_db_query("DELETE FROM " . TABLE_CONTENT_MANAGER_CONTENT . " WHERE languages_id = '" . $lID . "'");
        xtc_db_query("DELETE FROM " . TABLE_EMAIL_CONTENT . " WHERE languages_id = '" . $lID . "'");
        xtc_db_query("DELETE FROM " . TABLE_LANGUAGES . " WHERE languages_id = '" . $lID . "'");
        xtc_db_query("DELETE FROM " . TABLE_MANUFACTURERS_INFO . " WHERE languages_id = '" . $lID . "'");
        xtc_db_query("DELETE FROM " . TABLE_PRODUCTS_CONTENT . " WHERE languages_id = '" . $lID . "'");
        xtc_db_query("DELETE FROM " . TABLE_PRODUCTS_TAGS_OPTIONS . " WHERE languages_id = '" . $lID . "'");
        xtc_db_query("DELETE FROM " . TABLE_PRODUCTS_TAGS_VALUES . " WHERE languages_id = '" . $lID . "'");

        unset($_SESSION['language_charset']);

        xtc_redirect(xtc_href_link(FILENAME_LANGUAGES, 'page=' . $page));
        break;
      case 'delete':
        $lID = (int)$_GET['lID'];
        $lng_query = xtc_db_query("SELECT code 
                                     FROM " . TABLE_LANGUAGES . " 
                                    WHERE languages_id = '" . $lID . "'");
        $lng = xtc_db_fetch_array($lng_query);
        $remove_language = true;
        if ($lng['code'] == DEFAULT_LANGUAGE) {
          $remove_language = false;
          $messageStack->add(ERROR_REMOVE_DEFAULT_LANGUAGE, 'error');
        }
        unset($lng);
        break;
      case 'transfer':
        $lngID_from = (int)$_POST['lngID_from'];
        $lngID_to =(int)$_POST['lngID_to'];
        
        if ($lngID_from != $lngID_to) {
          // create additional categories_description records
          if (isset($_POST['c_desc'])) {
            xtc_db_query("DELETE FROM " . TABLE_CATEGORIES_DESCRIPTION . " WHERE language_id = '" . $lngID_to . "'");
            $categories_query = xtc_db_query("SELECT cd.* 
                                                FROM " . TABLE_CATEGORIES . " c 
                                           LEFT JOIN " . TABLE_CATEGORIES_DESCRIPTION . " cd 
                                                     ON c.categories_id = cd.categories_id 
                                               WHERE cd.language_id = '" . $lngID_from . "'");
            while ($categories = xtc_db_fetch_array($categories_query)) {
              $sql_data_array = $categories;
              $sql_data_array['language_id'] = $lngID_to;
              xtc_db_perform(TABLE_CATEGORIES_DESCRIPTION,$sql_data_array);
            }
          }
          // create additional products_description records
          if (isset($_POST['p_desc'])) {
            xtc_db_query("DELETE FROM " . TABLE_PRODUCTS_DESCRIPTION . " WHERE language_id = '" . $lngID_to . "'");
            $products_query = xtc_db_query("SELECT pd.* 
                                              FROM " . TABLE_PRODUCTS . " p 
                                         LEFT JOIN " . TABLE_PRODUCTS_DESCRIPTION . " pd 
                                                   ON p.products_id = pd.products_id 
                                             WHERE pd.language_id = '" . $lngID_from . "'");
            while ($products = xtc_db_fetch_array($products_query)) {
              $sql_data_array = $products;
              $sql_data_array['language_id'] = $lngID_to;
              xtc_db_perform(TABLE_PRODUCTS_DESCRIPTION,$sql_data_array);
            }
          }
          // create additional products_images_description records
          if (isset($_POST['p_imgdesc'])) {
            xtc_db_query("DELETE FROM " . TABLE_PRODUCTS_IMAGES_DESCRIPTION . " WHERE language_id = '" . $lngID_to . "'");
            $products_query = xtc_db_query("SELECT pid.* 
                                              FROM " . TABLE_PRODUCTS . " p 
                                         LEFT JOIN " . TABLE_PRODUCTS_IMAGES_DESCRIPTION . " pid 
                                                   ON p.products_id = pid.products_id 
                                             WHERE pid.language_id = '" . $lngID_from . "'");
            while ($products = xtc_db_fetch_array($products_query)) {
              $sql_data_array = $products;
              $sql_data_array['language_id'] = $lngID_to;
              xtc_db_perform(TABLE_PRODUCTS_IMAGES_DESCRIPTION,$sql_data_array);
            }
          }
          // create additional products_options records
          if (isset($_POST['p_opt'])) {
            xtc_db_query("DELETE FROM " . TABLE_PRODUCTS_OPTIONS . " WHERE language_id = '" . $lngID_to . "'");
            $products_options_query = xtc_db_query("SELECT * 
                                                      FROM " . TABLE_PRODUCTS_OPTIONS . " 
                                                     WHERE language_id = '" . $lngID_from . "'");
            while ($products_options = xtc_db_fetch_array($products_options_query)) {
              $sql_data_array = $products_options;
              $sql_data_array['language_id'] = $lngID_to;
              xtc_db_perform(TABLE_PRODUCTS_OPTIONS,$sql_data_array);
            }
          }
          // create additional products_options_values records
          if (isset($_POST['p_opt_val'])) {
            xtc_db_query("DELETE FROM " . TABLE_PRODUCTS_OPTIONS_VALUES . " WHERE language_id = '" . $lngID_to . "'");
            $products_options_values_query = xtc_db_query("SELECT * 
                                                             FROM " . TABLE_PRODUCTS_OPTIONS_VALUES . " 
                                                            WHERE language_id = '" . $lngID_from . "'");
            while ($products_options_values = xtc_db_fetch_array($products_options_values_query)) {
              $sql_data_array = $products_options_values;
              $sql_data_array['language_id'] = $lngID_to;
              xtc_db_perform(TABLE_PRODUCTS_OPTIONS_VALUES,$sql_data_array);
            }
          }
          // create additional products_tags_options records
          if (isset($_POST['p_tags_opt'])) {
            xtc_db_query("DELETE FROM " . TABLE_PRODUCTS_TAGS_OPTIONS . " WHERE languages_id = '" . $lngID_to . "'");
            $products_options_query = xtc_db_query("SELECT * 
                                                      FROM " . TABLE_PRODUCTS_TAGS_OPTIONS . " 
                                                     WHERE languages_id = '" . $lngID_from . "'");
            while ($products_options = xtc_db_fetch_array($products_options_query)) {
              $sql_data_array = $products_options;
              $sql_data_array['languages_id'] = $lngID_to;
              xtc_db_perform(TABLE_PRODUCTS_TAGS_OPTIONS,$sql_data_array);
            }
          }
          // create additional products_tags_values records
          if (isset($_POST['p_tags_val'])) {
            xtc_db_query("DELETE FROM " . TABLE_PRODUCTS_TAGS_VALUES . " WHERE languages_id = '" . $lngID_to . "'");
            $products_options_values_query = xtc_db_query("SELECT * 
                                                             FROM " . TABLE_PRODUCTS_TAGS_VALUES . " 
                                                            WHERE languages_id = '" . $lngID_from . "'");
            while ($products_options_values = xtc_db_fetch_array($products_options_values_query)) {
              $sql_data_array = $products_options_values;
              $sql_data_array['languages_id'] = $lngID_to;
              xtc_db_perform(TABLE_PRODUCTS_TAGS_VALUES,$sql_data_array);
            }
          }
          // create additional products_vpe records
          if (isset($_POST['p_vpe'])) {
            xtc_db_query("DELETE FROM " . TABLE_PRODUCTS_VPE . " WHERE language_id = '" . $lngID_to . "'");
            $products_vpe_query = xtc_db_query("SELECT * 
                                                  FROM " . TABLE_PRODUCTS_VPE . " 
                                                 WHERE language_id = '" . $lngID_from . "'");
            while ($products_vpe = xtc_db_fetch_array($products_vpe_query)) {
              $sql_data_array = $products_vpe;
              $sql_data_array['language_id'] = $lngID_to;
              xtc_db_perform(TABLE_PRODUCTS_VPE,$sql_data_array);               
            }
          }
          // create additional manufacturers_info records
          if (isset($_POST['m_info'])) {
            xtc_db_query("DELETE FROM " . TABLE_MANUFACTURERS_INFO . " WHERE languages_id = '" . $lngID_to . "'");
            $manufacturers_query = xtc_db_query("SELECT mi.* 
                                                   FROM " . TABLE_MANUFACTURERS . " m 
                                              LEFT JOIN " . TABLE_MANUFACTURERS_INFO . " mi 
                                                        ON m.manufacturers_id = mi.manufacturers_id 
                                                  WHERE mi.languages_id = '" . $lngID_from . "'");
            while ($manufacturers = xtc_db_fetch_array($manufacturers_query)) {
              $sql_data_array = $manufacturers;
              $sql_data_array['languages_id'] = $lngID_to;
              xtc_db_perform(TABLE_MANUFACTURERS_INFO,$sql_data_array);              
            }
          }
          // create additional orders_status records
          if (isset($_POST['o_status'])) {
            xtc_db_query("DELETE FROM " . TABLE_ORDERS_STATUS . " WHERE language_id = '" . $lngID_to . "'");
            $orders_status_query = xtc_db_query("SELECT * 
                                                   FROM " . TABLE_ORDERS_STATUS . " 
                                                  WHERE language_id = '" . $lngID_from . "'");
            while ($orders_status = xtc_db_fetch_array($orders_status_query)) {
              $sql_data_array = $orders_status;
              $sql_data_array['language_id'] = $lngID_to;
              xtc_db_perform(TABLE_ORDERS_STATUS,$sql_data_array);               
            }
          }
          // create additional shipping_status records
          if (isset($_POST['s_status'])) {
            xtc_db_query("DELETE FROM " . TABLE_SHIPPING_STATUS . " WHERE language_id = '" . $lngID_to . "'");
            $shipping_status_query = xtc_db_query("SELECT * 
                                                     FROM " . TABLE_SHIPPING_STATUS . " 
                                                    WHERE language_id = '" . $lngID_from . "'");
            while ($shipping_status = xtc_db_fetch_array($shipping_status_query)) {
              $sql_data_array = $shipping_status;
              $sql_data_array['language_id'] = $lngID_to;
              xtc_db_perform(TABLE_SHIPPING_STATUS,$sql_data_array); 
            }
          }
          // create additional xsell_groups records
          if (isset($_POST['x_groups'])) {
            xtc_db_query("DELETE FROM " . TABLE_PRODUCTS_XSELL_GROUPS . " WHERE language_id = '" . $lngID_to . "'");
            $xsell_grp_query = xtc_db_query("SELECT * 
                                               FROM " . TABLE_PRODUCTS_XSELL_GROUPS . " 
                                              WHERE language_id = '" . $lngID_from . "'");
            while ($xsell_grp = xtc_db_fetch_array($xsell_grp_query)) {
              $sql_data_array = $xsell_grp;
              $sql_data_array['language_id'] = $lngID_to;
              xtc_db_perform(TABLE_PRODUCTS_XSELL_GROUPS,$sql_data_array); 
            }
          }
          // create additional content_manager records
          if (isset($_POST['c_manager'])) {
            xtc_db_query("DELETE FROM " . TABLE_CONTENT_MANAGER . " WHERE languages_id = '" . $lngID_to . "'");
            $content_manager_query = xtc_db_query("SELECT * 
                                                     FROM " . TABLE_CONTENT_MANAGER . " 
                                                    WHERE languages_id = '" . $lngID_from . "'");
            while ($content_manager = xtc_db_fetch_array($content_manager_query)) {
              $sql_data_array = $content_manager;
              $sql_data_array['languages_id'] = $lngID_to;
              unset($sql_data_array['content_id']);
              xtc_db_perform(TABLE_CONTENT_MANAGER,$sql_data_array);               
            }
          }
          // create additional product_content records
          if (isset($_POST['p_content'])) {
            xtc_db_query("DELETE FROM " . TABLE_PRODUCTS_CONTENT . " WHERE languages_id = '" . $lngID_to . "'");
            $products_content_query = xtc_db_query("SELECT * 
                                                      FROM " . TABLE_PRODUCTS_CONTENT . " 
                                                     WHERE languages_id = '" . $lngID_from . "'");
            while ($products_content = xtc_db_fetch_array($products_content_query)) {
              $sql_data_array = $products_content;
              $sql_data_array['languages_id'] = $lngID_to;
              unset($sql_data_array['content_id']);
              xtc_db_perform(TABLE_PRODUCTS_CONTENT,$sql_data_array);               
            }
          }
          // create additional content_manager_content records
          if (isset($_POST['c_content'])) {
            xtc_db_query("DELETE FROM " . TABLE_CONTENT_MANAGER_CONTENT . " WHERE languages_id = '" . $lngID_to . "'");
            $products_content_query = xtc_db_query("SELECT * 
                                                      FROM " . TABLE_CONTENT_MANAGER_CONTENT . " 
                                                     WHERE languages_id = '" . $lngID_from . "'");
            while ($products_content = xtc_db_fetch_array($products_content_query)) {
              $sql_data_array = $products_content;
              $sql_data_array['languages_id'] = $lngID_to;
              unset($sql_data_array['content_id']);
              xtc_db_perform(TABLE_CONTENT_MANAGER_CONTENT,$sql_data_array);               
            }
          }
          // create additional email_content records
          if (isset($_POST['e_content'])) {
            xtc_db_query("DELETE FROM " . TABLE_EMAIL_CONTENT . " WHERE languages_id = '" . $lngID_to . "'");
            $products_content_query = xtc_db_query("SELECT * 
                                                      FROM " . TABLE_EMAIL_CONTENT . " 
                                                     WHERE languages_id = '" . $lngID_from . "'");
            while ($products_content = xtc_db_fetch_array($products_content_query)) {
              $sql_data_array = $products_content;
              $sql_data_array['languages_id'] = $lngID_to;
              unset($sql_data_array['content_id']);
              xtc_db_perform(TABLE_EMAIL_CONTENT,$sql_data_array);               
            }
          }
          // create additional banners records
          if (isset($_POST['banners'])) {
            xtc_db_query("DELETE FROM " . TABLE_BANNERS . " WHERE languages_id = '" . $lngID_to . "'");
            $banners_query = xtc_db_query("SELECT * 
                                             FROM " . TABLE_BANNERS . " 
                                            WHERE languages_id = '" . $lngID_from . "'");
            while ($banners = xtc_db_fetch_array($banners_query)) {
              $sql_data_array = $banners;
              $sql_data_array['languages_id'] = $lngID_to;
              unset($sql_data_array['banners_id']);
              xtc_db_perform(TABLE_BANNERS,$sql_data_array);               
            }
          }
          // create additional coupons_description records
          if (isset($_POST['co_desc'])) {
            xtc_db_query("DELETE FROM " . TABLE_COUPONS_DESCRIPTION . " WHERE language_id = '" . $lngID_to . "'");
            $coupons_query = xtc_db_query("SELECT cd.* 
                                             FROM " . TABLE_COUPONS . " c 
                                        LEFT JOIN " . TABLE_COUPONS_DESCRIPTION . " cd 
                                                  ON c.coupon_id = cd.coupon_id 
                                            WHERE cd.language_id = '" . $lngID_from . "'");
            while ($coupons = xtc_db_fetch_array($coupons_query)) {
              $sql_data_array = $coupons;
              $sql_data_array['language_id'] = $lngID_to;
              xtc_db_perform(TABLE_COUPONS_DESCRIPTION,$sql_data_array);
            }
          }
          $messageStack->add_session(TEXT_LANGUAGE_TRANSFER_OK, 'success');
        } else {
          $messageStack->add_session(TEXT_LANGUAGE_TRANSFER_ERR, 'error');
        }
        xtc_redirect(xtc_href_link(FILENAME_LANGUAGES, 'page=' . $page));
        break;
    }
  }

  require (DIR_WS_INCLUDES.'head.php');
?>
<style>
.fieldset{
  border: 1px solid #a3a3a3;
  background: #F1F1F1;
}
.transfer{
  margin-top:20px;
}
</style>
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
                  <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_LANGUAGE_NAME; ?></td>
                  <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_LANGUAGE_CODE; ?></td>
                  <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_LANGUAGE_STATUS; ?></td>
                  <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_LANGUAGE_STATUS_ADMIN; ?></td>
                  <td class="dataTableHeadingContent txta-r"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
                </tr>
                <?php
                $languages_query_raw = "SELECT *
                                          FROM " . TABLE_LANGUAGES . " 
                                      ORDER BY sort_order";
                $languages_split = new splitPageResults($page, $page_max_display_results, $languages_query_raw, $languages_query_numrows, 'languages_id', 'lID');
                $languages_query = xtc_db_query($languages_query_raw);

                while ($languages = xtc_db_fetch_array($languages_query)) {
                  if ((!isset($_GET['lID']) || (isset($_GET['lID']) && ($_GET['lID'] == $languages['languages_id']))) && !isset($lInfo) && (substr($action, 0, 3) != 'new')) {
                    $lInfo = new objectInfo($languages);
                  }
                  if (isset($lInfo) && (is_object($lInfo)) && ($languages['languages_id'] == $lInfo->languages_id) ) {
                    echo '<tr class="dataTableRowSelected" onmouseover="this.style.cursor=\'pointer\'" onclick="document.location.href=\'' . xtc_href_link(FILENAME_LANGUAGES, 'page=' . $page . '&lID=' . $lInfo->languages_id . '&action=edit') . '\'">' . "\n";
                  } else {
                    echo '<tr class="dataTableRow" onmouseover="this.className=\'dataTableRowOver\';this.style.cursor=\'pointer\'" onmouseout="this.className=\'dataTableRow\'" onclick="document.location.href=\'' . xtc_href_link(FILENAME_LANGUAGES, 'page=' . $page . '&lID=' . $languages['languages_id']) . '\'">' . "\n";
                  }

                    if (DEFAULT_LANGUAGE == $languages['code']) {
                      echo '<td class="dataTableContent"><b>' . $languages['name'] . ' (' . TEXT_DEFAULT . ')</b></td>' . "\n";
                    } else {
                      echo '<td class="dataTableContent">' . $languages['name'] . '</td>' . "\n";
                    }
                    ?>
                    <td class="dataTableContent"><?php echo $languages['code']; ?></td>                            
                    <td class="dataTableContent">
                      <?php
                      if ($languages['status'] == 1) {
                        echo xtc_image(DIR_WS_IMAGES . 'icon_status_green.gif', IMAGE_ICON_STATUS_GREEN, 12, 12, 'style="margin-left: 5px;"') . '<a href="' . xtc_href_link(FILENAME_LANGUAGES, xtc_get_all_get_params(array('page', 'action', 'lID')) . 'action=setlflag&flag=0&lID=' . $languages['languages_id'] . '&page='.$page) . '">' . xtc_image(DIR_WS_IMAGES . 'icon_status_red_light.gif', IMAGE_ICON_STATUS_RED_LIGHT, 12, 12, 'style="margin-left: 5px;"') . '</a>';
                      } else {
                        echo '<a href="' . xtc_href_link(FILENAME_LANGUAGES, xtc_get_all_get_params(array('page', 'action', 'lID')) . 'action=setlflag&flag=1&lID=' . $languages['languages_id'].'&page='.$page) . '">' . xtc_image(DIR_WS_IMAGES . 'icon_status_green_light.gif', IMAGE_ICON_STATUS_GREEN_LIGHT, 12, 12, 'style="margin-left: 5px;"') . '</a>' . xtc_image(DIR_WS_IMAGES . 'icon_status_red.gif', IMAGE_ICON_STATUS_RED, 12, 12, 'style="margin-left: 5px;"');
                      }
                      ?>
                    </td>
                    <td class="dataTableContent">
                      <?php
                      if ($languages['status_admin'] == 1) {
                        echo xtc_image(DIR_WS_IMAGES . 'icon_status_green.gif', IMAGE_ICON_STATUS_GREEN, 12, 12, 'style="margin-left: 5px;"') . '<a href="' . xtc_href_link(FILENAME_LANGUAGES, xtc_get_all_get_params(array('page', 'action', 'lID')) . 'action=setladminflag&adminflag=0&lID=' . $languages['languages_id'] . '&page='.$page) . '">' . xtc_image(DIR_WS_IMAGES . 'icon_status_red_light.gif', IMAGE_ICON_STATUS_RED_LIGHT, 12, 12, 'style="margin-left: 5px;"') . '</a>';
                      } else {
                        echo '<a href="' . xtc_href_link(FILENAME_LANGUAGES, xtc_get_all_get_params(array('page', 'action', 'lID')) . 'action=setladminflag&adminflag=1&lID=' . $languages['languages_id'].'&page='.$page) . '">' . xtc_image(DIR_WS_IMAGES . 'icon_status_green_light.gif', IMAGE_ICON_STATUS_GREEN_LIGHT, 12, 12, 'style="margin-left: 5px;"') . '</a>' . xtc_image(DIR_WS_IMAGES . 'icon_status_red.gif', IMAGE_ICON_STATUS_RED, 12, 12, 'style="margin-left: 5px;"');
                      }
                      ?>
                    </td>                            
                    <td class="dataTableContent txta-r"><?php if (isset($lInfo) && (is_object($lInfo)) && ($languages['languages_id'] == $lInfo->languages_id) ) { echo xtc_image(DIR_WS_IMAGES . 'icon_arrow_right.gif', ICON_ARROW_RIGHT); } else { echo '<a href="' . xtc_href_link(FILENAME_LANGUAGES, 'page=' . $page . '&lID=' . $languages['languages_id']) . '">' . xtc_image(DIR_WS_IMAGES . 'icon_arrow_grey.gif', IMAGE_ICON_INFO) . '</a>'; } ?>&nbsp;</td>
                  </tr>
                  <?php
                }
                ?>                                                
              </table>
                          
              <div class="smallText pdg2 flt-l"><?php echo $languages_split->display_count($languages_query_numrows, $page_max_display_results, $page, TEXT_DISPLAY_NUMBER_OF_LANGUAGES); ?></div>
              <div class="smallText pdg2 flt-r"><?php echo $languages_split->display_links($languages_query_numrows, $page_max_display_results, MAX_DISPLAY_PAGE_LINKS, $page); ?></div>
              <?php echo draw_input_per_page($PHP_SELF,$cfg_max_display_results_key,$page_max_display_results); ?>
             
              <?php
              if (empty($action)) {
                ?>
                <div class="clear"></div>                        
                <div class="smallText pdg2 flt-r"><?php echo '<a class="button" onclick="this.blur();" href="' . xtc_href_link(FILENAME_LANGUAGES, 'page=' . $page . ((isset($lInfo)) ? '&lID=' . $lInfo->languages_id : '') . '&action=new') . '">' . BUTTON_NEW_LANGUAGE . '</a>'; ?></div>
                
                <div class="clear"></div>                
                <div class="transfer main">
                <?php 
                    echo xtc_draw_form('languages', FILENAME_LANGUAGES, 'action=transfer', 'post', 'onsubmit="return confirmSubmit(\'\',\''. TEXT_LANGUAGE_TRANSFER_BTN .' ?\',this)"').PHP_EOL; 
                    echo '<fieldset class="fieldset">'.PHP_EOL;
                    echo '<legend><b>'. TEXT_LANGUAGE_TRANSFER_INFO . '</b></legend>'.PHP_EOL;
                    $lng_query = xtc_db_query("SELECT languages_id, name FROM ".TABLE_LANGUAGES."  ORDER BY sort_order");
                    while ($lng = xtc_db_fetch_array($lng_query)) {
                      $lng_array[] = array ('id' => $lng['languages_id'], 'text' => $lng['name']);
                    }
                    echo '<div class="mrg5">'. xtc_draw_checkbox_field('c_desc', '1', false) . ' ' . TABLE_CATEGORIES_DESCRIPTION . '</div>'.PHP_EOL;
                    echo '<div class="mrg5">'. xtc_draw_checkbox_field('p_desc', '1', false) . ' ' . TABLE_PRODUCTS_DESCRIPTION . '</div>'.PHP_EOL;
                    echo '<div class="mrg5">'. xtc_draw_checkbox_field('p_imgdesc', '1', false) . ' ' . TABLE_PRODUCTS_IMAGES_DESCRIPTION . '</div>'.PHP_EOL;
                    echo '<div class="mrg5">'. xtc_draw_checkbox_field('p_opt', '1', false) . ' ' . TABLE_PRODUCTS_OPTIONS . '</div>'.PHP_EOL;
                    echo '<div class="mrg5">'. xtc_draw_checkbox_field('p_opt_val', '1', false) . ' ' . TABLE_PRODUCTS_OPTIONS_VALUES . '</div>'.PHP_EOL;
                    echo '<div class="mrg5">'. xtc_draw_checkbox_field('p_tags_opt', '1', false) . ' ' . TABLE_PRODUCTS_TAGS_OPTIONS . '</div>'.PHP_EOL;
                    echo '<div class="mrg5">'. xtc_draw_checkbox_field('p_tags_val', '1', false) . ' ' . TABLE_PRODUCTS_TAGS_VALUES . '</div>'.PHP_EOL;
                    echo '<div class="mrg5">'. xtc_draw_checkbox_field('p_vpe', '1', false) . ' ' . TABLE_PRODUCTS_VPE . '</div>'.PHP_EOL;
                    echo '<div class="mrg5">'. xtc_draw_checkbox_field('m_info', '1', false) . ' ' . TABLE_MANUFACTURERS_INFO . '</div>'.PHP_EOL;
                    echo '<div class="mrg5">'. xtc_draw_checkbox_field('o_status', '1', false) . ' ' . TABLE_ORDERS_STATUS . '</div>'.PHP_EOL;
                    echo '<div class="mrg5">'. xtc_draw_checkbox_field('s_status', '1', false) . ' ' . TABLE_SHIPPING_STATUS . '</div>'.PHP_EOL;
                    echo '<div class="mrg5">'. xtc_draw_checkbox_field('x_groups', '1', false) . ' ' . TABLE_PRODUCTS_XSELL_GROUPS . '</div>'.PHP_EOL;
                    echo '<div class="mrg5">'. xtc_draw_checkbox_field('c_manager', '1', false) . ' ' . TABLE_CONTENT_MANAGER . '</div>'.PHP_EOL;
                    echo '<div class="mrg5">'. xtc_draw_checkbox_field('p_content', '1', false) . ' ' . TABLE_PRODUCTS_CONTENT . '</div>'.PHP_EOL;
                    echo '<div class="mrg5">'. xtc_draw_checkbox_field('c_content', '1', false) . ' ' . TABLE_CONTENT_MANAGER_CONTENT . '</div>'.PHP_EOL;
                    echo '<div class="mrg5">'. xtc_draw_checkbox_field('e_content', '1', false) . ' ' . TABLE_EMAIL_CONTENT . '</div>'.PHP_EOL;
                    echo '<div class="mrg5">'. xtc_draw_checkbox_field('banners', '1', false) . ' ' . TABLE_BANNERS . '</div>'.PHP_EOL;
                    echo '<div class="mrg5">'. xtc_draw_checkbox_field('co_desc', '1', false) . ' ' . TABLE_COUPONS_DESCRIPTION . '</div>'.PHP_EOL;
                    echo '<br />'.PHP_EOL;
                    echo '<div class="main important_info mrg5">'.TEXT_LANGUAGE_TRANSFER_INFO2.'</div>';
                    echo '<br />'.PHP_EOL;
                    echo '<div class="mrg5 smallText">'.TEXT_LANGUAGE_TRANSFER_FROM.xtc_draw_pull_down_menu('lngID_from', $lng_array, '' , 'style="width: 135px"').PHP_EOL;
                    echo TEXT_LANGUAGE_TRANSFER_TO. xtc_draw_pull_down_menu('lngID_to', $lng_array, '' , 'style="width: 135px"').PHP_EOL;
                    echo '<input type="submit" class="button" value="' . TEXT_LANGUAGE_TRANSFER_BTN . '" />'.PHP_EOL;
                    echo '</div>'.PHP_EOL;
                    echo '</fieldset>'.PHP_EOL;
                    echo '</form>'.PHP_EOL;
                ?>
                </div>
                <?php
              }
              ?>
            </td>
            <?php
            $heading = array();
            $contents = array();
            switch ($action) {
              case 'new':
                $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_NEW_LANGUAGE . '</b>');
                $contents = array('form' => xtc_draw_form('languages', FILENAME_LANGUAGES, 'action=insert'));
                $contents[] = array('text' => TEXT_INFO_INSERT_INTRO);
                $contents[] = array('text' => '<br />' . TEXT_INFO_LANGUAGE_NAME . '<br />' . xtc_draw_input_field('name'));
                $contents[] = array('text' => '<br />' . TEXT_INFO_LANGUAGE_CODE . '<br />' . xtc_draw_input_field('code'));
                $contents[] = array('text' => '<br />' . TEXT_INFO_LANGUAGE_CHARSET . '<br />' . xtc_draw_input_field('language_charset'));
                $contents[] = array('text' => '<br />' . TEXT_INFO_LANGUAGE_IMAGE . '<br />' . xtc_draw_input_field('image', 'icon.gif'));
                $contents[] = array('text' => '<br />' . TEXT_INFO_LANGUAGE_DIRECTORY . '<br />' . xtc_draw_input_field('directory'));
                $contents[] = array('text' => '<br />' . TEXT_INFO_LANGUAGE_SORT_ORDER . '<br />' . xtc_draw_input_field('sort_order'));
                $contents[] = array('text' => '<br />' . xtc_draw_checkbox_field('default') . ' ' . TEXT_SET_DEFAULT);
                $contents[] = array('align' => 'center', 'text' => '<br /><input type="submit" class="button" value="' . BUTTON_INSERT . '"/> <a class="button" onclick="this.blur();" href="' . xtc_href_link(FILENAME_LANGUAGES, 'page=' . $page . '&lID=' . (int)$_GET['lID']) . '">' . BUTTON_CANCEL . '</a>');
                break;
              case 'edit':
                $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_EDIT_LANGUAGE . '</b>');
                $contents = array('form' => xtc_draw_form('languages', FILENAME_LANGUAGES, 'page=' . $page . '&lID=' . $lInfo->languages_id . '&action=save'));
                $contents[] = array('text' => TEXT_INFO_EDIT_INTRO);
                $contents[] = array('text' => '<br />' . TEXT_INFO_LANGUAGE_NAME . '<br />' . xtc_draw_input_field('name', $lInfo->name));
                $contents[] = array('text' => '<br />' . TEXT_INFO_LANGUAGE_CODE . '<br />' . xtc_draw_input_field('code', $lInfo->code));
                $contents[] = array('text' => '<br />' . TEXT_INFO_LANGUAGE_CHARSET . '<br />' . xtc_draw_input_field('language_charset', $lInfo->language_charset));
                $contents[] = array('text' => '<br />' . TEXT_INFO_LANGUAGE_IMAGE . '<br />' . xtc_draw_input_field('image', $lInfo->image));
                $contents[] = array('text' => '<br />' . TEXT_INFO_LANGUAGE_DIRECTORY . '<br />' . xtc_draw_input_field('directory', $lInfo->directory));
                $contents[] = array('text' => '<br />' . TEXT_INFO_LANGUAGE_SORT_ORDER . '<br />' . xtc_draw_input_field('sort_order', $lInfo->sort_order));
                if (DEFAULT_LANGUAGE != $lInfo->code)
                  $contents[] = array('text' => '<br />' . xtc_draw_checkbox_field('default') . ' ' . TEXT_SET_DEFAULT);
                $contents[] = array('align' => 'center', 'text' => '<br /><input type="submit" class="button" onclick="this.blur();" value="' . BUTTON_UPDATE . '"/> <a class="button" onclick="this.blur();" href="' . xtc_href_link(FILENAME_LANGUAGES, 'page=' . $page . '&lID=' . $lInfo->languages_id) . '">' . BUTTON_CANCEL . '</a>');
                break;
              case 'delete':
                $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_DELETE_LANGUAGE . '</b>');
                $contents[] = array('text' => TEXT_INFO_DELETE_INTRO);
                $contents[] = array('text' => '<br /><b>' . $lInfo->name . '</b>');
                $contents[] = array('align' => 'center', 'text' => '<br />' . (($remove_language) ? '<a class="button" onclick="this.blur();" href="' . xtc_href_link(FILENAME_LANGUAGES, 'page=' . $page . '&lID=' . $lInfo->languages_id . '&action=deleteconfirm') . '">' . BUTTON_DELETE . '</a>' : '') . ' <a class="button" onclick="this.blur();" href="' . xtc_href_link(FILENAME_LANGUAGES, 'page=' . $page . '&lID=' . $lInfo->languages_id) . '">' . BUTTON_CANCEL . '</a>');
                break;
              default:
                if (is_object($lInfo)) {
                  $heading[] = array('text' => '<b>' . $lInfo->name . '</b>');
                  $contents[] = array('align' => 'center', 'text' => '<a class="button" onclick="this.blur();" href="' . xtc_href_link(FILENAME_LANGUAGES, 'page=' . $page . '&lID=' . $lInfo->languages_id . '&action=edit') . '">' . BUTTON_EDIT . '</a> <a class="button" onclick="this.blur();" href="' . xtc_href_link(FILENAME_LANGUAGES, 'page=' . $page . '&lID=' . $lInfo->languages_id . '&action=delete') . '">' . BUTTON_DELETE . '</a>');
                  $contents[] = array('text' => '<br />' . TEXT_INFO_LANGUAGE_NAME . ' ' . $lInfo->name);
                  $contents[] = array('text' => TEXT_INFO_LANGUAGE_CODE . ' ' . $lInfo->code);
                  $contents[] = array('text' => TEXT_INFO_LANGUAGE_CHARSET_INFO . ' ' . $lInfo->language_charset);
                  $contents[] = array('text' => 'Language-ID:' . ' ' . $lInfo->languages_id);
                  $contents[] = array('text' => '<br />' . xtc_image(DIR_WS_LANGUAGES . $lInfo->directory . '/' . $lInfo->image, $lInfo->name));
                  $contents[] = array('text' => '<br />' . TEXT_INFO_LANGUAGE_DIRECTORY . '<br />' . DIR_WS_LANGUAGES . '<b>' . $lInfo->directory . '</b>');
                  $contents[] = array('text' => '<br />' . TEXT_INFO_LANGUAGE_SORT_ORDER . ' ' . $lInfo->sort_order);
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