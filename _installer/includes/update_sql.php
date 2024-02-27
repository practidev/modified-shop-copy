<?php
/* -----------------------------------------------------------------------------------------
   $Id: update_sql.php 15514 2023-10-11 12:08:04Z GTB $

   modified eCommerce Shopsoftware
   http://www.modified-shop.org

   Copyright (c) 2009 - 2013 [www.modified-shop.org]
   -----------------------------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------------------------*/

  if (isset($_SESSION['db_update'])) {
    $db_update = $_SESSION['db_update'];
  }

  if ($action == 'processnow') {
    $db_update = array();
    unset($_SESSION['db_update']);
    
    $db_update['starttime'] = time();
    $db_update['num_tables'] = ((isset($sql_data_array) && is_array($sql_data_array)) ? count($sql_data_array) : 0);
    $db_update['ready'] = 0;
    $db_update['step'] = 1;
    $db_update['start'] = -1;
    
    if (isset($sql_data_array) && is_array($sql_data_array)) {
      $sql_data_array = array_map('base64_encode', $sql_data_array);
      file_put_contents(DIR_FS_INSTALLER.'update/complete.sql', json_encode($sql_data_array, JSON_PRETTY_PRINT));
      $_POST['sql_files'] = array(
        'complete.sql'
      );
    }
    $db_update['sql_files'] = $_POST['sql_files'];

    $_SESSION['db_update'] = $db_update;
  }

  if ($action == 'sql_update_process'
      && $db_update['num_tables'] > $db_update['start']
      )
  {
    // update table
    for ($j=$db_update['start']; $j<($db_update['start'] + $db_update['step']); $j++) {
      if (isset($sql_data_array)
          && is_array($sql_data_array)
          )
      {
        $sql_array = array_slice($sql_data_array, $j, $db_update['step']);
                  
        foreach ($sql_array as $sql) {
          execute_sql($sql);
        }
      }
    }

    $db_update['start'] ++;
    $_SESSION['db_update'] = $db_update;

    $sec = time() - $db_update['starttime']; 
    $time = sprintf('%d:%02d Min.', floor($sec/60), $sec % 60);

    $json_output = array();
    $json_output['aufruf'] = $db_update['start'];
    $json_output['nr'] = $db_update['start'];
    $json_output['num_tables'] = $db_update['num_tables'];
    $json_output['time'] = $time;
    $json_output['sql_files'] = $db_update['sql_files'];

    $json_output = json_encode($json_output);
    echo $json_output;
    exit();
  }
