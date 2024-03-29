<?php
/* --------------------------------------------------------------
   $Id: orders.php 15004 2023-02-16 16:02:13Z GTB $   

   modified eCommerce Shopsoftware
   http://www.modified-shop.org

   Copyright (c) 2009 - 2013 [www.modified-shop.org]
   -----------------------------------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce; www.oscommerce.com 
   (c) 2003      nextcommerce; www.nextcommerce.org
   (c) 2006      xt:Commerce; www.xt-commerce.com

   Released under the GNU General Public License 
   --------------------------------------------------------------*/
define('TEXT_BANK', 'Bank Collection');
define('TEXT_BANK_OWNER', 'Account Holder:');
define('TEXT_BANK_NUMBER', 'Account Number:');
define('TEXT_BANK_BLZ', 'Bank Code:');
define('TEXT_BANK_NAME', 'Bank:');
define('TEXT_BANK_BIC', 'BIC:');
define('TEXT_BANK_IBAN', 'IBAN:');
define('TEXT_BANK_FAX', 'Collect Authorization will be approved via Fax');
define('TEXT_BANK_STATUS', 'Verify Status:');
define('TEXT_BANK_PRZ', 'Method of Verify:');
define('TEXT_BANK_OWNER_EMAIL', 'E-Mail-Address Account Holder:');

define('TEXT_BANK_ERROR_1', 'Accountnumber and Bank Code are not compatible!<br />Please try again!');
define('TEXT_BANK_ERROR_2', 'Sorry, we are unable to proof this account number!');
define('TEXT_BANK_ERROR_3', 'Account number not proofable! Method of Verify not implemented');
define('TEXT_BANK_ERROR_4', 'Account number technically not proofable!<br />Please try again!');
define('TEXT_BANK_ERROR_5', 'Bank Code not found!<br />Please try again.!');
define('TEXT_BANK_ERROR_8', 'No match for your Bank Code or Bank Code not given!');
define('TEXT_BANK_ERROR_9', 'No account number given!');
define('TEXT_BANK_ERRORCODE', 'Errorcode:');

define('HEADING_TITLE', 'Orders');
define('HEADING_TITLE_SEARCH', 'Order ID:');
define('HEADING_TITLE_STATUS', 'Status:');

define('TABLE_HEADING_COMMENTS', 'Comments');
define('TABLE_HEADING_CUSTOMERS', 'Customers');
define('TABLE_HEADING_ORDER_TOTAL', 'Order Total');
define('TABLE_HEADING_DATE_PURCHASED', 'Date Purchased');
define('TABLE_HEADING_ACTION', 'Action');
define('TABLE_HEADING_QUANTITY', 'Qty.');
define('TABLE_HEADING_PRODUCTS_MODEL', 'Model');
define('TABLE_HEADING_PRODUCTS', 'Products');
define('TABLE_HEADING_TAX', 'Tax');
define('TABLE_HEADING_TOTAL', 'Total');
define('TABLE_HEADING_STATUS', 'Status');
define('TABLE_HEADING_PRICE_EXCLUDING_TAX', 'Price (ex)');
define('TABLE_HEADING_PRICE_INCLUDING_TAX', 'Price (inc)');
define('TABLE_HEADING_TOTAL_EXCLUDING_TAX', 'Total (ex)');
define('TABLE_HEADING_TOTAL_INCLUDING_TAX', 'Total');
define('TABLE_HEADING_AFTERBUY','Afterbuy');
define('TABLE_HEADING_CUSTOMER_NOTIFIED', 'Customer Notified');
define('TABLE_HEADING_DATE_ADDED', 'Date Added');

