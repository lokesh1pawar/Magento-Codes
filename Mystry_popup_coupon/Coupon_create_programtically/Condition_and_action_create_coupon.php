 public function createRule()
    {
        reexecute:
        $random = rand(1, 5) * 10;
        if($random == 50){
            $currentDate = $this->_stdTimezone->date()->format('Y-m-d');
            $rules = $this->collection->create()
                        ->addFieldToFilter('is_active', 1)
                        ->addFieldToFilter('from_date', ['eq' => $currentDate])
                        ->addFieldToFilter('discount_amount', 50);
            $count = 1;
        
         foreach ($rules as $rule) {
            if ($count > 10) {
                goto reexecute;
                  }
                $count++;
            }
        }
        
        $newRule = $this->salesRule->create();
        $newRule->setName('10-50% Discount Mystry')
            ->setDescription("10-50% Discount Mystry Rule")
            ->setIsAdvanced(true)
            ->setStopRulesProcessing(false)
            ->setDiscountQty($random)
            ->setCustomerGroupIds([0, 1, 2, 3, 4, 5, 6, 7, 8, 10])
            ->setWebsiteIds([1])
            ->setIsRss(0)
            ->setUsesPerCoupon(1)
            ->setDiscountStep(0)
            ->setCouponType(2)
            ->setSimpleAction(RuleInterface::DISCOUNT_ACTION_BY_PERCENT)
            ->setDiscountAmount($random)
            ->setIsActive(true)
            ->setFromDate(date('Y-m-d'));

      This code is for condition   53 line and below ----> 

        //     $item_found = $this->foundProductRuleFactory->create()
        //     ->setType('Magento\SalesRule\Model\Rule\Condition\Product\Found')
        //     ->setValue(1) // 1 == FOUND
        //     ->setAggregator('all'); // match ALL conditions
        //    $newRule->getConditions()->addCondition($item_found);

            
        // First condition
        $condition1 = $this->ruleProductConditionFactory->create()
            ->setType('Magento\SalesRule\Model\Rule\Condition\Product')
            ->setData('attribute', 'category_ids')
            ->setData('operator', '!()')
            ->setValue('797, 259');
  // $item_found->addCondition($condition1);  also this <--- 
   
        $newRule->getActions()->addCondition($condition1);

        // Second condition
        $condition2 = $this->ruleProductConditionFactory->create()
            ->setType('Magento\SalesRule\Model\Rule\Condition\Product')
            ->setData('attribute', 'exclude_tag')
            ->setData('operator', '!=')
            ->setValue('1');
        $newRule->getActions()->addCondition($condition2);

    // Third condition
        $excludedCategories = [305, 10]; // The categories to exclude
        $condition3 = $this->ruleProductConditionFactory->create()
            ->setType('Magento\SalesRule\Model\Rule\Condition\Product')
            ->setData('attribute', 'category_ids')
            ->setData('attribute_scope', 'parent')
            ->setData('operator', '!()')
            ->setValue(implode(',', $excludedCategories));
        $newRule->getActions()->addCondition($condition3);

        try {
            $ruleCreate = $newRule->save();

            //If rule generated, Create new Coupon by rule id
            if ($ruleCreate->getRuleId()) {
                echo "getting rule id ";
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
