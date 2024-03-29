<?php
/* --------------------------------------------------------------
   $Id: product_thumbnail_images.php 15565 2023-11-13 15:28:12Z GTB $

   XT-Commerce - community made shopping
   http://www.xt-commerce.com

   Copyright (c) 2003 XT-Commerce
   --------------------------------------------------------------

   Released under the GNU General Public License
   --------------------------------------------------------------*/

(defined( '_VALID_XTC' ) || defined('RUN_MODE_TASKS')) or die( 'Direct Access to this location is not allowed.' );

if (!isset($products_image_name_process)) {
  $products_image_name_process = $products_image_name;
}

if (is_file(DIR_FS_CATALOG_THUMBNAIL_IMAGES.$products_image_name_process)) {
  unlink(DIR_FS_CATALOG_THUMBNAIL_IMAGES.$products_image_name_process);
}

$a = new image_manipulation(DIR_FS_CATALOG_ORIGINAL_IMAGES.$products_image_name, PRODUCT_IMAGE_THUMBNAIL_WIDTH, PRODUCT_IMAGE_THUMBNAIL_HEIGHT, DIR_FS_CATALOG_THUMBNAIL_IMAGES.$products_image_name_process, IMAGE_QUALITY, '');

if (PRODUCT_IMAGE_THUMBNAIL_MERGE != '') {
  $string=str_replace("'",'',PRODUCT_IMAGE_THUMBNAIL_MERGE);
  $string=str_replace(')','',$string);
  $string=str_replace('(',DIR_FS_CATALOG_IMAGES,$string);
  $array=explode(',',$string);
  $a->merge($array[0],$array[1],$array[2],$array[3],$array[4]);
}

$a->create();

if (defined('IMAGE_TYPE_EXTENSION') && IMAGE_TYPE_EXTENSION == 'webp') {
  $a->createWebp();
}

unset($products_image_name_process);
