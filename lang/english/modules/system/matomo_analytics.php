<?php
/* -----------------------------------------------------------------------------------------
   $Id: matomo_analytics.php 15102 2023-04-19 13:03:25Z GTB $

   modified eCommerce Shopsoftware
   http://www.modified-shop.org

   Copyright (c) 2009 - 2013 [www.modified-shop.org]
   -----------------------------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------------------------*/

  define('MODULE_MATOMO_ANALYTICS_TEXT_TITLE', 'Matomo Analytics');
  define('MODULE_MATOMO_ANALYTICS_TEXT_DESCRIPTION', 'In order to use Matomo Analytics at all, you have to download and install it to your webspace at first. See also <a href="https://matomo.org/" target="_blank"><b>Matomo Web-Analytics</b></a>. In comparison to Google Analytics all data will be stored locally, i.e. you as show owner have complete control over all data.');    

  define('MODULE_MATOMO_ANALYTICS_STATUS_TITLE', 'Status');
  define('MODULE_MATOMO_ANALYTICS_STATUS_DESC', 'Module status');

  define('MODULE_MATOMO_ANALYTICS_ID_TITLE' , 'Matomo page ID');
  define('MODULE_MATOMO_ANALYTICS_ID_DESC' , 'In the Matomo administration a page ID will be created per domain (usually "1")');

  define('MODULE_MATOMO_ANALYTICS_LOCAL_PATH_TITLE' , 'Matomo install path (without "http://")');
  define('MODULE_MATOMO_ANALYTICS_LOCAL_PATH_DESC' , 'Enter the path when Matomo was installed successfully. The complete path of the domain has to be given, but without "http://", e.g. "www.example.com/matomo".');

  define('MODULE_MATOMO_ANALYTICS_GOAL_TITLE' , 'Matomo campaign number (optional)');
  define('MODULE_MATOMO_ANALYTICS_GOAL_DESC' , 'Enter your campaign number, if you want to track predefined goals. Details see <a href="https://matomo.org/docs/tracking-goals-web-analytics/" target="_blank"><b>Matomo: Tracking Goal Conversions</b></a>');

  define('MODULE_MATOMO_ANALYTICS_COUNT_ADMIN_TITLE' , 'Count page views of the shop owner');
  define('MODULE_MATOMO_ANALYTICS_COUNT_ADMIN_DESC' , 'By activating this option, all page views of the administration usersof the shop owner will be counted as well. This will falsify the visitor stats.');
