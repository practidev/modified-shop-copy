<?php
/* -----------------------------------------------------------------------------------------
   $Id: xtc_filesize.inc.php 15124 2023-04-28 06:22:01Z GTB $   

   XT-Commerce - community made shopping
   http://www.xt-commerce.com

   Copyright (c) 2003 XT-Commerce
   -----------------------------------------------------------------------------------------
   based on:
   (c) 2003	 nextcommerce (xtc_filesize.inc.php,v 1.1 2003/08/24); www.nextcommerce.org
   
   Released under the GNU General Public License 
   ---------------------------------------------------------------------------------------*/


  function xtc_filesize($file, $dir = 'products') {
    $a = array("B","KB","MB","GB","TB","PB");
  
    $size = $pos = 0;
    if (is_file(DIR_FS_CATALOG.'media/'.$dir.'/'.$file)) {
      $size = filesize(DIR_FS_CATALOG.'media/'.$dir.'/'.$file);
      while ($size >= 1024) {
        $size /= 1024;
        $pos++;
      }
    }
  
    return round($size,2)." ".$a[$pos];
  }