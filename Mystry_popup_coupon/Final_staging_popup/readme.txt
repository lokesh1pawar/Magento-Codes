
Additional code for --> when users Order and use 50% of 10 coupons in day when we have to stop making 50% coupon (users have no idea about this) then only 10,20,30,40% coupons will generate on that day & 60 min expire coupon.

----------------------------------------------------------------------
    reexecute:
    $random = rand(1, 5) * 10;
  
    if($random == 50){

        $currentDate = $this->_stdTimezone->date()->format('Y-m-d');

        $collection = $this->collection;
                
       $rules = $collection->addFieldToFilter('is_active', 1)
                           ->addFieldToFilter('discount_amount', 50);
                
       $allRules = $collection->getData();
                
       
       // Count how many rules were used today
          $rulesUsedToday = 0;
                       
          foreach ($allRules as $rule) {
            if (substr($rule['from_date'], 0, 10) == $currentDate) {
                $rulesUsedToday += $rule['times_used'];
            }
        }
        
        if ($rulesUsedToday >= 10) {

            goto reexecute;
        }            
    }
                                
    
        $duration = "+60 minutes";
        $from_time = $this->timezone->date()->format('Y-m-d H:i:s');
        $expiryDate = date('Y-m-d H:i:s', strtotime($duration, strtotime($from_time)));
