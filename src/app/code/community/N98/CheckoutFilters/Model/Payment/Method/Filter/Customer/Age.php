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
 * @copyright  Copyright (c) 1999-2012 netz98 new media GmbH (http://www.netz98.de)
 * @author netz98 new media GmbH <info@netz98.de>
 * @category N98
 * @package N98_CheckoutFilters
 */

/**
 * Filters payment methods by age
 *
 * @category N98
 * @package N98_CheckoutFilters
 */
class N98_CheckoutFilters_Model_Payment_Method_Filter_Customer_Age
    extends N98_CheckoutFilters_Model_Payment_Method_Filter_Abstract
    implements N98_CheckoutFilters_Model_Payment_Method_Filter
{

    /**
     * @var string
     */
    const XML_MIN_AGE_CONFIG_FIELD = 'available_min_age';

    /**
     * @return void
     */
    public function filter()
    {
        $minAge = $this->_getMinAgeByPaymentMethod();

        if (!empty($minAge)) {
            $customerDob = $this->getQuote()->getCustomerDob();
            if (!empty($customerDob)) {
                $customerAge = $this->_calcAge($customerDob);
                if ($customerAge < $minAge) {
                    $this->getResult()->isAvailable = false;
                }
            }
        }
    }

    /**
     * Returns the configured min age.
     * We must do a special handling for paypal which doesn't save the config in the same
     * way as other payment methods.
     * We cannot use the Mage_Paypal_Model_Config model. The config sections are currently
     * hard coded.
     *
     * @return int|null
     */
    protected function _getMinAgeByPaymentMethod()
    {
        switch ($this->getMethodInstance()->getCode()) {
            case Mage_Paypal_Model_Config::METHOD_WPP_DIRECT:
                $minAge = Mage::getStoreConfig('paypal/wpp/' . self::XML_MIN_AGE_CONFIG_FIELD);
                break;

            case Mage_Paypal_Model_Config::METHOD_WPP_EXPRESS:
                $minAge = Mage::getStoreConfig('paypal/express/' . self::XML_MIN_AGE_CONFIG_FIELD);
                break;

            case Mage_Paypal_Model_Config::METHOD_WPS:
                $minAge = Mage::getStoreConfig('paypal/wps/' . self::XML_MIN_AGE_CONFIG_FIELD);
                break;

            default:
                $minAge = $this->getMethodInstance()->getConfigData(self::XML_MIN_AGE_CONFIG_FIELD);
                break;
        }
        return $minAge;
    }

    /**
     * Calculate a persons age
     *
     * @param string|Zend_Date $dob
     * @return integer $age
     */
    public function _calcAge($dob)
    {
        if (is_string($dob)) {
            $dob = new Zend_Date($dob, Zend_Date::ISO_8601);
        }

        $date = Zend_Date::now();
        $currYear = $date->get(Zend_Date::YEAR);
        $currMonth = $date->get(Zend_Date::MONTH);
        $currDay = $date->get(Zend_Date::DAY);

        $birtDay = $dob->get(Zend_Date::DAY);
        $birthMonth = $dob->get(Zend_Date::MONTH);
        $birthYear = $dob->get(Zend_Date::YEAR);

        $age = $currYear - $birthYear - ($currMonth < $birthMonth || ($birthMonth == $currMonth && $currDay < $birtDay));

        return $age;
    }

}
