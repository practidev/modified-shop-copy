<?php
/* -----------------------------------------------------------------------------------------
   $Id: login.php 15551 2023-11-08 13:22:47Z GTB $

   modified eCommerce Shopsoftware
   http://www.modified-shop.org

   Copyright (c) 2009 - 2013 [www.modified-shop.org]
   -----------------------------------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(login.php,v 1.79 2003/05/19); www.oscommerce.com 
   (c) 2003 nextcommerce (login.php,v 1.13 2003/08/17); www.nextcommerce.org
   (c) 2003 XT-Commerce
   
   Released under the GNU General Public License 
   -----------------------------------------------------------------------------------------
   Third Party contribution:

   guest account idea by Ingo T. <xIngox@web.de>
   ---------------------------------------------------------------------------------------*/

include ('includes/application_top.php');

defined('MODULE_CAPTCHA_LOGIN_NUM') OR define('MODULE_CAPTCHA_LOGIN_NUM', 2);
defined('MODULE_CAPTCHA_CODE_LENGTH') OR define('MODULE_CAPTCHA_CODE_LENGTH', 6);

if (isset ($_SESSION['customer_id'])) {
	xtc_redirect(xtc_href_link(FILENAME_ACCOUNT, '', 'SSL'));
}

// redirect the customer to a friendly cookie-must-be-enabled page if cookies are disabled (or the session has not started)
if ($session_started == false) {
  xtc_redirect(xtc_href_link(FILENAME_COOKIE_USAGE, xtc_get_all_get_params(array('return_to')).'return_to='.basename($PHP_SELF)));
}

// include needed functions
require_once (DIR_FS_INC.'xtc_validate_password.inc.php');
require_once (DIR_FS_INC.'xtc_write_user_info.inc.php');
require_once (DIR_FS_INC.'write_customers_session.inc.php');

// include needed classes
require_once (DIR_WS_CLASSES.'modified_captcha.php');

// create smarty elements
$smarty = new Smarty();

$mod_captcha = $_mod_captcha_class::getInstance();

$account_options = ACCOUNT_OPTIONS;
$products = $_SESSION['cart']->get_products();
for ($i = 0, $n = sizeof($products); $i < $n; $i ++) {
  if (preg_match('/^GIFT/', addslashes($products[$i]['model']))) {
    $account_options = 'account';
    break;
  }
}

if (!isset($_SESSION['customers_login_tries'])) {
  $_SESSION['customers_login_tries'] = 0;
}

