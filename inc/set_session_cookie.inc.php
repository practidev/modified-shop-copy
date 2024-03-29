<?php
/* -----------------------------------------------------------------------------------------
   $Id: set_session_cookie.inc.php 15144 2023-05-03 10:05:57Z GTB $

   modified eCommerce Shopsoftware - community made shopping
   http://www.modified-shop.org

   Copyright (c) 2009 - 2012 modified eCommerce Shopsoftware
   -----------------------------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------------------------*/

  function set_session_cookie($lifetime, $path, $domain, $secure = false, $httponly = false, $samesite = 'None') {
    
    $user_agent = '';
    if (isset($_SERVER['HTTP_USER_AGENT'])) {
      $user_agent = $_SERVER['HTTP_USER_AGENT'];
    }
    
    if ($samesite == 'Lax'
        && stripos($user_agent, 'safari') !== false
        && stripos($user_agent, 'chrome') === false
        )
    {
      $samesite = 'None';
    }
    
    if (function_exists('session_set_cookie_params')) {
      if (version_compare(PHP_VERSION, '7.3', '>=')) {
        $cookie_options = array (
          'lifetime' => $lifetime,
          'path' => $path,
          'domain' => $domain,
          'secure' => $secure,
          'httponly' => $httponly,
          'samesite' => $samesite
        );
        session_set_cookie_params($cookie_options);
      } else {
        session_set_cookie_params($lifetime, $path.'; samesite='.$samesite, $domain, $secure, $httponly);
      }
    } elseif (function_exists('ini_set')) {
      ini_set('session.cookie_lifetime', $lifetime);
      ini_set('session.cookie_path', $path);
      ini_set('session.cookie_domain', $domain);
      ini_set('session.cookie_secure', $secure);
      ini_set('session.cookie_httponly', $httponly);
      if (version_compare(PHP_VERSION, '7.3', '>=')) {
        ini_set('session.cookie_samesite', $samesite);
      }
    }
  }
