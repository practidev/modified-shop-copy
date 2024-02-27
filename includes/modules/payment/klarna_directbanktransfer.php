<?php
/* -----------------------------------------------------------------------------------------
   $Id: klarna_directbanktransfer.php 14593 2022-06-28 10:59:17Z GTB $

   modified eCommerce Shopsoftware
   http://www.modified-shop.org

   Copyright (c) 2009 - 2013 [www.modified-shop.org]
   -----------------------------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------------------------*/

// include needed classes
require_once(DIR_FS_EXTERNAL.'klarna/classes/KlarnaPayment.php');


class klarna_directbanktransfer extends KlarnaPayment {
  var $code, $title, $description, $enabled;

  function __construct() {
    global $order;

    $this->code = 'klarna_directbanktransfer';
    $this->klarna_code = 'direct_bank_transfer';

    KlarnaPayment::__construct($this->code);

    if (!defined('RUN_MODE_ADMIN') && is_object($order)) {
      $this->update_status();
    }
  }


  function selection() {
    $data = $this->get_method();
        
    $info = '<div id="klarna-payments-direct-bank-transfer"></div>
             <script>var klarna_'.$this->klarna_code.'_result = false;</script>';
    
    $_SESSION['klarna']['script'][$this->klarna_code] = '          
          Klarna.Payments.load({
            container: "#klarna-payments-direct-bank-transfer",
            payment_method_category: "'.$this->klarna_code.'"
          });';
    
    return array(
      'id' => $this->code, 
      'module' => ((MODULE_PAYMENT_KLARNA_TEXT != '') ? MODULE_PAYMENT_KLARNA_TEXT.' ' : '').decode_utf8($data['name']), 
      'description' => $info,
    );
  }


  function pre_confirmation_check() {    
    if (isset($_POST['klarna'])) {
      $_SESSION['klarna'] = array_merge($_SESSION['klarna'], $_POST['klarna']);  
    }
    return false;
  }

}