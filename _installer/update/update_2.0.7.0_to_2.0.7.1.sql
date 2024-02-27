# -----------------------------------------------------------------------------------------
#  $Id: update_2.0.7.0_to_2.0.7.1.sql 14536 2022-06-15 13:18:52Z GTB $
#
#  modified eCommerce Shopsoftware
#  http://www.modified-shop.org
#
#  Copyright (c) 2009 - 2013 [www.modified-shop.org]
#  -----------------------------------------------------------------------------------------

#GTB - 2022-06-14 - changed database_version
INSERT INTO `database_version` (`version`) VALUES ('MOD_2.0.7.1');

#GTB - 2022-06-14 - fix empty payment
UPDATE `orders` SET `payment_class` = 'no_payment' WHERE `payment_class` = '';
UPDATE `orders` SET `payment_method` = 'no_payment' WHERE `payment_method` = '';

#GTB - 2022-06-15 - change orders_status_name
ALTER TABLE `orders_status` MODIFY `orders_status_name` VARCHAR(128) NOT NULL;

#GTB - 2022-06-15 - change shipping_status_name
ALTER TABLE `shipping_status` MODIFY `shipping_status_name` VARCHAR(128) NOT NULL;

# Keep an empty line at the end of this file for the db_updater to work properly