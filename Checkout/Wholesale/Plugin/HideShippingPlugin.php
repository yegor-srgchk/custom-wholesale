<?php
namespace Checkout\Wholesale\Plugin;

class HideShippingPlugin
{
    public function __construct(
        \Magento\Checkout\Model\Session $checkoutSession,
        \Checkout\Wholesale\Helper\Data $helper
    ) {
        $this->_checkoutSession = $checkoutSession;
        $this->_helper = $helper;
        
    }

    public function aroundCollectCarrierRates(
        \Magento\Shipping\Model\Shipping $subject,
        \Closure $proceed,
        $carrierCode,
        $request
    ) {
        $groupId = $this->_checkoutSession->getQuote()->getCustomerGroupId();
        $qty = $this->_checkoutSession->getQuote()->getItemsQty();
        if ($this->_helper->getGeneralConfig('enable') && $this->_helper->getLargeConfig('enable_large') && $groupId == 5){
            if ($carrierCode != 'freeshipping') {
                    return false;
            } 
            $result = $proceed($carrierCode, $request);
            return $result;

        } elseif ($this->_helper->getGeneralConfig('enable') && $this->_helper->getWholesaleConfig('enable_wholesale') && $groupId == 4){
            if ($this->_helper->getWholesaleConfig('qty') < $qty && $carrierCode != $this->_helper->getShippingWholesale()) {
                return false;
            } elseif ($this->_helper->getWholesaleConfig('qty') > $qty && $carrierCode == 'freeshipping') {
                return false;
            } else {
                $result = $proceed($carrierCode, $request);
                return $result;
            }
        } else {
            $result = $proceed($carrierCode, $request);
            return $result;
        }
    }
}
