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
 * @category N98
 * @package N98_CheckoutFilters
 */
class N98_CheckoutFilters_Block_Adminhtml_System_Config_Form
    extends Mage_Adminhtml_Block_System_Config_Form
{
    /**
     * @return N98_CheckoutFilters_Block_Adminhtml_System_Config_Form
     */
    protected function _initObjects()
    {
        parent::_initObjects();

        $sections = $this->_configFields->getSection($this->getSectionCode(), $this->getWebsiteCode(), $this->getStoreCode());

        /**
         *  Create config field during runtime.
         *
         * Check if we are in sales tab and sub-tab payment or shipping.
         * Then we create SimpleXMLElements for form init.
         */
        if ($sections->tab == 'sales') {
            if (in_array($sections->label, array('Payment Methods', 'Shipping Methods'))) {
                foreach ($sections->groups as $group) {
                    foreach ($group as $subGroup) {
                        if (isset($subGroup->fields)) {
                            $this->_addCustomergroupFieldToConfigGroup($subGroup);
                        }
                    }
                }
            }

            // Add fields only for payment methods
            if (in_array($sections->label, array('Payment Methods'))) {
                foreach ($sections->groups as $group) {
                    foreach ($group as $subGroup) {
                        if (isset($subGroup->fields)) {
                            $this->_addMinYearFieldToConfigGroup($subGroup);
                        }
                    }
                }
            }
        }

        /**
         * Paypal uses a special config tab
         */
        if ($sections->tab == 'sales' && $sections->getName() == 'paypal') {
            if (isset($sections->groups->express)) {
                $this->_addCustomergroupFieldToConfigGroup($sections->groups->express);
                $this->_addMinYearFieldToConfigGroup($sections->groups->express);
            }
            if (isset($sections->groups->wps)) {
                $this->_addCustomergroupFieldToConfigGroup($sections->groups->wps);
                $this->_addMinYearFieldToConfigGroup($sections->groups->wps);
            }
        }

        return $this;
    }

    /**
     * @param $subGroup
     */
    protected function _addMinYearFieldToConfigGroup($subGroup)
    {
        /**
         * Min age in years
         */
        $minAge = $subGroup->fields->addChild('available_min_age');
        $minAge->addAttribute('translate', 'label');
        /* @var $customerGroup Mage_Core_Model_Config_Element */
        $minAge->addChild('label', 'Min age');
        $minAge->addChild('frontend_type', 'text');
        $minAge->addChild('description', 'age in years');
        $minAge->addChild('sort_order', 1001);
        $minAge->addChild('show_in_default', 1);
        $minAge->addChild('show_in_website', 1);
        $minAge->addChild('show_in_store', 1);
    }

    /**
     * @param $subGroup
     */
    protected function _addCustomergroupFieldToConfigGroup($subGroup)
    {
        $customerGroup = $subGroup->fields->addChild('available_for_customer_groups');
        $customerGroup->addAttribute('translate', 'label');
        /* @var $customerGroup Mage_Core_Model_Config_Element */
        $customerGroup->addChild('label', 'Customer Group');
        $customerGroup->addChild('frontend_type', 'multiselect');
        $customerGroup->addChild('source_model', 'adminhtml/system_config_source_customer_group');
        $customerGroup->addChild('sort_order', 1000);
        $customerGroup->addChild('show_in_default', 1);
        $customerGroup->addChild('show_in_website', 1);
        $customerGroup->addChild('show_in_store', 1);
    }
}