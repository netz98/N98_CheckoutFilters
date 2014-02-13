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
class N98_CheckoutFilters_Model_Adminhtml_Config_Observer
{
    /**
     * Add fields to full system config object
     *
     * @param Varien_Event_Observer $observer
     * @return N98_CheckoutFilters_Model_Adminhtml_Config_Observer
     */
    public function addFieldsToConfig(Varien_Event_Observer $observer)
    {
        /** @var $config Mage_Core_Model_Config_Base */
        $config = $observer->getEvent()->getData('config');

        $sections = $config->getNode('sections');

        foreach ($sections->children() as $section) {
            /** @var $section Mage_Core_Model_Config_Element */
            $this->createConfigFields($section);
        }

        return $this;
    }

    /**
     * Create config field during runtime.
     *
     * @param Varien_Simplexml_Element $section
     * @return N98_CheckoutFilters_Model_Adminhtml_Config_Observer
     */
    public function createConfigFields($section)
    {
        /**
         * Check if we are in sales tab and sub-tab payment or shipping.
         * Then we create SimpleXMLElements for form init.
         */
        if ($section->tab == 'sales') {
            if (in_array($section->label, array('Payment Methods', 'Shipping Methods'))) {
                foreach ($section->groups as $group) {
                    foreach ($group as $subGroup) {
                        if (isset($subGroup->fields)) {
                            $this->_addCustomergroupFieldToConfigGroup($subGroup);
                        }
                    }
                }
            }

            // Add fields only for payment methods
            if (in_array($section->label, array('Payment Methods'))) {
                foreach ($section->groups as $group) {
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
        if ($section->tab == 'sales' && $section->getName() == 'paypal') {
            if (isset($section->groups->express)) {
                $this->_addCustomergroupFieldToConfigGroup($section->groups->express);
                $this->_addMinYearFieldToConfigGroup($section->groups->express);
            }
            if (isset($section->groups->wps)) {
                $this->_addCustomergroupFieldToConfigGroup($section->groups->wps);
                $this->_addMinYearFieldToConfigGroup($section->groups->wps);
            }
            if (isset($section->groups->wpp)) {
                $this->_addCustomergroupFieldToConfigGroup($section->groups->wpp);
                $this->_addMinYearFieldToConfigGroup($section->groups->wpp);
            }
        }

        /**
         * Ebizmarts_Sagepay uses a special config tab
         */
        if ('sales' == $section->tab && 'sagepaysuite' == $section->getName()) {
            $my_groups = array(
                'sagepayserver',
                'sagepayserver_moto',
                'sagepaydirectpro_moto',
                'sagepaydirectpro',
                'sagepayform',
                'sagepaypaypal',
                'sagepayrepeat'
            );
            foreach ($my_groups as $group) {
                $this_group = $section->groups->{$group};
                $this->_addCustomergroupFieldToConfigGroup($this_group);
                $this->_addMinYearFieldToConfigGroup($this_group);
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
