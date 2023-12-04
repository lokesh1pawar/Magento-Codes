<?php
namespace Mageplaza\BetterPopup\Cron;

use Psr\Log\LoggerInterface;
use Magento\Framework\Stdlib\DateTime\TimezoneInterface;
use Magento\SalesRule\Model\ResourceModel\Rule\CollectionFactory as RuleCollectionFactory;
use Magento\SalesRule\Api\RuleRepositoryInterface;


class CouponExpire
{
    // protected $timezone; 
    private $timezone;
    protected $ruleCollectionFactory;
    protected $ruleRepository;
    private $salesRule;

    public function __construct(
        \Magento\Framework\Stdlib\DateTime\TimezoneInterface $timezone,
        RuleCollectionFactory $ruleCollectionFactory,
        RuleRepositoryInterface $ruleRepository,
        \Magento\SalesRule\Model\RuleFactory $salesRule,
        \Magento\Framework\App\State $state,
        \Magento\SalesRule\Model\ResourceModel\Rule $ruleResource,
        \Magento\SalesRule\Model\ResourceModel\Rule\Collection $collection

    ) {

        $this->timezone = $timezone;
        $this->ruleRepository = $ruleRepository;
        $this->ruleCollectionFactory = $ruleCollectionFactory;
        $this->salesRule = $salesRule;
        $this->state = $state;
        $this->ruleResource = $ruleResource;
        $this->collection = $collection;

    }

    public function execute()
    {
        $writer = new \Zend_Log_Writer_Stream(BP . '/var/log/expire-coupon-4dec.log');
        $logger = new \Zend_Log();
        $logger->addWriter($writer);

        $startDate = date("Y-m-d",strtotime('-1 days')); // yesterday date
        $endDate = date("Y-m-d"); // current date

        $rules = $this->ruleCollectionFactory->create()
                    ->addFieldToFilter('is_active', 1)
                    ->addFieldToFilter('name',['eq'=>'10-50% Discount Mystery'])
                    ->addFieldToFilter('from_date', array('from'=>$startDate, 'to'=>$endDate)); 

         foreach ($rules as $rule) {
                
                $logger->info("Loop");
                $logger->info($rule);
                $curntDateTime = new \DateTime($this->timezone->date()->format('Y-m-d H:i:s'));
                $ruleDateTime = new \DateTime($rule->getCurrentTime());
            
                // Calculate the difference in minutes:
                $interval = $curntDateTime->diff($ruleDateTime);
                $hours = $interval->h;
                $hours = $hours + ($interval->days * 24);

                $logger->info("Hours: " . $hours);
                               
                // if($hours > 1){
                //     $r = $rule->getRuleId();
                //     $logger->info("Rule ID:" . $r);
                //     // $rule->setIsActive(0);
                //     $rule->setStatus(CouponInterface::STATUS_INACTIVE);
                //     $rule->save();
                //     // $rule->delete();
                // }     
                
                if($hours > 1){
                    $r = $rule->getRuleId();
                    $logger->info("Rule ID:" . $r);
                
                    // Load the rule using the Rule Factory
                    $ruleToSave = $this->salesRule->create();
                    $this->ruleResource->load($ruleToSave, $r);
                
                    // Set the rule as inactive and save using the Rule Resource Model
                    $ruleToSave->setIsActive(0);
                    $this->ruleResource->save($ruleToSave);
                }
                  
             }
    }

}
