<?php
namespace MagePlaza\BetterPopup\Controller\Index;

use Exception;
use Psr\Log\LoggerInterface;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\Action;
use Magento\SalesRule\Api\Data\RuleInterface;
use Magento\SalesRule\Api\Data\CouponInterface;
use Magento\Framework\Exception\InputException;
use Magento\SalesRule\Api\RuleRepositoryInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\SalesRule\Api\CouponRepositoryInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\SalesRule\Api\Data\RuleInterfaceFactory;
use Magento\Framework\Controller\ResultFactory;
use \Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\App\Config\ScopeConfigInterface;


class Coupon extends Action
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var CouponRepositoryInterface
     */
    protected $couponRepository;

    /**
     * @var RuleRepositoryInterface
     */
    protected $ruleRepository;

    /**
     * @var Rule
     */
    protected $rule;

    /**
     * @var CouponInterface
     */
    protected $coupon;
    
    /**
     * @var SaleRuleInterface
     */
    protected $saleRule;    
    
    /**
     * @var \Magento\Framework\Controller\Result\JsonFactory
     */
    private $jsonResultFactory;

     /**
     * @var Serializer
     */
    protected $serializer;

    /**
     * @var SalesRule
     */
    private $salesRule;

    /**
     * @var FoundProductRuleFactory
     */
    protected $foundProductRuleFactory;

    /**
     * @var RuleResource
     */
    protected $ruleResource;

    /**
     * @var RuleProductConditionFactory
     */
    protected $ruleProductConditionFactory;

     /**
     * @var timezone
     */
    private $timezone;

    protected $resourceConnection;

    const XML_PATH_IS_ENABLED = 'betterpopup/general/enabled';

    protected $_scopeConfig;
    public function __construct(
        CouponRepositoryInterface $couponRepository,
        RuleRepositoryInterface $ruleRepository,
        RuleInterfaceFactory $rule,
        CouponInterface $coupon,
        LoggerInterface $logger,
        \Magento\SalesRule\Model\Rule $saleRule,
        \Magento\SalesRule\Model\ResourceModel\Rule\Collection $collection,
        \Magento\Framework\Stdlib\DateTime\Timezone $_stdTimezone,
        \Magento\Framework\Stdlib\DateTime\TimezoneInterface $timezone,
        JsonFactory $jsonResultFactory,
        \Magento\Framework\Serialize\SerializerInterface $serializer,
        \Magento\SalesRule\Model\RuleFactory $salesRule,
        \Magento\SalesRule\Model\Rule\Condition\Product\FoundFactory $foundProductRuleFactory,
        \Magento\SalesRule\Model\ResourceModel\Rule $ruleResource,
        \Magento\SalesRule\Model\Rule\Condition\ProductFactory $ruleProductConditionFactory,
        \Magento\Framework\App\ResourceConnection $resourceConnection,
        ScopeConfigInterface $scopeConfig,
        Context $context)
        
    {
        parent::__construct($context);
    
        $this->couponRepository = $couponRepository;
        $this->ruleRepository = $ruleRepository;
        $this->rule = $rule;
        $this->coupon = $coupon;
        $this->logger = $logger;
        $this->saleRule = $saleRule;
        $this->collection = $collection;
        $this->_stdTimezone = $_stdTimezone;
        $this->timezone = $timezone;
        $this->jsonResultFactory = $jsonResultFactory;
        $this->serializer = $serializer;
        $this->salesRule = $salesRule;
        $this->foundProductRuleFactory = $foundProductRuleFactory;
        $this->ruleResource = $ruleResource;
        $this->ruleProductConditionFactory = $ruleProductConditionFactory;
        $this->resourceConnection = $resourceConnection;
        $this->_scopeConfig = $scopeConfig;
    }
    
    public function execute()
    {
		 if (!$this->isModuleEnabled()) {
            $this->_forward('noroute');
            return;
        }						
		$responce = $this->createRule();
        $data = ['success' => $responce['status'], 'percent' => $responce['discount'], 'msg' => $responce['responce']];
        $result = $this->jsonResultFactory->create();
        $result->setData($data);
         return $result;
    }
        

    public function createRule()
  {
    reexecute:
     $random = rand(1, 3) * 10;
     //   $random = 50;  
    if($random == 50){
        goto reexecute; 
       //  $currentDate = $this->_stdTimezone->date()->format('Y-m-d');
       //   $collection = $this->collection;
                
       // $rules = $collection->addFieldToFilter('is_active', 1)
       //                     ->addFieldToFilter('discount_amount', 50);
                
       // $allRules = $collection->getData();
                
       
       // // Count how many rules were used today
       //    $rulesUsedToday = 0;
                       
       //    foreach ($allRules as $rule) {
       //      if (substr($rule['from_date'], 0, 10) == $currentDate) {
       //          $rulesUsedToday += $rule['times_used'];
       //      }
       //  }
        
       //  if ($rulesUsedToday >= 5) {
       //       goto reexecute; 
            
       //  }         
        
        
    } 
     if($random == 40){
         goto reexecute; 

       //  $currentDate = $this->_stdTimezone->date()->format('Y-m-d');
       //   $collection = $this->collection;
                
       // $rules = $collection->addFieldToFilter('is_active', 1)
       //                     ->addFieldToFilter('discount_amount', 40);
                
       // $allRules = $collection->getData();
                
       
       // // Count how many rules were used today
       //    $rulesUsedToday = 0;
                       
       //    foreach ($allRules as $rule) {
       //      if (substr($rule['from_date'], 0, 10) == $currentDate) {
       //          $rulesUsedToday += $rule['times_used'];
       //      }
       //  }
        
       //  if ($rulesUsedToday >= 10) {
       //       goto reexecute; 
            
       //  }         
        
        
    }       
     if($random == 30){

        $currentDate = $this->_stdTimezone->date()->format('Y-m-d');
         $collection = $this->collection;
                
       $rules = $collection->addFieldToFilter('is_active', 1)
                           ->addFieldToFilter('discount_amount', 30);
                
       $allRules = $collection->getData();
                
       
       // Count how many rules were used today
          $rulesUsedToday = 0;
                       
          foreach ($allRules as $rule) {
            if (substr($rule['from_date'], 0, 10) == $currentDate) {
                $rulesUsedToday += $rule['times_used'];
            }
        }
        
        if ($rulesUsedToday >= 100) {
             goto reexecute; 
            
        }         
        
        
    }       
     if($random == 20){

        $currentDate = $this->_stdTimezone->date()->format('Y-m-d');
         $collection = $this->collection;
                
       $rules = $collection->addFieldToFilter('is_active', 1)
                           ->addFieldToFilter('discount_amount', 20);
                
       $allRules = $collection->getData();
                
       
       // Count how many rules were used today
       $rulesUsedToday = 0;

       foreach ($allRules as $rule) {
           if ($rule['from_date'] !== null && substr($rule['from_date'], 0, 10) == $currentDate) {
               $rulesUsedToday += $rule['times_used'];
           }
       }
       
       if ($rulesUsedToday >= 100) {
           goto reexecute;
       }
        
        
    }                 
    
        $duration = "+60 minutes";
        $from_time = $this->timezone->date()->format('Y-m-d H:i:s');
        $expiryDate = date('Y-m-d H:i:s', strtotime($duration, strtotime($from_time)));

        $newRule = $this->salesRule->create();
        $newRule->setName('10-50% Discount Mystery')
            ->setDescription("10-50% Discount Mystery Rule")
            ->setIsAdvanced(true)
            ->setStopRulesProcessing(false)
            ->setDiscountQty($random)
            ->setCustomerGroupIds([0, 1, 2, 3, 4, 5, 6, 7, 8])
            ->setWebsiteIds([1])
            ->setIsRss(0)
            ->setUsesPerCoupon(1)
            ->setDiscountStep(0)
            ->setCouponType(2)
            ->setSimpleAction(RuleInterface::DISCOUNT_ACTION_BY_PERCENT)
            ->setDiscountAmount($random)
            ->setIsActive(true)
            ->setFromDate($from_time)
            ->setToDate($expiryDate); 
         
            
        // First condition
        $condition1 = $this->ruleProductConditionFactory->create()
            ->setType('Magento\SalesRule\Model\Rule\Condition\Product')
            ->setData('attribute', 'category_ids')
            ->setData('operator', '!()')
            ->setValue('797, 259');
        $newRule->getActions()->addCondition($condition1);

        // Second condition
        $condition2 = $this->ruleProductConditionFactory->create()
            ->setType('Magento\SalesRule\Model\Rule\Condition\Product')
            ->setData('attribute', 'exclude_tag')
            ->setData('operator', '!=')
            ->setValue('1');
        $newRule->getActions()->addCondition($condition2);

  
        try {
            $ruleCreate = $newRule->save();

            //If rule generated, Create new Coupon by rule id
            if ($ruleCreate->getRuleId()) {
                $couponCode = 'P' . $this->generateRandomString(3) . 'C' . $this->generateRandomString(1);
                $this->createCoupon($ruleCreate->getRuleId(), $couponCode);

                return array('status' => 1, 'discount' => $random, 'responce' => $couponCode);
            }
        } catch (Exception $exception) {
            $this->logger->error($exception->getMessage());
            return array('status' => 0, 'discount' => $random, 'responce' => $exception->getMessage());

        }
        return array('status' => 0, 'discount' => $random, 'responce' => "Coupon Code not Generated");

        
    }

    public function generateRandomString($length = 1) {
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[random_int(0, strlen($characters) - 1)];
            
        }
        return $randomString;
    }
   
    public function createCoupon(int $ruleId, $couponCode) {
        try {
            /** @var CouponInterface $coupon */
            $coupon = $this->coupon;
            $coupon->setCode($couponCode)
                ->setIsPrimary(1)
                ->setRuleId($ruleId);
    
            /** @var CouponRepositoryInterface $couponRepository */
            $couponRepository = $this->couponRepository;
    
            $couponRepository->save($coupon);
    
            $couponId = $coupon->getCouponId();
    
            $durationn = "+2 minutes";
            $from_time = $this->timezone->date()->format('Y-m-d H:i:s');
            $newExpiryDate = date('Y-m-d H:i:s', strtotime($durationn, strtotime($from_time)));
    
            $this->updateCouponExpirationDate($couponId, $newExpiryDate);
    
            return $couponId;
        } catch (Exception $e) {
            $this->logger->error("Error during coupon creation: " . $e->getMessage());
            return false;
        }
    }

    public function updateCouponExpirationDate($couponId, $newExpiryDate) {
        try {
            /** @var CouponInterface $coupon */
            $coupon = $this->couponRepository->getById($couponId);
            $coupon->setExpirationDate($newExpiryDate);
            $this->couponRepository->save($coupon);
        } catch (NoSuchEntityException $e) {
            $this->logger->error('Coupon with id ' . $couponId . ' does not exist.');
        } catch (InputException $e) {
            $this->logger->error($e->getMessage());
        } catch (LocalizedException $e) {
            $this->logger->error($e->getMessage());
        }
    }

    public function isModuleEnabled()
    {
        return $this->_scopeConfig->getValue(self::XML_PATH_IS_ENABLED);
    }



}
