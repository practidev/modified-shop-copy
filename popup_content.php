<?php
/* -----------------------------------------------------------------------------------------
   $Id: popup_content.php 15238 2023-06-14 08:23:03Z GTB $

   modified eCommerce Shopsoftware
   http://www.modified-shop.org

   Copyright (c) 2009 - 2013 [www.modified-shop.org]
  -----------------------------------------------------------------------------------------
   based on:
   (c) 2003 nextcommerce (content_preview.php,v 1.2 2003/08/25); www.nextcommerce.org
   (c) 2006 XT-Commerce

   Released under the GNU General Public License
   ---------------------------------------------------------------------------------------*/

require ('includes/application_top.php');

$content_data = $main->getContentData(((isset($_GET['coID'])) ? (int)$_GET['coID'] : 0), '', '', (isset($_GET['preview']) ? true : false));

if (!isset($_GET['coID']) || $_GET['coID'] == '' || count($content_data) < 1) {
  die('Direct Access to this location is not allowed.');
}

$popup_smarty = new Smarty();
$popup_smarty->assign('language', $_SESSION['language']);
$popup_smarty->assign('tpl_path', DIR_WS_BASE.'templates/'.CURRENT_TEMPLATE.'/');
$popup_smarty->assign('html_params', ((TEMPLATE_HTML_ENGINE == 'xhtml') ? ' '.HTML_PARAMS : ' lang="'.$_SESSION['language_code'].'"'));
$popup_smarty->assign('doctype', ((TEMPLATE_HTML_ENGINE == 'xhtml') ? ' PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"' : ''));
$popup_smarty->assign('charset', $_SESSION['language_charset']);
$popup_smarty->assign('link_close', 'javascript:window.close()');
$popup_smarty->assign('title', htmlspecialchars($content_data['content_heading'], ENT_QUOTES, strtoupper($_SESSION['language_charset'])));
if (DIR_WS_BASE == '') {
  $popup_smarty->assign('base', (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG);
}

$popup_smarty->assign('content_heading', $content_data['content_heading']);
$popup_smarty->assign('content_text', $content_data['content_text']);

$popup_smarty->caching = 0;
$popup_smarty->display(CURRENT_TEMPLATE.'/module/popup_content.html');
