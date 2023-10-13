<?php
namespace Plumtree\Promopopup\Controller\Ajax;

use Magento\Framework\App\Action\Action;
use Magento\Store\Model\ScopeInterface;

class Visitorstatus extends Action
{
    private $resultLayoutFactory;
    private $collectionFactory;
    private $remoteAddress;
    private $ruleProductCondition;
    private $timezone;
    private $salesRule;
    private $mathRandom;
    private $customerGroup;
    private $website;
    private $scopeConfig;
    private $logger;
    private $categoryFactory;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        \Magento\Framework\Stdlib\DateTime\TimezoneInterface $timezone,
        \Magento\Framework\Math\Random $mathRandom,
        \Magento\SalesRule\Model\RuleFactory $salesRule,
        \Magento\Customer\Model\ResourceModel\Group\Collection $customerGroup,
        \Magento\Store\Model\ResourceModel\Website\Collection $website,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\SalesRule\Model\Rule\Condition\Product $ruleProductCondition,
        \Plumtree\Promopopup\Model\ResourceModel\Visitor\CollectionFactory $collectionFactory,
        \Magento\Framework\HTTP\PhpEnvironment\RemoteAddress $remoteAddress,
         \Magento\Catalog\Model\CategoryFactory $categoryFactory
    ) {
        $this->resultJsonFactory = $resultJsonFactory;
        $this->collectionFactory = $collectionFactory;
        $this->remoteAddress = $remoteAddress;
        $this->ruleProductCondition = $ruleProductCondition;
        $this->customerGroup = $customerGroup;
        $this->scopeConfig = $scopeConfig;
        $this->salesRule = $salesRule;
        $this->mathRandom = $mathRandom;
        $this->timezone = $timezone;
        $this->website = $website;
        $this->categoryFactory = $categoryFactory;

        $writer = new \Zend_Log_Writer_Stream(BP . '/var/log/promopopup.log');
        $logger = new \Zend_Log();
        $logger->addWriter($writer);

        parent::__construct($context);
    }

    public function execute()
    {
        $result = $this->resultJsonFactory->create();
        $ip = $this->remoteAddress->getRemoteAddress();
        $collection = $this->collectionFactory->create();
        $collection->addFieldToFilter('ip_address', ['eq' => $ip]);
        //$collection->addFieldToFilter('status', ['eq' => 1]);
        if($collection->count() > 0){
          $result->setData(['success' => false]);
        }else{
          $result->setData(['success' => true]);
          $coupon_code = $this->generateCoupon($ip);
          if(!empty($coupon_code)){
            $duration = $this->scopeConfig->getValue('promopopup/general/duration', ScopeInterface::SCOPE_STORE);
            $duration = (int)$duration.":00";
            $result->setData(['success' => true, 'coupon' => $coupon_code, 'duration' => $duration]);
          }
        }

        return $result;
    }

    protected function generateCoupon($remote_ip)
    {
        try{
          $coupon_code = $this->mathRandom->getRandomString(12);
          $customerGroups = $this->customerGroup->getAllIds();
          $websites = $this->website->getAllIds();
          $discount = $this->scopeConfig->getValue('promopopup/general/discount_amount', ScopeInterface::SCOPE_STORE);
          $duration = $this->scopeConfig->getValue('promopopup/general/duration', ScopeInterface::SCOPE_STORE);
          $from_time = $this->timezone->date()->format('Y-m-d H:i:s');
          if(!empty($duration)){
                    $duration='+'.(int)$duration.' minutes';
                }else{
                    $duration='+30 minutes';
                }
          $to_time = date('Y-m-d H:i:s', strtotime($duration, strtotime($from_time)));

          $shoppingCartPriceRule = $this->salesRule->create();
          $shoppingCartPriceRule->setName('Discount for '.$remote_ip)
                ->setDescription('Promo Popup for unique IP')
                ->setFromDate($from_time)
                ->setToDate($to_time)
                ->setUsesPerCustomer(1)
                ->setUsesPerCoupon(1)
                ->setCouponType(2)
                ->setCouponCode($coupon_code)
                ->setCustomerGroupIds($customerGroups)
                ->setIsActive(1)
                ->setSimpleAction('by_percent')
                ->setDiscountAmount($discount)
                ->setDiscountQty(0)
                ->setApplyToShipping(0)
                ->setTimesUsed(0)
                ->setWebsiteIds($websites);

          $actions = $this->ruleProductCondition
              ->setType('Magento\SalesRule\Model\Rule\Condition\Product')
              ->setData('attribute','brand')
              ->setData('operator','!=')
              ->setValue('HP');
          $shoppingCartPriceRule->getActions()->addCondition($actions);

          $shoppingCartPriceRule->save();
          // $this->logger->info("=========");
          // $this->logger->info("New Promo Created: ".$coupon_code);
          // $this->logger->info("Time from: ".$from_time);
          // $this->logger->info("Time to: ".$to_time);
          return $coupon_code;
        }catch (Exception $e) {
          $this->logger->info("Error: ".$e->getMessage());
        }
    }
    
}
