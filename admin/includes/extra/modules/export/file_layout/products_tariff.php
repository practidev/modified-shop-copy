<?php
/* -----------------------------------------------------------------------------------------
   $Id: products_tariff.php 14937 2023-01-30 16:20:02Z GTB $

   modified eCommerce Shopsoftware
   http://www.modified-shop.org

   Copyright (c) 2009 - 2013 [www.modified-shop.org]
   -----------------------------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------------------------*/

  defined('_VALID_XTC') or die('Direct Access to this location is not allowed.');

  if (defined('MODULE_PRODUCTS_TARIFF_STATUS')
      && MODULE_PRODUCTS_TARIFF_STATUS == 'true'
      )
  {
    $line .= $this->encode('p_tariff');
    $line .= $this->encode('p_tariff_title');
    $line .= $this->encode('p_origin');
  }