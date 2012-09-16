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
 * Interface for payment method filters
 *
 * @category N98
 * @package N98_CheckoutFilters
 */
interface N98_CheckoutFilters_Model_Payment_Method_Filter
{
    /**
     * @abstract
     * @param Mage_Sales_Model_Quote $
     * @return mixed
     */
    public function setQuote($quote);

    /**
     * @abstract
     * @param Mage_Payment_Model_Method_Abstract $methodInstance
     * @return mixed
     */
    public function setMethodInstance(Mage_Payment_Model_Method_Abstract $methodInstance);

    /**
     * @abstract
     * @return stdClass
     */
    public function setResult($result);

    /**
     * @abstract
     * @return stdClass
     */
    public function getResult();

    /**
     * @abstract
     * @return void
     */
    public function filter();
}