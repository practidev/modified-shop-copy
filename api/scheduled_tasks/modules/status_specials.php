<?php
/* -----------------------------------------------------------------------------------------
   $Id: status_specials.php 14962 2023-02-08 10:30:27Z GTB $

   modified eCommerce Shopsoftware
   http://www.modified-shop.org

   Copyright (c) 2009 - 2013 [www.modified-shop.org]
   -----------------------------------------------------------------------------------------
   Released under the GNU General Public License 
   ---------------------------------------------------------------------------------------*/

  function cron_status_specials() {
    // include needed functions
    require_once(DIR_FS_CATALOG.'inc/xtc_expire_specials.inc.php');
    
    xtc_expire_specials();
    
    return true;
  }