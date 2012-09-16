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
 * Abstract filter class
 *
 * @category N98
 * @package N98_CheckoutFilters
 */
class N98_CheckoutFilters_Model_Payment_Method_Filter_Abstract
{
    /**
     * @var Mage_Sales_Model_Quote
     */
    protected $_quote;

    /**
     * @var
     */
    protected $_result;

    /**
     * @var
     */
    protected $_methodInstance;

    /**
     * @param Mage_Sales_Model_Quote $
     *
     * @return N98_CheckoutFilters_Model_Payment_Method_Filter_Abstract
     */
    public function setQuote($quote)
    {
        $this->_quote = $quote;

        return $this;
    }

    /**
     * @return Mage_Sales_Model_Quote
     */
    public function getQuote()
    {
        return $this->_quote;
    }

    /**
     * @return stdClass
     * @return N98_CheckoutFilters_Model_Payment_Method_Filter_Abstract
     */
    public function setResult($result)
    {
        $this->_result = $result;

        return $this;
    }

    /**
     * @return stdClass
     */
    public function getResult()
    {
        return $this->_result;
    }

    /**
     * @param  $methodInstance
     */
    public function setMethodInstance(Mage_Payment_Model_Method_Abstract $methodInstance)
    {
        $this->_methodInstance = $methodInstance;
    }

    /**
     * @return
     */
    public function getMethodInstance()
    {
        return $this->_methodInstance;
    }
}