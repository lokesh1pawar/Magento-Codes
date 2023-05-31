<?php

namespace Plumtree\RestrictProduct\Helper;

use Magento\Customer\Model\Session as CustomerSession;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\App\Helper\Context;
use Magento\Catalog\Api\ProductRepositoryInterface;


class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    public const CUSTOMER_GROUP_RESTRICT = 'restrict_product/general/restrict_customer_group';

    // protected ScopeConfigInterface $scopeConfig;
    protected CustomerSession $customerSession;
    protected StoreManagerInterface $storeManager;
    protected $productRepository;


    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        StoreManagerInterface $storeManager,
        // ScopeConfigInterface $scopeConfig,
        CustomerSession $customerSession,
        \Magento\Catalog\Api\ProductRepositoryInterface $productRepository

    ) {
        parent::__construct($context);
        $this->customerSession = $customerSession;
        // $this->scopeConfig = $scopeConfig;
        $this->storeManager = $storeManager;
        $this->productRepository = $productRepository;

    }

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
            // print_r($groupArray);
            // exit;

            if (in_array($customerGroup, $groupArray)) {
                return true;
            }
        }

        return false;
    }

    public function isRestrictedSku($sku)
    {
        $restrictedSkus = $this->scopeConfig->getValue(
            'restrict_product/general/restricted_skus',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $this->storeManager->getStore()->getStoreId()
        );

        // $restrictedSkuArray = str_getcsv($restrictedSkus);
        $restrictedSkuArray = explode(",", (string)$restrictedSkus);
        // print_r($restrictedSkuArray);
        // exit;

        if (in_array($sku, $restrictedSkuArray, true)) {
            return true;
        }

        return false;
    }

    public function getProductById($id)
{
    return $this->productRepository->getById($id);
}

}
