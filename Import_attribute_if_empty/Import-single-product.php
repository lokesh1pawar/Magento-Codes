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
        //    $this->logger->addInfo("Cronjob Helloword is executed.");
        $writer = new \Zend_Log_Writer_Stream(BP . '/var/log/lokesh-swatch.log');
        $logger = new \Zend_Log();
        $logger->addWriter($writer);
        $logger->info("Cron Swatch running...");


        $currentProduct = $this->productRepository->getById(23626);

        $productId = $currentProduct->getId();

      //   $collection = $this->productFactory->create();

      //   $collection->addAttributeToFilter('swatch_data', ['null' => true]) // filter products where swatch_data is null
      //   $collection->addAttributeToSort('created_at', 'DESC');


      // $collection->setPageSize(100) 
      // ->setCurPage(1);  


      // foreach ($collection as $product) {
          // $productId = $product->getId();
          // $output->writeln('Product ID: ' . $productId);
      
          // Load the product model
          // $currentProduct = $this->productRepository->getById($productId);

          // For get text value of attribute like : Black, Blue, Green
          // $color = $product->getAttributeText('color');  
          // $size = $product->getAttributeText('size');
      //    $cs = $currentProduct->getSwatchData();
      //    $output->writeln("Swatch Data ". $cs);

      $sd = $currentProduct->getSwatchData();
    //   $output->writeln($sd);
    //   $logger->info($sd);
    $logger->info(print_r($sd, true));


          // Only set swatch_data if it's null
      if ($currentProduct->getSwatchData() === null) {
        //   $output->writeln('Product ID: ' . $productId);
      $logger->info($productId);

           
          // For get html option value of attribute like: 231, 223, 222
          $color = $currentProduct->getColor();
          $size = $currentProduct->getSize();

        //   $output->writeln('Color Value : ' . $color);
        //   $output->writeln('Size Value : ' . $size);
        $logger->info($color);
        $logger->info($size);

        

          $sd = $currentProduct->getSwatchData();
        //  $logger->info($sd);
         $logger->info(print_r($sd, true));


        //   $output->writeln($sd);

          // Set the swatch_data attribute
          $swatchData = 'color=' . $color . '&size=' . $size;

      //    $output->writeln("Swatch Data ". $swatchData);
         // $output->writeln($swatchData);
        //   $output->writeln("-------");

          $currentProduct->setData('swatch_data', $swatchData);

          // Save the product
          $this->productRepository->save($currentProduct);
      // }
  

  }

      }
}
