<?php
/* -----------------------------------------------------------------------------------------
   $Id: products_tariff.php 13639 2021-07-27 12:42:17Z GTB $

   modified eCommerce Shopsoftware
   http://www.modified-shop.org

   Copyright (c) 2009 - 2013 [www.modified-shop.org]
   -----------------------------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------------------------*/

  if (defined('MODULE_PRODUCTS_TARIFF_STATUS')
      && MODULE_PRODUCTS_TARIFF_STATUS == 'true'
      )
  {
    $add_select_cart[] = 'p.products_origin'; 
    $add_select_cart[] = 'p.products_tariff'; 
    $add_select_cart[] = 'p.products_tariff_title'; 
  }