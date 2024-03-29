<?php
  /* --------------------------------------------------------------
   $Id: auth.php 15257 2023-06-16 14:28:16Z GTB $

   modified eCommerce Shopsoftware
   http://www.modified-shop.org

   Copyright (c) 2009 - 2013 [www.modified-shop.org]
   ----------------------------------------------------------------
   Released under the GNU General Public License
   --------------------------------------------------------------*/

  function check_auth() {
    if (!isset($_SESSION['auth']) || $_SESSION['auth'] === false) {
      if (isset($_POST) && count($_POST) > 0) {
        // include functions
        require_once(DIR_FS_INC.'auto_include.inc.php');
        require_once(DIR_WS_INCLUDES . 'database_tables.php');
  
        require_once (DIR_FS_INC.'xtc_not_null.inc.php');
        require_once (DIR_FS_INC.'xtc_validate_password.inc.php');

        // Database
        $db_type = get_mysql_type();
        require_once (DIR_FS_INC.'db_functions_'.$db_type.'.inc.php');
        require_once (DIR_FS_INC.'db_functions.inc.php');

        // make a connection to the database... now
        xtc_db_connect() or die('Unable to connect to database server!');
  
        $email_address = $_POST['email_address'];
        $password = $_POST['password'];
      
        // check if email exists
        $check_customer_query = xtc_db_query("SELECT customers_id, 
                                                     customers_password
                                                FROM ".TABLE_CUSTOMERS." 
                                               WHERE customers_email_address = '".xtc_db_input($email_address)."' 
                                                 AND customers_status = '0'
                                                 AND account_type = '0'");

        if (xtc_db_num_rows($check_customer_query) > 0) {
          // change password field
          xtc_db_query("ALTER TABLE ".TABLE_CUSTOMERS." MODIFY customers_password VARCHAR(255) NOT NULL");

          // Check that password is good
          $check_customer = xtc_db_fetch_array($check_customer_query);      
          if (xtc_validate_password($password, $check_customer['customers_password'], false) !== true) {
            return false;
          }
        } else {
          return false;
        }
      } else {
        return false;
      }
    }
    return true;
  }

  function show_auth() {
    global $PHP_SELF;
    
    define('_MODIFIED_SHOP_LOGIN', true);
    include(DIR_FS_CATALOG.'includes/login_admin.php');
    exit();
  }
  
  function check_db() {
    // include functions
    require_once(DIR_FS_INC.'auto_include.inc.php');
    require_once(DIR_WS_INCLUDES . 'database_tables.php');

    require_once (DIR_FS_INC.'xtc_not_null.inc.php');
    require_once (DIR_FS_INC.'xtc_validate_password.inc.php');

    // Database
    $db_type = get_mysql_type();
    require_once (DIR_FS_INC.'db_functions_'.$db_type.'.inc.php');
    require_once (DIR_FS_INC.'db_functions.inc.php');

  $check_db = false;
  if (DB_SERVER_USERNAME != '') {
    // make a connection to the database... now
    $check_db = xtc_db_connect();
    
    if ($check_db !== false) {
      $check_db = false;
      
      $check_table = xtc_db_query("SHOW TABLES WHERE `Tables_in_".DB_DATABASE."` = '".TABLE_CUSTOMERS."'");
      if (xtc_db_num_rows($check_table) > 0) {     
        $check_query = xtc_db_query("SELECT *
                                       FROM ".TABLE_CUSTOMERS);
        if (xtc_db_num_rows($check_query) > 0) {
          $check_db = true;
        }
      }
    }
  }
  
    return $check_db;
  } 
?>