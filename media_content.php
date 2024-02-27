<?php
/* -----------------------------------------------------------------------------------------
   $Id: media_content.php 15238 2023-06-14 08:23:03Z GTB $

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

$content_heading = $content_text = '';

if (isset($_GET['coID']) && (int)$_GET['coID'] > 0) {
  $table = TABLE_PRODUCTS_CONTENT;
  $type = 'content';
  if (isset($_GET['type'])) {
    $type = xtc_db_input($_GET['type']);
    $table = constant('TABLE_'.strtoupper($_GET['type']).'_CONTENT');
  }
  $content_query = xtDBquery("SELECT content_name, 
                                     content_file, 
                                     file_comment
                                FROM ".$table."
                               WHERE ".$type."_id = '".(int) $_GET['coID']."'
                                     ".CONTENT_CONDITIONS);

  if (xtc_db_num_rows($content_query, true) == 1) {
    $content_data = xtc_db_fetch_array($content_query, true);

    xtc_db_query("UPDATE ".$table."
                     SET content_read = content_read + 1
                   WHERE ".$type."_id = '".(int) $_GET['coID']."'");

    $content_heading = $content_data['content_name'];
    $content_text = $content_data['file_comment'];

    if ($content_data['content_file'] != '' 
        && (is_file(DIR_FS_CATALOG.'media/content/'.$content_data['content_file'])
            || is_file(DIR_FS_CATALOG.'media/products/'.$content_data['content_file']))
        )
    {
      $path = 'content';
      if (is_file(DIR_FS_CATALOG.'media/products/'.$content_data['content_file'])) {
        $path = 'products';
      }
      $mime_type = mime_content_type(DIR_FS_CATALOG.'media/'.$path.'/'.$content_data['content_file']);
      ob_start();
      if (strpos($content_data['content_file'], '.txt') !== false)
        echo '<pre>';
      if (strpos($mime_type, 'image') !== false) {
        echo xtc_image('media/'.$path.'/'.$content_data['content_file'], $content_data['content_name']);
      } else {
        include (DIR_FS_CATALOG.'media/'.$path.'/'.$content_data['content_file']);
      }
      if (strpos($content_data['content_file'], '.txt') !== false)
        echo '</pre>';
      $content_text = ob_get_contents();
      ob_end_clean();
    }
  }
}

$popup_smarty = new Smarty();
$popup_smarty->assign('language', $_SESSION['language']);
$popup_smarty->assign('tpl_path', DIR_WS_BASE.'templates/'.CURRENT_TEMPLATE.'/');
$popup_smarty->assign('html_params', ((TEMPLATE_HTML_ENGINE == 'xhtml') ? ' '.HTML_PARAMS : ' lang="'.$_SESSION['language_code'].'"'));
$popup_smarty->assign('doctype', ((TEMPLATE_HTML_ENGINE == 'xhtml') ? ' PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"' : ''));
$popup_smarty->assign('charset', $_SESSION['language_charset']);
$popup_smarty->assign('link_close', 'javascript:window.close()');
$popup_smarty->assign('title', (($content_heading != '') ? htmlspecialchars($content_heading, ENT_QUOTES, strtoupper($_SESSION['language_charset'])) : ''));
if (DIR_WS_BASE == '') {
  $popup_smarty->assign('base', (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG);
}

$popup_smarty->assign('content_heading', $content_heading);
$popup_smarty->assign('content_text', $content_text);

$popup_smarty->caching = 0;
$popup_smarty->display(CURRENT_TEMPLATE.'/module/popup_content.html');
