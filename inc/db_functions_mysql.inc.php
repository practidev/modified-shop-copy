<?php
/* -----------------------------------------------------------------------------------------
   $Id: db_functions_mysql.inc.php 14385 2022-04-28 13:44:23Z GTB $

   modified eCommerce Shopsoftware
   http://www.modified-shop.org

   Copyright (c) 2009 - 2013 [www.modified-shop.org]
   -----------------------------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------------------------*/

  die('Deprecated File: '.basename(__FILE__).'. Use MySQLi instead.'); 
/*
  function xtc_db_select_db($database) {
    return mysql_select_db($database);
  }


  function xtc_db_close($link='db_link') {
    global ${$link};

    return mysql_close(${$link});
  }


  function xtc_db_fetch_fields($db_query) {
    return mysql_fetch_field($db_query);
  }


  function xtc_db_free_result($db_query) {
    return mysql_free_result($db_query);
  }


  function xtc_db_get_client_info($link='db_link') {
    global ${$link};

    return mysql_get_client_info();
  }


  function xtc_db_get_server_info($link='db_link') {
    global ${$link};

    return mysql_get_server_info(${$link});
  }


  function xtc_db_fetch_object($db_query) {
    return mysql_fetch_object($db_query);
  }


  function xtc_db_affected_rows($link='db_link') {
    global ${$link};

    return mysql_affected_rows(${$link});
  }


  function xtc_db_insert_id($link='db_link') {
    global ${$link};

    return mysql_insert_id(${$link});
  }


  function xtc_db_set_charset($charset, $link='db_link') {
    global ${$link};
    
    if (function_exists('mysql_set_charset')) { //requires MySQL 5.0.7 or later
      mysql_set_charset($charset, ${$link});
    } else {
      xtc_db_query('SET NAMES '.$charset);
    }  
  }


  function xtc_db_connect($server=DB_SERVER, $username=DB_SERVER_USERNAME, $password=DB_SERVER_PASSWORD, $database=DB_DATABASE, $link='db_link') {
    global ${$link};

    if (!function_exists('mysql_connect')) {
      die ('Call to undefined function: mysql_connect(). Please install the MySQL Connector for PHP');
    }

    if (USE_PCONNECT == 'true') {
      ${$link} = @mysql_pconnect($server, $username, $password);
    } else {
      ${$link} = @mysql_connect($server, $username, $password);
    }

    if (${$link}) {
      if (!@mysql_select_db($database, ${$link})) {
        xtc_db_error('', mysql_errno(${$link}), mysql_error(${$link}));
        return false;
      }
    } else {
      xtc_db_error('', mysql_errno(), mysql_error());
      return false;
    }
    
    if (version_compare(xtc_db_get_server_info(), '5.0.0', '>=')) {
      xtc_db_query("SET SESSION sql_mode=''");
    }

    // set charset defined in configure.php
    if (!defined('DB_SERVER_CHARSET')) {
      define('DB_SERVER_CHARSET','latin1');
    }
    xtc_db_set_charset(DB_SERVER_CHARSET);

    return ${$link};
  }


  function xtc_db_data_seek($db_query, $row_number, $cq=false) {

    if (defined('DB_CACHE') && DB_CACHE == 'true' && $cq) {
      if (!count($db_query)) {
        return;
      }
      return $db_query[$row_number];
    } else {
      if (!is_array($db_query)) {
        return mysql_data_seek($db_query, $row_number);
      }
    }
  }


  function xtc_db_error($query, $errno, $error) { 
  
    // Deliver 503 Error on database error (so crawlers won't index the error page)
    if (!defined('DIR_FS_ADMIN')) {
      //header("HTTP/1.1 503 Service Temporarily Unavailable");
      //header("Status: 503 Service Temporarily Unavailable");
      //header("Connection: Close");
    }
    
    // Send an email to the shop owner if a sql error occurs
    if (defined('EMAIL_SQL_ERRORS') && EMAIL_SQL_ERRORS == 'true') {
      require_once (DIR_FS_INC.'xtc_php_mail.inc.php');
      $subject = 'DATA BASE ERROR AT - ' . STORE_NAME;
      $message = '<b style="color:#ff0000;">' . $errno . ' - ' . $error . '<br><br>' . $query . '<br><br>Request URL: ' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'].'<br><br><small style="color:#ff0000">[XT SQL Error]</small></b>';
      xtc_php_mail(STORE_OWNER_EMAIL_ADDRESS, 
                   STORE_OWNER, 
                   STORE_OWNER_EMAIL_ADDRESS, 
                   '', 
                   '', 
                   STORE_OWNER_EMAIL_ADDRESS, 
                   STORE_OWNER, 
                   '', 
                   '', 
                   $subject, 
                   nl2br($message), 
                   $message);
    }
    
    trigger_error($errno.' - '.$error.'<br/><br/>'.$query, E_USER_WARNING);    
  }


  function xtc_db_fetch_array(&$db_query, $cq=false, $result_type=MYSQL_ASSOC) {

    if ($db_query === false) {
      return false;
    }
    if (defined('DB_CACHE') && DB_CACHE=='true' && $cq) {
      if (!is_array($db_query) || !count($db_query)) {
        return false;
      }
      $curr = current($db_query);
      next($db_query);
      return $curr;
    } else {
      if (is_array($db_query)) {
        $curr = current($db_query);
        next($db_query);
        return $curr;
      }
      return mysql_fetch_array($db_query, $result_type);
    }
  }


  function xtc_db_fetch_row(&$db_query, $cq=false) {

    if ($db_query === false) {
      return false;
    }
    if (defined('DB_CACHE') && DB_CACHE=='true' && $cq) {
      if (!is_array($db_query) || !count($db_query)) {
        return false;
      }
      $curr = current($db_query);
      $curr = array_values($curr);
      next($db_query);
      return $curr;
    } else {
      if (is_array($db_query)) {
        $curr = current($db_query);
        next($db_query);
        return $curr;
      }
      return mysql_fetch_row($db_query);
    }
  }


  function xtc_db_query($query, $link='db_link') {
    global ${$link};

    if (defined('STORE_DB_TRANSACTIONS') && STORE_DB_TRANSACTIONS == 'true') {    
      $queryStartTime = array_sum(explode(" ",microtime()));
    }
    
    if (stripos(trim($query), 'INSERT INTO '.TABLE_CONFIGURATION.' ') !== false
        || stripos(trim($query), "INSERT INTO '".TABLE_CONFIGURATION."' ") !== false
        || stripos(trim($query), 'INSERT INTO `'.TABLE_CONFIGURATION.'` ') !== false
        ) 
    {
      str_replace('INSERT INTO', 'REPLACE INTO', $query);
      str_replace('insert into', 'REPLACE INTO', $query);
    }
    
    $result = mysql_query($query, ${$link}) or xtc_db_error($query, mysql_errno(${$link}), mysql_error(${$link}));

    if (defined('STORE_DB_TRANSACTIONS') && STORE_DB_TRANSACTIONS == 'true') {
      $queryEndTime = array_sum(explode(" ",microtime())); 
      $processTime = number_format(round($queryEndTime - $queryStartTime, 5), 5, '.', '');

      if (defined('STORE_DB_SLOW_QUERY') && ((STORE_DB_SLOW_QUERY == 'true' && $processTime >= STORE_DB_SLOW_QUERY_TIME) || STORE_DB_SLOW_QUERY == 'false')) {
        xtc_db_slow_query_log($processTime, $query, 'QUERY');
      }
      $result_error = mysql_error(${$link});
      if ($result_error) {
        xtc_db_slow_query_log($processTime, $result_error, 'ERROR');
      }

      require_once(DIR_FS_INC.'auto_include.inc.php');
      foreach(auto_include(DIR_FS_CATALOG.'includes/extra/db_query/','php') as $file) require ($file);
    }

    return $result;
  }


  function xtc_db_input($string, $link='db_link') {
    global ${$link};

    if (function_exists('mysql_real_escape_string')) {
      return mysql_real_escape_string($string, ${$link});
    } elseif (function_exists('mysql_escape_string')) {
      return mysql_escape_string($string);
    }

    return addslashes($string);
  }


  function xtc_db_num_rows($db_query, $cq=false) {
    if ($db_query === false) {
      return false;
    }
    if (defined('DB_CACHE') && DB_CACHE == 'true' && $cq) {
      if (!count($db_query)) {
        return false;
      }
      return count($db_query);
    } else {
      if (!is_array($db_query)) {
        return mysql_num_rows($db_query);
      }
    }
  }
*/