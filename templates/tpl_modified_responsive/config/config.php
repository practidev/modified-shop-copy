<?php
/* -----------------------------------------------------------------------------------------
   $Id: config.php 15034 2023-03-17 14:06:12Z Markus $

   modified eCommerce Shopsoftware
   http://www.modified-shop.org

   Copyright (c) 2009 - 2013 [www.modified-shop.org]
   -----------------------------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------------------------*/

  /*
   *  template specific defines
   */
  
  // paths
  define('DIR_FS_BOXES', DIR_FS_CATALOG .'templates/'.CURRENT_TEMPLATE. '/source/boxes/');
  define('DIR_FS_BOXES_INC', DIR_FS_CATALOG .'templates/'.CURRENT_TEMPLATE. '/source/inc/');

  // popup
  define('TPL_POPUP_SHIPPING_LINK_PARAMETERS', '');
  define('TPL_POPUP_SHIPPING_LINK_CLASS', 'iframe');
  define('TPL_POPUP_CONTENT_LINK_PARAMETERS', '');
  define('TPL_POPUP_CONTENT_LINK_CLASS', 'iframe');
  define('TPL_POPUP_PRODUCT_LINK_PARAMETERS', '');
  define('TPL_POPUP_PRODUCT_LINK_CLASS', 'iframe');
  define('TPL_POPUP_COUPON_HELP_LINK_PARAMETERS', '');
  define('TPL_POPUP_COUPON_HELP_LINK_CLASS', 'iframe');
  define('TPL_POPUP_PRODUCT_PRINT_SIZE', '');
  define('TPL_POPUP_PRINT_ORDER_SIZE', '');
  
  define('PRODUCT_LIST_BOX', ((isset($_SESSION['listbox'])) ? $_SESSION['listbox'] : 'true')); // 'true' zeigt Artikel in Kategorie-Navigation (product_listing) als Box-Ansicht / 'false' zeigt Listen-Ansicht
  define('PRODUCT_LIST_BOX_STARTPAGE', 'true'); // 'true' zeigt "Unsere TOP-Artikel" als Box-Ansicht / 'false' zeigt als Listen-Ansicht
  define('PRODUCT_INFO_BOX', 'false'); // 'true' zeigt Cross-Selling-, Reverse-Cross-Selling- & Also-Purchased-Artikel auf Artikel-Detailseite als Box-Ansicht / 'false' zeigt als Listen-Ansicht
  
  // template output
  define('TEMPLATE_ENGINE', 'smarty_4'); // 'smarty_4' oder 'smarty_3' oder 'smarty_2' -> Nicht ändern! (Nur "smarty_4" oder "smarty_3" unterstützt die custom Sprachdateien (lang_english.custom & lang_german.custom) aus dem Ordner "../lang/" des Templates!)
  define('TEMPLATE_HTML_ENGINE', 'html5'); // 'html5' oder 'xhtml' -> Nicht ändern!
  define('TEMPLATE_RESPONSIVE', 'true'); // 'true' oder 'false' -> Nicht ändern!
  defined('COMPRESS_JAVASCRIPT') or define('COMPRESS_JAVASCRIPT', true); // 'true' kombiniert & komprimiert die zusätzliche JS-Dateien / 'false' bindet alle JS-Dateien einzeln ein

  // categories
  defined('SPECIALS_CATEGORIES') or define('SPECIALS_CATEGORIES', true); // 'true' zeigt den Link "Angebote" im Kategoriebaum / 'false' zeigt die "Angebote" als separate Box
  defined('WHATSNEW_CATEGORIES') or define('WHATSNEW_CATEGORIES', true); // 'true' zeigt den Link "Neue Artikel" im Kategoriebaum / 'false' zeigt die "Neue Artikel" als separate Box

  // check specials
  if (SPECIALS_CATEGORIES === true) {
    require_once (DIR_FS_INC.'check_specials.inc.php');
    define('SPECIALS_EXISTS', check_specials());
  }
  
  // check whats new
  /*
  if (WHATSNEW_CATEGORIES === true) {
    require_once (DIR_FS_INC.'check_whatsnew.inc.php');
    define('WHATSNEW_EXISTS', check_whatsnew());
  }
  */
    
  // picture set listing box
  define('PICTURESET_ACTIVE', defined('DIR_WS_MINI_IMAGES'));
  define('PICTURESET_BOX', '360:thumbnail,480:midi,600:thumbnail,690:thumbnail,920:thumbnail,985:midi');
  define('PICTURESET_ROW', '985:midi');
  
  // Sumo select  
  defined('ADVANCED_SUMOSELECT_SEARCHFIELD') or define('ADVANCED_SUMOSELECT_SEARCHFIELD', true); // 'true' zeigt in allen Select-Felder das Suchfeld an  / 'false' zeigt das Suchfeld nur bei der Länderauswahl an
  
  // set base
  defined('DIR_WS_BASE') OR define('DIR_WS_BASE', xtc_href_link('', '', $request_type, false, false));

  // css buttons
  if (is_file(DIR_FS_CATALOG.'templates/'.CURRENT_TEMPLATE.'/source/inc/css_button.inc.php')) {
    require_once(DIR_FS_CATALOG.'templates/'.CURRENT_TEMPLATE.'/source/inc/css_button.inc.php');
  }
