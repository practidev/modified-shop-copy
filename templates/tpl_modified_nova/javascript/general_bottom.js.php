<?php
/*-----------------------------------------------------------
   $Id: general_bottom.js.php 15291 2023-07-06 11:46:25Z GTB $

   modified eCommerce Shopsoftware
   http://www.modified-shop.org

   Copyright (c) 2009 - 2013 [www.modified-shop.org]
  -----------------------------------------------------------
   based on: (c) 2003 - 2006 XT-Commerce (general.js.php)
  -----------------------------------------------------------
   Released under the GNU General Public License
  -----------------------------------------------------------*/
   
  // this javascriptfile get includes at the BOTTOM of every template page in shop
  // you can add your template specific js scripts here
  defined('DIR_TMPL_JS') OR define('DIR_TMPL_JS', DIR_TMPL.'javascript/');
  ?>

  <script src="<?php echo DIR_WS_BASE.DIR_TMPL_JS; ?>jquery.min.js"></script>
  <?php
  $script_array = array(
    DIR_TMPL_JS.'jquery.mmenulight.js',
    DIR_TMPL_JS.'jquery.colorbox.min.js',
    DIR_TMPL_JS.'jquery.lazysizes.min.js',
    DIR_TMPL_JS.'jquery.viewer.min.js',
    DIR_TMPL_JS.'jquery.easyTabs.js',
    DIR_TMPL_JS.'jquery.alertable.min.js',
    DIR_TMPL_JS.'jquery.sumoselect.min.js',
    DIR_TMPL_JS.'splide.min.js',

  );
  $script_min = DIR_TMPL_JS.'tpl_plugins.min.js';
  
  $this_f_time = filemtime(DIR_FS_CATALOG.DIR_TMPL_JS.'general_bottom.js.php');
  
  if (COMPRESS_JAVASCRIPT == 'true') {
    require_once(DIR_FS_BOXES_INC.'combine_files.inc.php');
    $script_array = combine_files($script_array,$script_min,false,$this_f_time);
  }

  foreach ($script_array as $script) {
    $script .= strpos($script,$script_min) === false ? '?v=' . filemtime(DIR_FS_CATALOG.$script) : '';
    echo '<script src="'.DIR_WS_BASE.$script.'"></script>'.PHP_EOL;
  }

  ob_start();
  foreach(auto_include(DIR_FS_CATALOG.DIR_TMPL_JS.'/extra/','php') as $file) require ($file);
  $javascript = ob_get_clean();
  if (COMPRESS_JAVASCRIPT == 'true') {
    require_once(DIR_FS_EXTERNAL.'compactor/compactor.php');
    $compactor = new Compactor(array('strip_php_comments' => false, 'compress_css' => false, 'compress_scripts' => true));
    $javascript = $compactor->squeeze($javascript);
  }
  echo $javascript.PHP_EOL;
