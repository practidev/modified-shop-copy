<?php
/* -----------------------------------------------------------------------------------------
   $Id: shop_content.php 15276 2023-06-27 09:45:34Z GTB $

   modified eCommerce Shopsoftware
   http://www.modified-shop.org

   Copyright (c) 2009 - 2013 [www.modified-shop.org]
   -----------------------------------------------------------------------------------------
   based on:
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(conditions.php,v 1.21 2003/02/13); www.oscommerce.com 
   (c) 2003 nextcommerce (shop_content.php,v 1.1 2003/08/19); www.nextcommerce.org
   (c) 2006 XT-Commerce (shop_content.php 1238 2005-09-24)

   Released under the GNU General Public License 
   ---------------------------------------------------------------------------------------*/

require_once ('includes/application_top.php');

// redirect contact form to SSL if available
if (ENABLE_SSL == true && $request_type == 'NONSSL' && !isset($_GET['action']) && $_GET['coID'] == '7') {
  xtc_redirect(xtc_href_link(FILENAME_CONTENT, xtc_content_link((int)$_GET['coID']), 'SSL'));
}

// include needed functions
require_once (DIR_FS_INC.'xtc_validate_email.inc.php');

// create smarty elements
$smarty = new Smarty();
$smarty->assign('language', $_SESSION['language']);
$smarty->assign('tpl_path', DIR_WS_BASE.'templates/'.CURRENT_TEMPLATE.'/');

if ($language_not_found === true) {
  $site_error = TEXT_CONTENT_NOT_FOUND;
  include (DIR_WS_MODULES.FILENAME_ERROR_HANDLER);
} else {
  if (!isset($_GET['coID']) || $_GET['coID'] == '') {
    xtc_redirect(xtc_href_link(FILENAME_DEFAULT));
  }
  
  $shop_content_query = xtDBquery("SELECT ".ADD_SELECT_CONTENT."
                                          content_id, 
                                          content_title, 
                                          content_heading, 
                                          content_text, 
                                          content_file,
                                          content_group,
                                          parent_id
                                     FROM ".TABLE_CONTENT_MANAGER."
                                    WHERE content_group='".(int) $_GET['coID']."'
                                      AND content_active = '1'
                                      AND trim(content_title) != ''
                                      AND languages_id = '".(int)$_SESSION['languages_id']."'
                                          ".CONTENT_CONDITIONS);
  
  if (xtc_db_num_rows($shop_content_query, true) < 1) {
    $site_error = TEXT_CONTENT_NOT_FOUND;
    include (DIR_WS_MODULES.FILENAME_ERROR_HANDLER);

    // build breadcrumb
    $breadcrumb->add(NAVBAR_TITLE_ERROR, xtc_href_link(FILENAME_ERROR));
  } else {
    $display_mode = 'content';
    $shop_content_data = xtc_db_fetch_array($shop_content_query, true);
    
    // sub content
    include (DIR_WS_MODULES.'sub_content_listing.php');

    // build breadcrumb
    $breadcrumb->add($shop_content_data['content_title'], xtc_href_link(FILENAME_CONTENT, xtc_content_link($shop_content_data['content_group'], $shop_content_data['content_title'])));

    $link = 'javascript:history.back(1)';
    if (!isset($_SERVER['HTTP_REFERER']) 
        || strpos($_SERVER['HTTP_REFERER'], HTTP_SERVER) === false
        )
    {
      $link = xtc_href_link(FILENAME_DEFAULT, '', 'NONSSL');
    } 
    $smarty->assign('BUTTON_CONTINUE', '<a href="'.$link.'">'.xtc_image_button('button_back.gif', IMAGE_BUTTON_BACK).'</a>');
    $smarty->assign('CONTENT_HEADING', (($shop_content_data['content_heading'] != '') ? $shop_content_data['content_heading'] : $shop_content_data['content_title']));

    if ($messageStack->size('content') > 0) {
      $smarty->assign('error_message', $messageStack->output('content'));
    }

    $content_body = $shop_content_data['content_text'];
    if ($shop_content_data['content_file'] != '' 
        && is_file(DIR_FS_CATALOG.'media/content/'.$shop_content_data['content_file'])
        )
    {
      ob_start();
      if (strpos($shop_content_data['content_file'], '.txt') !== false)
        echo '<pre>';
      include (DIR_FS_CATALOG.'media/content/'.$shop_content_data['content_file']);
      if (strpos($shop_content_data['content_file'], '.txt') !== false)
        echo '</pre>';
      $smarty->assign('file', ob_get_contents());
      ob_end_clean();
    }
    $smarty->assign('CONTENT_BODY', $content_body);
  
    include (DIR_WS_MODULES.'content_manager_media.php');
  
    $content_template = 'content.html';
    foreach(auto_include(DIR_FS_CATALOG.'includes/extra/shop_content_end/','php') as $file) require_once ($file);

    $smarty->caching = 0;
    $main_content = $smarty->fetch(CURRENT_TEMPLATE.'/module/'.$content_template);
    $smarty->assign('main_content', $main_content);
  }
}

// include header
require (DIR_WS_INCLUDES . 'header.php');

// include boxes
require (DIR_FS_CATALOG.'templates/'.CURRENT_TEMPLATE.'/source/boxes.php');

$smarty->caching = 0;
if (!defined('RM'))
  $smarty->load_filter('output', 'note');
$smarty->display(CURRENT_TEMPLATE.'/index.html');
include ('includes/application_bottom.php');