define('ENTRY_CUSTOMER', 'Customer:');
define('ENTRY_SOLD_TO', 'SOLD TO:');
define('ENTRY_TELEPHONE', 'Telephone:');
define('ENTRY_DELIVERY_TO', 'Delivery To:');
define('ENTRY_SHIP_TO', 'SHIP TO:');
define('ENTRY_SHIPPING_ADDRESS', 'Shipping Address:');
define('ENTRY_PICKUP_ADDRESS', 'Pickup Address:');
define('ENTRY_BILLING_ADDRESS', 'Billing Address:');
define('ENTRY_PAYMENT_METHOD', 'Payment Method:');
define('ENTRY_SHIPPING_METHOD', 'Shipping Method:');
define('ENTRY_SUB_TOTAL', 'Sub-Total:');
define('ENTRY_TAX', 'Tax:');
define('ENTRY_SHIPPING', 'Shipping:');
define('ENTRY_TOTAL', 'Total:');
define('ENTRY_DATE_PURCHASED', 'Date Purchased:');
define('ENTRY_STATUS', 'Status:');
define('ENTRY_DATE_LAST_UPDATED', 'Date Last Updated:');
define('ENTRY_NOTIFY_CUSTOMER', 'Notify Customer:');
define('ENTRY_NOTIFY_COMMENTS', 'Append Comments:');
define('ENTRY_PRINTABLE', 'Print Invoice');

define('TEXT_INFO_HEADING_DELETE_ORDER', 'Delete Order');
define('TEXT_INFO_DELETE_INTRO', 'Are you sure you want to delete this order?');
define('TEXT_INFO_RESTOCK_PRODUCT_QUANTITY', 'Restock product quantity');
define('TEXT_DATE_ORDER_CREATED', 'Date Created:');
define('TEXT_DATE_ORDER_LAST_MODIFIED', 'Last Modified:');
define('TEXT_INFO_PAYMENT_METHOD', 'Payment Method:');
define('TEXT_INFO_SHIPPING_METHOD', 'Shipping Method:');

define('TEXT_ALL_ORDERS', 'All Orders');
define('TEXT_NO_ORDER_HISTORY', 'No Order History Available');

define('EMAIL_SEPARATOR', '------------------------------------------------------');
define('EMAIL_TEXT_SUBJECT', 'Order Update');
define('EMAIL_TEXT_ORDER_NUMBER', 'Order Number:');
define('EMAIL_TEXT_INVOICE_URL', 'Detailed Invoice:');
define('EMAIL_TEXT_DATE_ORDERED', 'Date Ordered:');
define('EMAIL_TEXT_STATUS_UPDATE', 'Your order has been updated to the following status.' . "\n\n" . 'New status: %s' . "\n\n" . 'Please reply to this E-Mail if you have any questions.' . "\n");
define('EMAIL_TEXT_COMMENTS_UPDATE', 'The comments for your order are' . "\n\n%s\n\n");

define('ERROR_ORDER_DOES_NOT_EXIST', 'Error: Order does not exist.');
define('SUCCESS_ORDER_UPDATED', 'Success: Order has been successfully updated.');
define('WARNING_ORDER_NOT_UPDATED', 'Warning: Nothing to change. The order was not updated.');

define('TABLE_HEADING_DISCOUNT','Discount');
define('ENTRY_CUSTOMERS_GROUP','Customers Group:');
define('TEXT_VALIDATING','Not validated');

// BOF - Tomcraft - 2010-04-22 - Added a missing language definition
define('TEXT_PRODUCTS', 'Products');
// EOF - Tomcraft - 2010-04-22 - Added a missing language definition

//BOF - web28 - 2010-03-20 - Send Order by Admin
define('COMMENT_SEND_ORDER_BY_ADMIN' , 'Order confirmation is sent');
define('BUTTON_ORDER_CONFIRMATION', 'Send order confirmation');
define('SUCCESS_ORDER_SEND', 'Order confirmation sent successfully');
//EOF - web28 - 2010-03-20 - Send Order by Admin

// web28 2010-12-07 add new defines
define('ENTRY_CUSTOMERS_ADDRESS', 'Customers Address:');
define('TEXT_ORDER', 'Order:');
define('TEXT_ORDER_HISTORY', 'Order History:');
define('TEXT_ORDER_STATUS', 'Order Status:');

