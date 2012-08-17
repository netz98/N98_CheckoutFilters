<?php
/**
 * netz98 magento module
 *
 * LICENSE
 *
 * This source file is subject of netz98.
 * You may be not allowed to change the sources
 * without authorization of netz98 new media GmbH.
 *
 * @copyright  Copyright (c) 1999-2010 netz98 new media GmbH (http://www.netz98.de)
 * @category N98
 * @package N98_CheckoutFilters
 */

/**
 * Config model of checkout filter module
 *
 * @category N98
 * @package N98_CheckoutFilters
 */
class N98_CheckoutFilters_Model_Config extends Mage_Core_Model_Abstract
{
    /**
     * @var string
     */
    const XML_PAYMENT_FREE_HIDE_OTHER = 'n98_checkoutfilters/payment/free_hide_other';

    /**
     * @param mixed $store
     * @return bool
     */
    public function isFreeHideOther($store = null)
    {
        return Mage::getStoreConfigFlag(self::XML_PAYMENT_FREE_HIDE_OTHER, $store);
    }
}