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
 * If grandtotal is zero -> filter all payment methods which are not "free".
 *
 * @category N98
 * @package N98_CheckoutFilters
 */
class N98_CheckoutFilters_Model_Payment_Method_Filter_Freepayment
    extends N98_CheckoutFilters_Model_Payment_Method_Filter_Abstract
    implements N98_CheckoutFilters_Model_Payment_Method_Filter
{
    /**
     * @return void
     */
    public function filter()
    {
        if (Mage::getSingleton('n98_checkoutfilters/config')->isFreeHideOther()) {
            /**
             * if total is 0, don't show other payment methods
             */
            if ($this->getMethodInstance()->getCode() != 'free'
                && $this->getQuote()
                && $this->getQuote()->getBaseGrandTotal() == 0
            ) {
                $this->getResult()->isAvailable = false;
            }
        }
    }
}