define('TABLE_HEADING_ORDERS_ID', 'Order ID');
define('TEXT_SHIPPING_TO', 'Shipping to');

define('TABLE_HEADING_COMMENTS_SENT', 'Comment is sent');

define('TABLE_HEADING_TRACK_TRACE', 'Track &amp; Trace:');
define('TABLE_HEADING_CARRIER', 'Shipping');
define('TABLE_HEADING_PARCEL_LINK', 'Shipment number / parcel label number / order number / shipment id / tracking number');

define('TEXT_INFO_HEADING_REVERSE_ORDER', 'Reverse order');
define('TEXT_INFO_REVERSE_INTRO', 'Are you sure you want to reverse this order?');

define('TABLE_HEADING_SHIPCLOUD', 'Shipcloud:');
define('TABLE_HEADING_PARCEL_ID', 'Parcel ID');
define('TEXT_SHIPCLOUD_STANDARD', 'Standard');
define('TEXT_SHIPCLOUD_ONE_DAY', 'Express');
define('TEXT_SHIPCLOUD_ONE_DAY_EARLY', 'Express 10:00');
define('TEXT_SHIPCLOUD_RETURNS', 'Retour');
define('TEXT_SHIPCLOUD_LETTER', 'Post Letter');
define('TEXT_SHIPCLOUD_BOOKS', 'Post Book');
define('TEXT_SHIPCLOUD_PARCEL_LETTER', 'Post Parcel Letter');
define('TEXT_WEIGHT_PLACEHOLDER', 'Weight / Kg');
define('TEXT_SHIPCLOUD_INSURANCE_NO', 'higher insurance no');
define('TEXT_SHIPCLOUD_INSURANCE_YES', 'higher insurance yes');
define('TEXT_SHIPCLOUD_BULK', 'Bulk');
define('TEXT_SHIPCLOUD_PARCEL', 'Parcel');
define('TEXT_SHIPCLOUD_DPAG_WARENPOST', 'Warenpost');
define('TEXT_SHIPCLOUD_DPAG_WARENPOST_UNTRACKED', 'Warenpost (untracked)');
define('TEXT_SHIPCLOUD_DPAG_WARENPOST_SIGNATURE', 'Warenpost (signature)');

define('DOWNLOAD_LABEL', 'Download Label');
define('CREATE_LABEL', 'Create Label');
define('TEXT_DELETE_SHIPMENT_SUCCESS', 'shipcloud Label deleted.');
define('TEXT_LABEL_CREATED', 'Label created.');
define('TEXT_CARRIER_ERROR', 'Carrier not activated in your shipcloud account or invalid API key.');
define('TEXT_CARRIER_PLACEHOLDER_1', 'Package description');
define('TEXT_CARRIER_PLACEHOLDER_2', 'Shipment description');

define('TEXT_DOWNLOADS', 'Downloads');
define('TABLE_HEADING_FILENAME', 'Filename');
define('TABLE_HEADING_EXPIRES', 'Expires date');
define('TABLE_HEADING_DOWNLOADS', 'Number Downloads');
define('TABLE_HEADING_DAYS', 'Number days');

define('ENTRY_SEND_TRACKING_INFO', 'Shipping information:');

define('TEXT_ORDERS_STATUS_FILTER', 'Orders status filter');

define('TABLE_HEADING_DATE', 'Date');

define('BUTTON_ORDER_MAIL_STEP', 'Send order mail');
define('COMMENT_SEND_ORDER_MAIL_STEP' , 'Order mail is sent');
define('SUCCESS_ORDER_MAIL_STEP_SEND', 'Order mail sent successfully');

define('TABLE_HEADING_INVOICE_NUMBER', 'Invoice No.');
define('BUTTON_BILL', 'Create Invoice Number');
define('NOT_ASSIGNED', 'not yet assigned!');
?>