if (isset($_GET['action']) 
    && $_GET['action'] == 'process'
    && $_SERVER['REQUEST_METHOD'] == 'POST'
    )
{
	$email_address = ((isset($_POST['email_address'])) ? xtc_db_prepare_input($_POST['email_address']) : '');
	$password = ((isset($_POST['password'])) ? xtc_db_prepare_input($_POST['password']) : '');
  $captcha_validation = $mod_captcha->validate((isset($_POST['vvcode'])) ? $_POST['vvcode'] : '');
  
  // brute force
  $check_login_query = xtc_db_query("SELECT customers_login_tries
                                       FROM ".TABLE_CUSTOMERS_LOGIN."
                                      WHERE (customers_email_address = '".xtc_db_input($email_address)."'
                                             OR customers_ip = '".xtc_db_input($_SESSION['tracking']['ip'])."')
                                        AND customers_email_address != ''");
  if (xtc_db_num_rows($check_login_query) > 0) {
    while ($check_login = xtc_db_fetch_array($check_login_query)) {
      if ($check_login['customers_login_tries'] > $_SESSION['customers_login_tries']) {
        $_SESSION['customers_login_tries'] = $check_login['customers_login_tries'];
      }
    }
    // update login tries
    xtc_db_query("UPDATE ".TABLE_CUSTOMERS_LOGIN." 
                     SET customers_login_tries = '".($_SESSION['customers_login_tries'] + 1)."',
                         last_modified = NOW()
                   WHERE (customers_email_address = '".xtc_db_input($email_address)."'
                          OR customers_ip = '".xtc_db_input($_SESSION['tracking']['ip'])."')");
  } else {
    $sql_data_array = array(
      'customers_ip' => $_SESSION['tracking']['ip'],
      'customers_email_address' => $email_address,
      'customers_login_tries' => ($_SESSION['customers_login_tries'] + 1),
      'date_added' => 'now()',
    );
    xtc_db_perform(TABLE_CUSTOMERS_LOGIN, $sql_data_array);
  }

  // captcha
  $captcha_error = false;	
  if ($_SESSION['customers_login_tries'] >= MODULE_CAPTCHA_LOGIN_NUM) {
    $captcha_error = (($captcha_validation !== true) ? true : false);
  }
    
  // increment login tries
  $_SESSION['customers_login_tries'] ++;

	// check if email exists
	$check_customer_query = xtc_db_query("SELECT *
	                                        FROM ".TABLE_CUSTOMERS." 
	                                       WHERE customers_email_address = '".xtc_db_input($email_address)."' 
	                                         AND account_type = '0'");

	if (xtc_db_num_rows($check_customer_query) < 1) {
		$messageStack->add('login', TEXT_LOGIN_ERROR);
		if (isset($_POST['login']) && $_POST['login'] == 'admin') {
		  xtc_redirect(xtc_href_link('login_admin.php', '', 'SSL'));
		}
	} else {
		$check_customer = xtc_db_fetch_array($check_customer_query);
    		
		// Check that password is good
		if (xtc_validate_password($password, $check_customer['customers_password'], $check_customer['customers_id']) !== true) {
			$messageStack->add('login', TEXT_LOGIN_ERROR);      
      if (isset($_POST['login']) && $_POST['login'] == 'admin') {
        xtc_redirect(xtc_href_link('login_admin.php', '', 'SSL'));
      }
		} elseif ($captcha_error === false) {		
			if (SESSION_RECREATE == 'True') {
				xtc_session_recreate();
			}
      
      // reset Login tries
      unset($_SESSION['customers_login_tries']);
      xtc_db_query("DELETE FROM ".TABLE_CUSTOMERS_LOGIN."
                          WHERE (customers_email_address = '".xtc_db_input($email_address)."'
                                 OR customers_ip = '".xtc_db_input($_SESSION['tracking']['ip'])."')");

			// default session
			$_SESSION['customer_id'] = $check_customer['customers_id'];
			$_SESSION['customer_time'] = $check_customer['customers_password_time'];
      
      if ($_SESSION['customer_time'] == 0) {
        $_SESSION['customer_time'] = time();
        xtc_db_query("UPDATE ".TABLE_CUSTOMERS."
                         SET customers_password_time = '".(int)$_SESSION['customer_time']."'
                       WHERE customers_id = '".(int)$_SESSION['customer_id']."' ");
      }
      
			xtc_db_query("UPDATE ".TABLE_CUSTOMERS_INFO." 
			                 SET customers_info_date_of_last_logon = now(), 
			                     customers_info_number_of_logons = customers_info_number_of_logons+1 
			               WHERE customers_info_id = '".(int)$_SESSION['customer_id']."'");
      
      // write customers status session
      require(DIR_WS_INCLUDES.'write_customers_status.php');

      // write customers session
      write_customers_session((int)$_SESSION['customer_id']);

      // user info
			xtc_write_user_info((int)$_SESSION['customer_id']);
			
      // who's online
      xtc_update_whos_online();

			// restore cart contents
			$_SESSION['cart']->restore_contents();

			// restore wishlist contents
			if (defined('MODULE_WISHLIST_SYSTEM_STATUS') && MODULE_WISHLIST_SYSTEM_STATUS == 'true') {
			  $_SESSION['wishlist']->restore_contents();
			}
			
			if (isset($econda) && is_object($econda)) {
			  $econda->_loginUser();			
      }

      foreach(auto_include(DIR_FS_CATALOG.'includes/extra/login/','php') as $file) require_once ($file);
      
      // redirect to last viewed page
      $cnt_pageview_history = count($_SESSION['tracking']['pageview_history']);
      if ($cnt_pageview_history > 1) {
        $redirect = $_SESSION['tracking']['pageview_history'][$cnt_pageview_history - 2];
        if (substr($redirect, 0, strlen(DIR_WS_CATALOG)) == DIR_WS_CATALOG) {
          $redirect = substr($redirect, strlen(DIR_WS_CATALOG));
        }
        if ($_SESSION['old_customers_basket_cart'] === true) {
          unset($_SESSION['old_customers_basket_cart']);
          $messageStack->add_session('global', TEXT_SAVED_BASKET);
        }
        xtc_redirect(xtc_href_link(ltrim($redirect, '/'))); 
      }
      
      // redirect fallback
      if ($_SESSION['cart']->count_contents() > 0) {
        if ($_SESSION['old_customers_basket_cart'] === true) {
          unset($_SESSION['old_customers_basket_cart']);
          $messageStack->add_session('info_message_3', TEXT_SAVED_BASKET);
        }
        xtc_redirect(xtc_href_link(FILENAME_SHOPPING_CART),'NONSSL'); 
      } else {          
        xtc_redirect(xtc_href_link(FILENAME_DEFAULT),'NONSSL');           
      }
		}
	}
}

if (isset($captcha_error) && $captcha_error === true) {	
  $messageStack->add('login', TEXT_WRONG_CODE);
}

if (isset($_GET['action']) && $_GET['action'] === 'relogin') {	
  $messageStack->add('login', TEXT_RELOGIN_NEEDED);
}

// build breadcrumb
$breadcrumb->add(NAVBAR_TITLE_LOGIN, xtc_href_link(FILENAME_LOGIN, '', 'SSL'));

// include header
require (DIR_WS_INCLUDES . 'header.php');

// include boxes
$display_mode = 'login';
require (DIR_FS_CATALOG.'templates/'.CURRENT_TEMPLATE.'/source/boxes.php');

if ($messageStack->size('login') > 0) {
	$smarty->assign('error_message', $messageStack->output('login'));
}
if ($messageStack->size('login', 'success') > 0) {
	$smarty->assign('success_message', $messageStack->output('login', 'success'));
}

$smarty->assign('account_option', $account_options);
$smarty->assign('BUTTON_NEW_ACCOUNT', '<a href="'.xtc_href_link(FILENAME_CREATE_ACCOUNT, '', 'SSL').'">'.xtc_image_button('button_continue.gif', IMAGE_BUTTON_CONTINUE).'</a>');
$smarty->assign('BUTTON_LOGIN', xtc_image_submit('button_login.gif', IMAGE_BUTTON_LOGIN));
$smarty->assign('BUTTON_GUEST', '<a href="'.xtc_href_link(FILENAME_CREATE_GUEST_ACCOUNT, '', 'SSL').'">'.xtc_image_button('button_continue.gif', IMAGE_BUTTON_CONTINUE).'</a>');
$smarty->assign('FORM_ACTION', xtc_draw_form('login', xtc_href_link(FILENAME_LOGIN, xtc_get_all_get_params(array('action')).'action=process', 'SSL')));
$smarty->assign('INPUT_MAIL', xtc_draw_input_field('email_address'));
$smarty->assign('INPUT_PASSWORD', xtc_draw_password_field('password'));
$smarty->assign('LINK_LOST_PASSWORD', xtc_href_link(FILENAME_PASSWORD_DOUBLE_OPT, '', 'SSL'));
$smarty->assign('FORM_END', '</form>');

// captcha
if ($_SESSION['customers_login_tries'] >= MODULE_CAPTCHA_LOGIN_NUM) {
  $smarty->assign('VVIMG', $mod_captcha->get_image_code());
  $smarty->assign('INPUT_CODE', $mod_captcha->get_input_code());
}

$smarty->assign('language', $_SESSION['language']);
$smarty->caching = 0;
$main_content = $smarty->fetch(CURRENT_TEMPLATE.'/module/login.html');
$smarty->assign('main_content', $main_content);

$smarty->assign('language', $_SESSION['language']);
$smarty->caching = 0;
if (!defined('RM'))
	$smarty->load_filter('output', 'note');
$smarty->display(CURRENT_TEMPLATE.'/index.html');
include ('includes/application_bottom.php');