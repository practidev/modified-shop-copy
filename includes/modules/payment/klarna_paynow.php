<?php
/* -----------------------------------------------------------------------------------------
   $Id: klarna_paynow.php 14593 2022-06-28 10:59:17Z GTB $

   modified eCommerce Shopsoftware
   http://www.modified-shop.org

   Copyright (c) 2009 - 2013 [www.modified-shop.org]
   -----------------------------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------------------------*/

// include needed classes
require_once(DIR_FS_EXTERNAL.'klarna/classes/KlarnaPayment.php');


class klarna_paynow extends KlarnaPayment {
  var $code, $title, $description, $enabled;

  function __construct() {
    global $order;

    $this->code = 'klarna_paynow';
    $this->klarna_code = 'pay_now';

    KlarnaPayment::__construct($this->code);

    if (!defined('RUN_MODE_ADMIN') && is_object($order)) {
      $this->update_status();
    }
  }


  function selection() {
    $data = $this->get_method();
        
    $info = '<div id="klarna-payments-pay-now"></div>
             <script>var klarna_'.$this->klarna_code.'_result = false;</script>';
    
    $_SESSION['klarna']['script'][$this->klarna_code] = '          
          Klarna.Payments.load({
            container: "#klarna-payments-pay-now",
            payment_method_category: "'.$this->klarna_code.'"
          });';
    
    return array(
      'id' => $this->code, 
      'module' => ((MODULE_PAYMENT_KLARNA_TEXT != '') ? MODULE_PAYMENT_KLARNA_TEXT.' ' : '').decode_utf8($data['name']), 
      'description' => $info,
    );
  }

}