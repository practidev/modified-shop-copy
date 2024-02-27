<?php
/* -----------------------------------------------------------------------------------------
   $Id: admin_log.php 15210 2023-06-12 15:34:37Z GTB $

   modified eCommerce Shopsoftware
   http://www.modified-shop.org

   Copyright (c) 2009 - 2013 [www.modified-shop.org]
   -----------------------------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------------------------*/

define('MODULE_ADMIN_LOG_TEXT_TITLE', 'Administrator Log');
define('MODULE_ADMIN_LOG_TEXT_DESCRIPTION', 'Changes done by an Admin will be logged.
<ul>
  <li>Orders</li>
  <li>Categories</li>
  <li>Products</li>
  <li>Content Manager</li>
  <li>Shipping Methods</li>
  <li>Payment Methods</li>
  <li>Order Total</li>
  <li>System Modules</li>
  <li>Export Modules</li>
  <li>Configuration</li>
</ul>
  ');
define('MODULE_ADMIN_LOG_STATUS_TITLE', 'Activate module?');
define('MODULE_ADMIN_LOG_STATUS_DESC', 'Activate Administrator Log');

define('MODULE_ADMIN_LOG_DISPLAY_TITLE', 'Display Log?');
define('MODULE_ADMIN_LOG_DISPLAY_DESC', 'Display administrator Log in Footer');

define('MODULE_ADMIN_LOG_SHOW_DETAILS_TITLE', 'Show Details?');
define('MODULE_ADMIN_LOG_SHOW_DETAILS_DESC', 'Show changes as an Array');

define('MODULE_ADMIN_LOG_SHOW_DETAILS_FULL_TITLE', 'Show full Details?');
define('MODULE_ADMIN_LOG_SHOW_DETAILS_FULL_DESC', 'Show full changes as an Array');

define('MODULE_ADMIN_LOG_SCHEDULED_TASKS_TITLE', 'Logs cleanup');
define('MODULE_ADMIN_LOG_SCHEDULED_TASKS_DESC', 'Should the logs get regulary cleaned?');

define('MODULE_ADMIN_LOG_TRESHOLD_DAYS_TITLE', 'Treshold Logs');
define('MODULE_ADMIN_LOG_TRESHOLD_DAYS_DESC', 'How many days should the logs be kept?');
