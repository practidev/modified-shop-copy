<?php
/*
 * 888888ba                 dP  .88888.                    dP
 * 88    `8b                88 d8'   `88                   88
 * 88aaaa8P' .d8888b. .d888b88 88        .d8888b. .d8888b. 88  .dP  .d8888b.
 * 88   `8b. 88ooood8 88'  `88 88   YP88 88ooood8 88'  `"" 88888"   88'  `88
 * 88     88 88.  ... 88.  .88 Y8.   .88 88.  ... 88.  ... 88  `8b. 88.  .88
 * dP     dP `88888P' `88888P8  `88888'  `88888P' `88888P' dP   `YP `88888P'
 *
 *                          m a g n a l i s t e r
 *                                      boost your Online-Shop
 *
 * -----------------------------------------------------------------------------
 * (c) 2010 - 2021 RedGecko GmbH -- http://www.redgecko.de
 *     Released under the MIT License (Expat)
 * -----------------------------------------------------------------------------
 */

defined('_VALID_XTC') or die('Direct Access to this location is not allowed.');
require_once(DIR_MAGNALISTER_MODULES.'magnacompatible/listings/MagnaCompatibleInventoryView.php');

class OttoInventoryView extends MagnaCompatibleInventoryView {

    public function __construct() {
        parent::__construct();
        $this->saveDeletedLocally = false;
    }

