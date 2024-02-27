<?php
  /* --------------------------------------------------------------
   $Id: xtc_content_link.inc.php 14836 2022-12-15 16:40:06Z GTB $

   modified eCommerce Shopsoftware
   http://www.modified-shop.org

   Copyright (c) 2009 - 2013 [www.modified-shop.org]
   --------------------------------------------------------------
   Released under the GNU General Public License
   --------------------------------------------------------------*/

  function xtc_content_link($coID, $name = '') {
    $params = 'coID='.$coID;
    if (SEARCH_ENGINE_FRIENDLY_URLS == 'true' && $name != '') {
      $params .= '&name='.base64_encode(strip_tags($name));
    }

    return $params;
  }
