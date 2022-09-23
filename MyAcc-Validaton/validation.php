<?php

namespace Plumtree\AddressValidation\Controller\Street;

use Magento\Store\Model\ScopeInterface;

class Validate extends \Magento\Framework\App\Action\Action
{

    protected $resultPageFactory;
    protected $jsonHelper;
    protected $scopeConfig;
    protected $quote;

    /**
     * Constructor
     *
     * @param \Magento\Framework\App\Action\Context  $context
     * @param \Magento\Framework\Json\Helper\Data $jsonHelper
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Framework\Json\Helper\Data $jsonHelper,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Checkout\Model\CartFactory $cartFactory
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->jsonHelper = $jsonHelper;
        $this->scopeConfig = $scopeConfig;
        $this->quote = $cartFactory->create()->getQuote();
        parent::__construct($context);
    }

    /**
     * Execute view action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
      // exit("D1");
        try {
            $params = $this->getRequest()->getParams();
            $response = 'success';
            $isActive = $this->scopeConfig->isSetFlag('address_validation/general/enable', ScopeInterface::SCOPE_STORE);
            if(!$isActive){
              return $this->jsonResponse($response);
            }
            $shippingAddressOnly = $this->scopeConfig->isSetFlag('address_validation/general/shipping_only', ScopeInterface::SCOPE_STORE);

            if(!isset($params['shippingStreet'])){
              return $this->jsonResponse($response);
            }
            // print_r($params['shippingStreet']);
            $street = '';
            foreach ($params['shippingStreet'] as $_street) {
              $street .= $_street." ";
            }

            // if(preg_match("/\bP(ost|ostal)?([ \.]*(O|0)(ffice)?)?([ \.]*Box)\b/i", strtolower($street))){
           if(preg_match("/(A(.*)|p(.*)?o(.*?)?)(\s+)?(bo?x)?(\s+)?[#]?(\d+)?/i", strtolower($street))){

              $response = 'error';
            }

            if(!$shippingAddressOnly){
                $street = '';
                foreach ($params['billingStreet'] as $_street) {
                  $street .= $_street." ";
                }

                // if(preg_match("/\bP(ost|ostal)?([ \.]*(O|0)(ffice)?)?([ \.]*Box)\b/i", $street)){
                 if(preg_match("/(A(.*)|p(.*)?o(.*?)?)(\s+)?(bo?x)?(\s+)?[#]?(\d+)?/i", $street)){

                  $response = 'error';
                }
            }

            if(isset($params['city'])){
                            $city = $params['city'];
                           if(preg_match("/(A(.*)|p(.*)?o(.*?)?)(\s+)?(bo?x)?(\s+)?[#]?(\d+)?/i", $city)){
                             $response = 'error';
                           }
                         }

            return $this->jsonResponse($response);
        } catch 
        
      // } catch (\Exception $e) {
      //   echo 'ERROR ==> '.$e->getMessage();
      //   exit; }
    
    (\Magento\Framework\Exception\LocalizedException $e) {
            return $this->jsonResponse($e->getMessage());
        } catch (\Exception $e) {
            return $this->jsonResponse($e->getMessage());
        }
    }

    /**
     * Create json response
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function jsonResponse($response = '')
    {
        return $this->getResponse()->representJson(
            $this->jsonHelper->jsonEncode($response)
        );
    }
}
