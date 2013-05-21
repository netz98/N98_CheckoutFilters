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
 * @author cm
 * @category N98
 * @package N98_CheckoutFilters
 */

/**
 * Overwrites shipping model of magento an inject test for customer groups
 *
 * @category N98
 * @package N98_CheckoutFilters
 */
class N98_CheckoutFilters_Model_Shipping_Shipping
    extends Mage_Shipping_Model_Shipping
{
    /**
     * @var string
     */
    const XML_CUSTOMER_GROUP_CONFIG_FIELD = 'available_for_customer_groups';

    /**
     * @param string $carrierCode
     * @param Varien_Object $request
     * @return N98_CheckoutFilters_Model_Shipping_Shipping
     */
    public function collectCarrierRates($carrierCode, $request)
    {
        if (!$this->_checkCarrierByCustomerGroup($carrierCode)) {
            return $this;
        }
        return parent::collectCarrierRates($carrierCode, $request);
    }

    /**
     * Check if carrier can be used by customer groups
     *
     * @param Mage_Shipping_Model_Carrier_Abstract $carrier
     * @return boolean
     */
    protected function _checkCarrierByCustomerGroup($carrierCode)
    {
        if (Mage::app()->getStore()->isAdmin()) {
            $customer = Mage::getSingleton('adminhtml/session_quote')->getCustomer();
        } else {
            /* @var $customer Mage_Customer_Model_Customer */
            $customer = Mage::helper('customer')->getCustomer();
        }

        $carrierCustomerGroupConfig = Mage::getStoreConfig('carriers/' . $carrierCode . '/' . self::XML_CUSTOMER_GROUP_CONFIG_FIELD);

        if (!empty($carrierCustomerGroupConfig)) {
            $carrierCustomerGroups = explode(',', $carrierCustomerGroupConfig);
            if (count($carrierCustomerGroups) > 0) {
                if (!in_array($customer->getGroupId(), $carrierCustomerGroups)) {
                    return false;
                }
            }
        }

        // If nothing was specified the shipping carrier is not blocked!
        return true;
    }
}
