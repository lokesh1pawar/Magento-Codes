<?php

namespace Plumtree\ImportSwatch\Cron;

class Import
{
     protected $logger;
     private $productRepository;


     public function __construct(
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productFactory,
        \Magento\Catalog\Api\ProductRepositoryInterface $productRepository,
        
    )
    {
        $this->productFactory = $productFactory;
        $this->productRepository = $productRepository;
       
    }

     /**
      * Execute the cron
      *
      * @return void
      */
      public function execute()
      {
        $writer = new \Zend_Log_Writer_Stream(BP . '/var/log/lokesh-swatch.log');
        $logger = new \Zend_Log();
        $logger->addWriter($writer);
        $logger->info("Cron Swatch running...");

        $collection = $this->productFactory->create();
        $collection->addAttributeToSelect('*');
$collection->addAttributeToFilter('color', ['notnull' => true]);
$collection->addAttributeToFilter('size', ['notnull' => true]);
        $collection->addAttributeToSort('created_at', 'DESC');

        $collection->setPageSize(100) ;
                    // ->setCurPage(1);  

        foreach ($collection as $product) {
          $productId = $product->getId();
      
          // Load the product model
       $currentProduct = $this->productRepository->getById($productId);

    //   $sd = $currentProduct->getSwatchData();
    // $logger->info(print_r($sd, true));

      if ($currentProduct->getSwatchData() === null) {

             $logger->info("Product Id". $productId);

          $color = $currentProduct->getColor();
          $size = $currentProduct->getSize();

            $logger->info("color" . $color);  // Log color of all products
            $logger->info("Size" . $size);   // Log size of all products

        //   $sd = $currentProduct->getSwatchData();
        //  $logger->info(print_r($sd, true));

        //   Set the swatch_data attribute
          $swatchData = 'color=' . $color . '&size=' . $size;

          $currentProduct->setData('swatch_data', $swatchData);

          // Save the product
          $this->productRepository->save($currentProduct);
      }
  

  }

      }
}
