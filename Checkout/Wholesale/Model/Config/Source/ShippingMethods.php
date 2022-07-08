<?php
namespace Checkout\Wholesale\Model\Config\Source;

class ShippingMethods extends \Magento\Framework\Model\AbstractModel
{


    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfigInterface,
        \Magento\Shipping\Model\Config $shippingConfig,
        array $data = []
    ) {
        $this->scopeConfig = $scopeConfigInterface;
        $this->_shippingConfig = $shippingConfig;
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }
    public function getActiveShippingMethod() 
    {
        $shippings = $this->_shippingConfig->getActiveCarriers();
        $methods = array();
        foreach($shippings as $shippingCode => $shippingModel) {
            if($carrierMethods = $shippingModel->getAllowedMethods()) {
                foreach ($carrierMethods as $methodCode => $method) {
                    $carrierTitle = $this->scopeConfig->getValue('carriers/'. $shippingCode.'/title');
                    $methods[] = array('value'=>$shippingCode,'label'=>$carrierTitle);
                }
            }
        }
        return $methods;
    }

    public function toOptionArray(): array
    {
        return $this->getActiveShippingMethod();
    }
}
