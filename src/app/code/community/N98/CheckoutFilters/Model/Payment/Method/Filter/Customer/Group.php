<?php
/**
 * Created by JetBrains PhpStorm.
 * User: cmuench
 * Date: 15.06.12
 * Time: 11:30
 * To change this template use File | Settings | File Templates.
 */
class N98_CheckoutFilters_Model_Payment_Method_Filter_Customer_Group
    extends N98_CheckoutFilters_Model_Payment_Method_Filter_Abstract
    implements N98_CheckoutFilters_Model_Payment_Method_Filter
{
    /**
     * @var string
     */
    const XML_CUSTOMER_GROUP_CONFIG_FIELD = 'available_for_customer_groups';

    /**
     * @return void
     */
    public function filter()
    {
        $customer = $this->_getCustomer();
        /* @var $customer Mage_Customer_Model_Customer */

        $paymentMethodInstance = $this->getMethodInstance();

        /**
         * Special handling for paypal
         */
        if ($paymentMethodInstance instanceof Mage_Paypal_Model_Standard) {
            $customerGroupConfig = Mage::getStoreConfig('paypal/wps/' . self::XML_CUSTOMER_GROUP_CONFIG_FIELD);
        } elseif ($paymentMethodInstance instanceof Mage_Paypal_Model_Express) {
            $customerGroupConfig = Mage::getStoreConfig('paypal/express/' . self::XML_CUSTOMER_GROUP_CONFIG_FIELD);
        } elseif ($paymentMethodInstance instanceof Mage_Paypal_Model_Direct) {
            $customerGroupConfig = Mage::getStoreConfig('paypal/wpp/' . self::XML_CUSTOMER_GROUP_CONFIG_FIELD);
        } elseif ($paymentMethodInstance instanceof Mage_GoogleCheckout_Model_Payment) {
            $customerGroupConfig = Mage::getStoreConfig('google/checkout/' . self::XML_CUSTOMER_GROUP_CONFIG_FIELD);
        } else {
            $customerGroupConfig = $paymentMethodInstance->getConfigData(self::XML_CUSTOMER_GROUP_CONFIG_FIELD);
        }

        if (!empty($customerGroupConfig)) {
            $methodCustomerGroups = explode(',', $customerGroupConfig);
            if (count($methodCustomerGroups) > 0) {
                if (!in_array($customer->getGroupId(), $methodCustomerGroups)) {
                    $this->getResult()->isAvailable = false;
                }
            }
        }
    }

    /**
     * @return Mage_Customer_Model_Customer
     */
    protected function _getCustomer()
    {
        return Mage::helper('customer')->getCustomer();
    }
}
