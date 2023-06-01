<?php

namespace Plumtree\RestrictProduct\Helper;

use Magento\Customer\Model\Session as CustomerSession;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\App\Helper\Context;
use Magento\Catalog\Api\ProductRepositoryInterface;


class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    public const CUSTOMER_GROUP_RESTRICT = 'restrict_product/general/restrict_customer_group';

    protected CustomerSession $customerSession;
    protected StoreManagerInterface $storeManager;
    protected $productRepository;


    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        StoreManagerInterface $storeManager,
        CustomerSession $customerSession,
        \Magento\Catalog\Api\ProductRepositoryInterface $productRepository

    ) {
        parent::__construct($context);
        $this->customerSession = $customerSession;
        $this->storeManager = $storeManager;
        $this->productRepository = $productRepository;

    }
    
        // For Customer Group values from configuration 

      public function isCustomerInAllowedGroup(): bool
    {
        if ($this->customerSession->isLoggedIn()) {
            $customerGroup = $this->customerSession->getCustomer()->getGroupId();

            $groupList = $this->scopeConfig->getValue(
                self::CUSTOMER_GROUP_RESTRICT,
                \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
                $this->storeManager->getStore()->getStoreId()
            );

            $groupArray = explode(",", (string)$groupList);

            if (in_array($customerGroup, $groupArray)) {
                return true;
            }
        }
             return false;
     }

            // For Comma Saprated SKU from configuration

            public function isRestrictedSku($sku)
            {
                $restrictedSkus = $this->scopeConfig->getValue(
                    'restrict_product/general/restricted_skus',
                    \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
                    $this->storeManager->getStore()->getStoreId()
                );

                $restrictedSkuArray = explode(",", (string)$restrictedSkus);

                if (in_array($sku, $restrictedSkuArray, true)) {
                    return true;
                }
                return false;
            }

            // For Custom URL path from configuration

        public function cmsRedirectUrl()
            {
                $cmsRedirect = $this->scopeConfig->getValue(
                    'restrict_product/general/restricted_csm_redirect',
                    \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
                    $this->storeManager->getStore()->getStoreId()
                );
                return $cmsRedirect;
            }

            // For Custom Message from configuration

        public function customMsg()
            {
                $msg = $this->scopeConfig->getValue(
                    'restrict_product/general/restricted_after_msg',
                    \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
                    $this->storeManager->getStore()->getStoreId()
                );
                return $msg;
            }

            // For get Current Product ID

            public function getProductById($id)
                {
                    return $this->productRepository->getById($id);
                }

}
