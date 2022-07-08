<?php
namespace Checkout\Wholesale\Model\Config\Source;

class PaymentMethods extends \Magento\Framework\Model\AbstractModel
{
    /**
     * Payment Model Config
     *
     * @var \Magento\Payment\Model\Config
     */
    protected $_paymentConfig;

    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        \Magento\Payment\Model\Config $paymentConfig,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfigInterface,
        array $data = []
    ) {
        $this->_paymentConfig = $paymentConfig;
        $this->_appConfigScopeConfigInterface = $scopeConfigInterface;
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }
    
    /**
     * Get active/enabled payment methods
     * 
     * @return array
     */ 
    public function getActivePaymentMethods() 
    {
        $payments = $this->_paymentConfig->getActiveMethods();
        $methods = array();
        foreach ($payments as $paymentCode => $paymentModel) {
            $paymentTitle = $this->_appConfigScopeConfigInterface
                ->getValue('payment/'.$paymentCode.'/title');
            $methods[$paymentCode] = array(
                'label' => $paymentTitle,
                'value' => $paymentCode
            );
        }
        return $methods;
    }

    public function toOptionArray(): array
    {
        return $this->getActivePaymentMethods();
    }
}