    /*
     * overwritten by eBay-like function
     * to get shop prices and quantities
     * + some performance by doing queries for the entire item list instead of for each item
     */
    public function prepareInventoryData() {
        global $magnaConfig;
        // fetch shop prices and quantities, like in eBay
        $aGetInventoryResult = $this->getInventory();
        if (($aGetInventoryResult === false) || empty($aGetInventoryResult['DATA'])) {
            return;
        }
        $this->renderableData = $aGetInventoryResult['DATA'];
        $language = $magnaConfig['db'][$this->magnasession['mpID']]['otto.lang'];
        $SKUarr = array();
        $SKUlist = '';
        foreach ($this->renderableData as $item) {
            $SKUarr[] = $item['SKU'];
        }
        $SKUarr = array_unique($SKUarr);
        $character_set_client = MagnaDB::gi()->mysqlVariableValue('character_set_client');
        $character_set_system = MagnaDB::gi()->mysqlVariableValue('character_set_system');
        if (('utf8mb3' == $character_set_client) || ('utf8mb4' == $character_set_client)) {
            $character_set_client = 'utf8';
        }
        if (('utf8mb3' == $character_set_system) || ('utf8mb4' == $character_set_system)) {
            $character_set_system = 'utf8';
        }
        if (('utf8' == $character_set_system) && ('utf8' != $character_set_client)) {
            arrayEntitiesToLatin1($SKUarr);
        }
        foreach ($SKUarr as $currentSKU) {
            $SKUlist .= ", '".MagnaDB::gi()->escape($currentSKU)."'";
        }
        $SKUlist = ltrim($SKUlist, ', ');
        if (!empty($SKUlist)) {
            if ('artNr' == getDBConfigValue('general.keytype', '0')) {
                $ShopDataForSimpleItems = MagnaDB::gi()->fetchArray('
	                    SELECT DISTINCT p.products_model SKU, p.products_id products_id, 
	                           CAST(p.products_quantity AS SIGNED) ShopQuantity, p.products_price ShopPrice,
	                           pd.products_name ShopTitle 
	                      FROM '.TABLE_PRODUCTS.' p, '.TABLE_PRODUCTS_DESCRIPTION.' pd
	                     WHERE p.products_id=pd.products_id
	                           AND pd.language_id='.$language.'
	                           AND p.products_model IN ('.$SKUlist.')
	                ');
            } else {
                $ShopDataForSimpleItems = MagnaDB::gi()->fetchArray('
	                    SELECT DISTINCT CONCAT(\'ML\',p.products_id) SKU, p.products_id products_id, 
	                           CAST(p.products_quantity AS SIGNED) ShopQuantity, p.products_price ShopPrice,
	                           pd.products_name ShopTitle
	                      FROM '.TABLE_PRODUCTS.' p, '.TABLE_PRODUCTS_DESCRIPTION.' pd
	                     WHERE p.products_id=pd.products_id
	                           AND pd.language_id='.$language.'
	                           AND CONCAT(\'ML\',p.products_id) IN ('.$SKUlist.')
	                ');
                $ShopDataForSimpleItems2 = MagnaDB::gi()->fetchArray('
	                    SELECT DISTINCT p.products_id SKU, p.products_id products_id, 
	                           CAST(p.products_quantity AS SIGNED) ShopQuantity, p.products_price ShopPrice,
	                           pd.products_name ShopTitle
	                      FROM '.TABLE_PRODUCTS.' p, '.TABLE_PRODUCTS_DESCRIPTION.' pd
	                     WHERE p.products_id=pd.products_id
	                           AND pd.language_id='.$language.'
	                           AND p.products_id IN ('.$SKUlist.')
	                ');
                if (!empty($ShopDataForSimpleItems2)) {
                    $ShopDataForSimpleItems = array_merge($ShopDataForSimpleItems, $ShopDataForSimpleItems2);
                }
            }
            if (getDBConfigValue('general.options', '0', 'old') == 'gambioProperties') {
                if ('artNr' == getDBConfigValue('general.keytype', '0')) {
                    $selectSku = "CONCAT(p.products_model, '-', ppc.combi_model)";
                    $ShopDataForVariationItems = MagnaDB::gi()->fetchArray(eecho("
						SELECT DISTINCT ".$selectSku." AS SKU,
						       ".$selectSku." AS SKUDeprecated,
						       ppc.products_id AS products_id, '' AS variation_attributes,
						       CAST(ppc.combi_quantity AS SIGNED) AS ShopQuantity,
						       ppc.combi_price + p.products_price AS ShopPrice,
						       pd.products_name AS ShopTitle
						  FROM products_properties_combis ppc, ".TABLE_PRODUCTS." p, ".TABLE_PRODUCTS_DESCRIPTION." pd
						 WHERE     ppc.products_id = p.products_id
						       AND ppc.products_id = pd.products_id
						       AND pd.language_id = '$language'
						       AND ".$selectSku." IN (".$SKUlist.")", false));
                } else {
                    $ShopDataForVariationItems = array();
                    foreach ($SKUarr as $sku) {
                        $combisId = magnaSKU2aID($sku, false, true);
                        $ShopDataForVariationItems[] = MagnaDB::gi()->fetchRow("
								SELECT '$sku' AS SKU, '$sku' AS SKUDeprecated,
						   	ppc.products_id AS products_id, '' AS variation_attributes,
						   	CAST(ppc.combi_quantity AS SIGNED) AS ShopQuantity,
						   	ppc.combi_price + p.products_price AS ShopPrice,
						   	pd.products_name AS ShopTitle
							FROM products_properties_combis ppc, ".TABLE_PRODUCTS." p, ".TABLE_PRODUCTS_DESCRIPTION." pd
					   	WHERE ppc.products_id=p.products_id
								AND ppc.products_id=pd.products_id
								AND pd.language_id='$language'
								AND ppc.products_properties_combis_id = '$combisId'");
                    }
                }
            } else {
                // if there are more problems with not existing master sku,
                // we can filter by existing MasterSku where $SKUarr will filled in this method
                // => now here to use magnaSKU2pID only here (performance)
                $aSkusWithExistingMaster = array();
                foreach ($this->renderableData as $item) {
                    if ((int)magnaSKU2pID(empty($item['MasterSKU']) ? $item['SKU'] : $item['MasterSKU']) !== 0) {
                        $aSkusWithExistingMaster[] = MagnaDB::gi()->escape($item['SKU']);
                    }
                }
                if (empty($aSkusWithExistingMaster)) {
                    $ShopDataForVariationItems = array();
                } else {
                    if (('utf8' == $character_set_system) && ('utf8' != $character_set_client)) {
                        arrayEntitiesToLatin1($aSkusWithExistingMaster);
                    }
                    $sSkusWithExistingMaster = '"'.implode('", "', $aSkusWithExistingMaster).'"';
                    $ShopDataForVariationItems = MagnaDB::gi()->fetchArray(eecho('
	                        SELECT DISTINCT v.'.mlGetVariationSkuField().' AS SKU, v.variation_products_model AS SKUDeprecated,
	                            v.products_id products_id, variation_attributes,
	                            CAST(v.variation_quantity AS SIGNED) ShopQuantity, v.variation_price + p.products_price ShopPrice, pd.products_name ShopTitle
	                        FROM '.TABLE_MAGNA_VARIATIONS.' v, '.TABLE_PRODUCTS.' p, '.TABLE_PRODUCTS_DESCRIPTION.' pd
	                        WHERE v.products_id=p.products_id
	                            AND v.products_id=pd.products_id
	                            AND pd.language_id='.$language.'
	                            AND (
	                                    v.'.mlGetVariationSkuField().' IN ('.$sSkusWithExistingMaster.') 
	                                    OR v.variation_products_model IN ('.$sSkusWithExistingMaster.')
	                            )
	                    ', false));
                }
            }

            $ShopDataForItemsBySKU = array();
            foreach ($ShopDataForSimpleItems as $ShopDataForSimpleItem) {
                $ShopDataForItemsBySKU[$ShopDataForSimpleItem['SKU']] = $ShopDataForSimpleItem;
                unset ($ShopDataForItemsBySKU[$ShopDataForSimpleItem['SKU']]['SKU']);
                $ShopDataForItemsBySKU[$ShopDataForSimpleItem['SKU']]['ShopVarText'] = '';
            }
            foreach ($ShopDataForVariationItems as &$ShopDataForVariationItem) {
                if (('utf8' == $character_set_system) && ('utf8' != $character_set_client)) {
                    $ShopDataForVariationItem['SKU'] = utf8_encode($ShopDataForVariationItem['SKU']);
                }
                $ShopDataForItemsBySKU[$ShopDataForVariationItem['SKU']] = $ShopDataForVariationItem;
                unset ($ShopDataForItemsBySKU[$ShopDataForVariationItem['SKU']]['SKU']);
                $ShopDataForItemsBySKU[$ShopDataForVariationItem['SKUDeprecated']] = &$ShopDataForItemsBySKU[$ShopDataForVariationItem['SKU']];
            }
        } else {
            $ShopDataForItemsBySKU = array();
        }

        #echo print_m($this->renderableData, '$this->renderableData');
        #echo print_m($ShopDataForItemsBySKU, '$ShopDataForItemsBySKU');

        foreach ($this->renderableData as &$item) {
            $itemProductData = json_decode($item['ProductData'], true);
            $item['MarketplaceTitle'] = $itemProductData[0]['Title'];
            $item['MarketplaceTitleShort'] = (mb_strlen($item['MarketplaceTitle'], 'UTF-8') > $this->settings['maxTitleChars'] + 2)
                ? (fixHTMLUTF8Entities(mb_substr($item['MarketplaceTitle'], 0, $this->settings['maxTitleChars'], 'UTF-8')).'&hellip;')
                : fixHTMLUTF8Entities($item['MarketplaceTitle']);
            if (isset($ShopDataForItemsBySKU[$item['SKU']])) {
                $item['ProductsID'] = $ShopDataForItemsBySKU[$item['SKU']]['products_id'];
                $item['ShopQuantity'] = $ShopDataForItemsBySKU[$item['SKU']]['ShopQuantity'];
                $item['ShopPrice'] = $ShopDataForItemsBySKU[$item['SKU']]['ShopPrice'];
                $item['Title'] = $ShopDataForItemsBySKU[$item['SKU']]['ShopTitle'];
                $item['TitleShort'] = (mb_strlen($item['Title'], 'UTF-8') > $this->settings['maxTitleChars'] + 2)
                    ? (fixHTMLUTF8Entities(mb_substr($item['Title'], 0, $this->settings['maxTitleChars'], 'UTF-8')).'&hellip;')
                    : (fixHTMLUTF8Entities($item['Title']));

                $item['ShopVarText'] = isset($ShopDataForItemsBySKU[$item['SKU']]['ShopVarText'])
                    ? $ShopDataForItemsBySKU[$item['SKU']]['ShopVarText']
                    : '&nbsp;';
            } else {
                $item['ShopQuantity'] = $item['ShopPrice'] = $item['Title'] = $item['TitleShort'] = '&mdash;';
                $item['ShopVarText'] = '&nbsp;';
                $item['ProductsID'] = 0;
            }
        }
    }

    protected function getFields() {
        return array(
            'SKU' => array(
                'Label' => ML_LABEL_SKU,
                'Sorter' => 'sku',
                'Getter' => 'getSKU', /** @uses getSKU */
                'Field' => null
            ),
            'ShopTitle' => array(
                'Label' => ML_LABEL_SHOP_TITLE,
                'Sorter' => null,
                'Getter' => 'getTitle', /** @uses getTitle */
                'Field' => null,
            ),

            'ProductUrl' => array(
                'Label' => 'URL',
                'Sorter' => 'itemtitle',
                'Getter' => 'getProductUrl',
                'Field' => null,
            ),
            'Price' => array(
                'Label' => ML_OTTO_PRICE_SHOP_OTTO,
                'Sorter' => 'price',
                'Getter' => 'getItemPrice', /** @uses getItemPrice */
                'Field' => null
            ),
            'Quantity' => array(
                'Label' => ML_OTTO_STOCK_SHOP_OTTO,
                'Sorter' => 'quantity',
                'Getter' => 'getItemQuantity', /** @uses getItemQuantity */
                'Field' => null,
            ),
            'DateAdded' => array(
                'Label' => ML_GENERIC_CHECKINDATE,
                'Sorter' => 'dateadded',
                'Getter' => 'getItemDateAdded', /** @uses getItemDateAdded */
                'Field' => null
            ),
            'DateUpdated' => array(
                'Label' => ML_LAST_SYNC,
                'Sorter' => 'lastsync',
                'Getter' => 'getItemLastSync', /** @uses getItemLastSync */
                'Field' => null
            ),
            'Status' => array(
                'Label' => ML_GENERIC_STATUS,
                'Sorter' => null,
                'Getter' => 'getItemStatus', /** @uses getItemStatus */
                'Field' => null
            ),
        );
    }

    protected function getSKU($item) {
        return '<td>'.fixHTMLUTF8Entities($item['SKU'], ENT_COMPAT).'</td>';
    }

    protected function getProductUrl($item) {
        if (!empty($item['ProductUrl'])) {
            return '<td><a class="ml-js-noBlockUi" href="'.$item['ProductUrl'].'" target="_blank">LINK</a></td>';
        }
        return '<td>&mdash;</td>';
    }

    protected function getItemPrice($item) {
        if ($item['ShopPrice'] > 0) {
            $sShopPrice = $this->simplePrice->setPriceAndCurrency($item['ShopPrice'], $this->mpCurrency)->addTaxByPID($item['ProductsID'])->format();
        } else {
            $sShopPrice = '&mdash;';
        }
        $item['Currency'] = isset($item['Currency']) ? $item['Currency'] : $this->mpCurrency;
        $sOttoPrice = $this->simplePrice->setPriceAndCurrency($item['Price'], $item['Currency'])->format();
        return '<td>'.$sShopPrice.' / '.$sOttoPrice/*.'<br />'.print_m($item, '$item')*/.'</td>';
    }

    protected function getItemQuantity($item) {
        return '<td>' . $item['ShopQuantity'] . ' / ' . $item['Quantity'] . '<br />' . date("d.m.Y", $item['LastSync']) . ' &nbsp;&nbsp;<span class="small">' . date("H:i", $item['LastSync']) . '</span></td>';
    }

    protected function getItemLastSync($item) {
        $item['LastSync'] = ((isset($item['DateUpdated'])) ? strtotime($item['DateUpdated']) : '');
        return '<td>'.date("d.m.Y", $item['LastSync']).' &nbsp;&nbsp;<span class="small">'.date("H:i", $item['LastSync']).'</span>'.'</td>';
    }

    protected function getItemStatus($item) {
        if ('active' == $item['StatusProduct']) {
            return '<td>'.ML_GENERIC_INVENTORY_STATUS_ACTIVE.'</td>';
        } elseif ('waiting' == $item['StatusProduct']) {
            return '<td>'.ML_OTTO_STATUS_PRODUCT_IS_CREATED.'</td>';
        } elseif ('creating' == $item['StatusProduct']) {
            return '<td>'.ML_OTTO_STATUS_PRODUCT_IS_CREATING.'</td>';
        } elseif ('pending_delete' == $item['StatusProduct']) {
            return '<td>'.ML_OTTO_STATUS_PRODUCT_IS_PENDING_DELETE.'</td>';
        } elseif ('pending_creation' == $item['StatusProduct']) {
            return '<td>'.ML_OTTO_STATUS_PRODUCT_IS_PENDING_CREATION.'</td>';
        } elseif ('pending_update' == $item['StatusProduct']) {
            return '<td>'.ML_OTTO_STATUS_PRODUCT_IS_PENDING_UPDATE.'</td>';
        } else {
            return '<td>&mdash;</td>';
        }
    }
}
