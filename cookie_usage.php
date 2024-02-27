<?php
/* -----------------------------------------------------------------------------------------
   $Id: cookie_usage.php 15273 2023-06-27 09:35:15Z GTB $   

   XT-Commerce - community made shopping
   http://www.xt-commerce.com

   Copyright (c) 2003 XT-Commerce
   -----------------------------------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(cookie_usage.php,v 1.1 2003/03/10); www.oscommerce.com 
   (c) 2003	 nextcommerce (cookie_usage.php,v 1.9 2003/08/17); www.nextcommerce.org

   Released under the GNU General Public License 
   ---------------------------------------------------------------------------------------*/

include ('includes/application_top.php');

if (isset($_GET['return_to'])
    && $session_started == true
    )
{
  xtc_redirect(xtc_href_link(basename($_GET['return_to']), xtc_get_all_get_params(array('return_to')), 'SSL'));
}

$smarty = new Smarty();
$smarty->assign('language', $_SESSION['language']);
$smarty->assign('tpl_path', DIR_WS_BASE.'templates/'.CURRENT_TEMPLATE.'/');

// build breadcrumb
$breadcrumb->add(NAVBAR_TITLE_COOKIE_USAGE, xtc_href_link(FILENAME_COOKIE_USAGE));

// include header
require (DIR_WS_INCLUDES . 'header.php');

// include boxes
$display_mode = 'cookieusage';
require (DIR_FS_CATALOG.'templates/'.CURRENT_TEMPLATE.'/source/boxes.php');

// set cache ID
if (!CacheCheck()) {
  $cache = false;
  $smarty->caching = 0;
  $cache_id = null;
} else {
  $cache = true;
  $smarty->caching = 1;
  $smarty->cache_lifetime = CACHE_LIFETIME;
  $smarty->cache_modified_check = CACHE_CHECK == 'true';
  $cache_id = md5('lID:'.$_SESSION['language']);
}

if (!$smarty->is_cached(CURRENT_TEMPLATE.'/module/cookie_usage.html', $cache_id) || !$cache) {
  $smarty->assign('BUTTON_CONTINUE', '<a href="'.xtc_href_link(FILENAME_DEFAULT).'">'.xtc_image_button('button_continue.gif', IMAGE_BUTTON_CONTINUE).'</a>');
}

$main_content = $smarty->fetch(CURRENT_TEMPLATE.'/module/cookie_usage.html', $cache_id);

$smarty->assign('language', $_SESSION['language']);
$smarty->assign('main_content', $main_content);
$smarty->caching = 0;
if (!defined('RM'))
  $smarty->load_filter('output', 'note');
$smarty->display(CURRENT_TEMPLATE.'/index.html');
include ('includes/application_bottom.php');