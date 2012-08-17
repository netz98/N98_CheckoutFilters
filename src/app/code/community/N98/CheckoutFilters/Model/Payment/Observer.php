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
 * Observer to limit access to customer groups by customer group
 *
 * @category N98
 * @package N98_CheckoutFilters
 */
class N98_CheckoutFilters_Model_Payment_Observer
{
    /**
     * Check if customer group can use the payment method
     *
     * @param Varien_Event_Observer $observer
     * @return bool
     */
    public function methodIsAvailable(Varien_Event_Observer $observer)
    {
        $this->_addFilter('n98_checkoutfilters/payment_method_filter_customer_group', $observer);
        $this->_addFilter('n98_checkoutfilters/payment_method_filter_customer_age', $observer);
        $this->_addFilter('n98_checkoutfilters/payment_method_filter_freepayment', $observer);

        return $observer->getResult()->isAvailable;
    }

    /**
     * @param string $filterClass
     * @param Varien_Event_Observer $observer
     * @return void
     */
    protected function _addFilter($filterClass, Varien_Event_Observer $observer)
    {
        $quote = $observer->getQuote();
        $result = $observer->getResult(); // stdClass with property "isAvailable"
        $paymentMethodInstance = $observer->getMethodInstance();
        /* @var $paymentMethodInstance Mage_Payment_Model_Method_Abstract */
        $customerGroupFilter = Mage::getModel($filterClass);
        $customerGroupFilter->setResult($result);
        $customerGroupFilter->setMethodInstance($paymentMethodInstance);
        $customerGroupFilter->setQuote($quote);
        $customerGroupFilter->filter();
    }
}