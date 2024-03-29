<?php
/* -----------------------------------------------------------------------------------------
   $Id: popup_search_help.php 15346 2023-07-17 15:07:43Z Tomcraft $

   XT-Commerce - community made shopping
   http://www.xt-commerce.com

   Copyright (c) 2003 XT-Commerce
   -----------------------------------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(popup_search_help.php,v 1.3 2003/02/13); www.oscommerce.com
   (c) 2003	 nextcommerce (popup_search_help.php,v 1.6 2003/08/17); www.nextcommerce.org 

   Released under the GNU General Public License
   ---------------------------------------------------------------------------------------*/

include ('includes/application_top.php');

$popup_smarty = new Smarty();
$popup_smarty->assign('language', $_SESSION['language']);
$popup_smarty->assign('tpl_path', DIR_WS_BASE.'templates/'.CURRENT_TEMPLATE.'/');
$popup_smarty->assign('html_params', ((TEMPLATE_HTML_ENGINE == 'xhtml') ? ' '.HTML_PARAMS : ' lang="'.$_SESSION['language_code'].'"'));
$popup_smarty->assign('doctype', ((TEMPLATE_HTML_ENGINE == 'xhtml') ? ' PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"' : ''));
$popup_smarty->assign('charset', $_SESSION['language_charset']);
$popup_smarty->assign('link_close', 'javascript:window.close()');
if (DIR_WS_BASE == '') {
  $popup_smarty->assign('base', (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG);
}

$popup_help_array = array();
if (SEARCH_IN_DESC == 'true') $popup_help_array[] = TEXT_SEARCH_DESCRIPTION;
if (SEARCH_IN_MANU == 'true') $popup_help_array[] = TEXT_SEARCH_MANUFACTURERS;
if (SEARCH_IN_ATTR == 'true') $popup_help_array[] = TEXT_SEARCH_ATTRIBUTES;
if (SEARCH_IN_FILTER == 'true') $popup_help_array[] = TEXT_SEARCH_TAGS;
$popup_help = implode(', ', $popup_help_array);
$popup_smarty->assign('TEXT_HELP', sprintf(TEXT_SEARCH_HELP, (($popup_help != '') ? ', ' . $popup_help.' ' : ' ')));

$popup_smarty->caching = 0;
$popup_smarty->display(CURRENT_TEMPLATE.'/module/popup_search_help.html');
