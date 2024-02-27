<?php
/* -----------------------------------------------------------------------------------------
   $Id: dhl_business.php 15598 2023-11-27 13:54:03Z GTB $

   modified eCommerce Shopsoftware
   http://www.modified-shop.org

   Copyright (c) 2009 - 2013 [www.modified-shop.org]
   -----------------------------------------------------------------------------------------
   Released under the GNU General Public License 
   ---------------------------------------------------------------------------------------*/

  defined('_VALID_XTC') or die('Direct Access to this location is not allowed.');

  if (defined('MODULE_DHL_BUSINESS_STATUS') && MODULE_DHL_BUSINESS_STATUS == 'True') {
    if (isset($_GET['subaction']) && $_GET['subaction'] == 'createlabel') {
      require_once(DIR_FS_EXTERNAL.'dhl/DHLBusinessShipment.php');
      $oID = (int)$_POST['oID'];
      $dhl = new DHLBusinessShipment($_POST);
      $response = $dhl->CreateLabel($oID, false);
      
      if (is_array($response) && isset($response['message'])) {
        if (is_array($response['message'])) {
          foreach ($response['message'] as $message) {
            $messageStack->add_session(utf8_decode($message), 'warning');
          }
        } else {
          $messageStack->add_session(utf8_decode($response['message']), 'warning');
        }
      } else {
        $_SESSION['DHLparcel_id'] = $response;
        $messageStack->add_session(TEXT_DHL_BUSINESS_CREATE_SUCCESS, 'success');
        
        if ($_POST['status_update'] > 0) {
          $check_query = xtc_db_query("SELECT *
                                         FROM ".TABLE_ORDERS_TRACKING."
                                        WHERE parcel_id = '".xtc_db_input($_SESSION['DHLparcel_id'])."'
                                          AND orders_id = '".(int)$oID."'
                                          AND dhl_label_url != ''");
          if (xtc_db_num_rows($check_query) > 0) {
            $check = xtc_db_fetch_array($check_query);
            
            $order = new order($oID);
            require_once(DIR_FS_CATALOG.DIR_WS_CLASSES.'xtcPrice.php');
            $xtPrice = new xtcPrice($order->info['currency'], $order->info['status']);

            $lang_query = xtc_db_query("SELECT *
                                          FROM " . TABLE_LANGUAGES . "
                                         WHERE directory = '" . xtc_db_input($order->info['language']) . "'");
            $lang_array = xtc_db_fetch_array($lang_query);
            $lang = $lang_array['languages_id'];
            $lang_code = $lang_array['code'];

            $status = $_POST['status_update'];
            $comments = sprintf(TEXT_DHL_BUSINESS_ORDER_COMMENT, $_SESSION['DHLparcel_id']);
            $order_updated = false;
            $_POST['notify'] = 'on';
            $_POST['notify_comments'] = 'off';
            $_POST['tracking_id'] = array($check['tracking_id']);
      
            include (DIR_WS_MODULES.'orders_update.php');
        
            if ($order_updated) {
              $messageStack->add_session(SUCCESS_ORDER_UPDATED, 'success');
            }
          }
        }
      } 
      xtc_redirect(xtc_href_link(FILENAME_ORDERS, xtc_get_all_get_params(array('action', 'subaction')).'action=edit'));
    }

    if (isset($_GET['subaction']) && $_GET['subaction'] == 'deletetracking') {
      $tracking_id = (int)$_GET['tID'];
      $tracking_links_query = xtc_db_query("SELECT * 
                                              FROM ".TABLE_ORDERS_TRACKING."
                                             WHERE tracking_id = '".(int)$tracking_id."'");
      $tracking_links = xtc_db_fetch_array($tracking_links_query);
  
      require_once(DIR_FS_EXTERNAL.'dhl/DHLBusinessShipment.php');
      $dhl = new DHLBusinessShipment(array());
      $response = $dhl->DeleteLabel($tracking_links['parcel_id']);
      
      if (is_array($response) && isset($response['message'])) {
        if (is_array($response['message'])) {
          foreach ($response['message'] as $message) {
            $messageStack->add_session(utf8_decode($message), 'warning');
          }
        } else {
          $messageStack->add_session(utf8_decode($response['message']), 'warning');
        }
      } else {
        $messageStack->add_session(TEXT_DHL_BUSINESS_DELETE_SUCCESS, 'success');
      
        $check_query = xtc_db_query("SELECT *
                                       FROM ".TABLE_ORDERS_STATUS_HISTORY."
                                      WHERE orders_id = '".(int)$oID."'
                                        AND comments LIKE ('".xtc_db_input(sprintf(TEXT_DHL_BUSINESS_ORDER_COMMENT, $tracking_links['parcel_id']))."')
                                        AND customer_notified = 1
                                   ORDER BY orders_status_history_id DESC
                                      LIMIT 1");
        if (xtc_db_num_rows($check_query) > 0) {
          $check = xtc_db_fetch_array($check_query);
          
          $order = new order($oID);
          require_once(DIR_FS_CATALOG.DIR_WS_CLASSES.'xtcPrice.php');
          $xtPrice = new xtcPrice($order->info['currency'], $order->info['status']);

          $orders_status_query = xtc_db_query("SELECT *
                                                 FROM ".TABLE_ORDERS_STATUS_HISTORY."
                                                WHERE orders_id = '".(int)$oID."'
                                                  AND orders_status_history_id < '".$check['orders_status_history_id']."'
                                             ORDER BY orders_status_history_id DESC
                                                LIMIT 1");
          $orders_status = xtc_db_fetch_array($orders_status_query);

          $lang_query = xtc_db_query("SELECT *
                                        FROM " . TABLE_LANGUAGES . "
                                       WHERE directory = '" . xtc_db_input($order->info['language']) . "'");
          $lang_array = xtc_db_fetch_array($lang_query);
          $lang = $lang_array['languages_id'];
          $lang_code = $lang_array['code'];

          $status = $orders_status['orders_status_id'];
          $comments = sprintf(TEXT_DHL_BUSINESS_ORDER_COMMENT_DELETED, $tracking_links['parcel_id']);
          $order_updated = false;
          $_POST['notify'] = 'on';
          $_POST['notify_comments'] = 'on';
    
          include (DIR_WS_MODULES.'orders_update.php');
      
          if ($order_updated) {
            $messageStack->add_session(SUCCESS_ORDER_UPDATED, 'success');
          }
        }
      }
      xtc_db_query("DELETE FROM ".TABLE_ORDERS_TRACKING." WHERE tracking_id = '".(int)$tracking_id."'");
    
      xtc_redirect(xtc_href_link(FILENAME_ORDERS, xtc_get_all_get_params(array('action', 'tID', 'subaction')).'action=edit'));
    }
  }
