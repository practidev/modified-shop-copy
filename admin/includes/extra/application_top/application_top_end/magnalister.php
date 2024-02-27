<?php
/* -----------------------------------------------------------------------------------------
   $Id: magnalister.php 15667 2024-01-08 11:59:17Z GTB $

   modified eCommerce Shopsoftware
   http://www.modified-shop.org

   Copyright (c) 2009 - 2013 [www.modified-shop.org]
   -----------------------------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------------------------*/

  defined('_VALID_XTC') or die('Direct Access to this location is not allowed.');

  if (defined('MODULE_MAGNALISTER_STATUS') 
      && MODULE_MAGNALISTER_STATUS == 'True'
      && !defined('MAGNALISTER_PLUGIN') 
      && file_exists(DIR_FS_DOCUMENT_ROOT.'magnaCallback.php')
      )
  {    
    switch (basename($PHP_SELF)) {
      case FILENAME_CATEGORIES:
        ob_start();
        require_once (DIR_FS_DOCUMENT_ROOT.'magnaCallback.php');
        ob_end_clean();
        
        if (function_exists('magnaExecute')) magnaExecute('magnaInventoryUpdate', array('action' => 'inventoryUpdate'), array('inventoryUpdate.php'));
        break;

      case FILENAME_ORDERS:
        ob_start();
        require_once (DIR_FS_DOCUMENT_ROOT.'magnaCallback.php');
        ob_end_clean();
        
        if (function_exists('magnaExecute')) magnaExecute('magnaSubmitOrderStatus', array(), array('order_details.php'));
        break;
    }
  }
