<?php

namespace Plumtree\LabelExport\Controller\Adminhtml\Newproduct;

use Magento\Backend\App\Action;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\Framework\App\Response\Http\FileFactory;

class Index extends Action
{
    protected $productCollectionFactory;
    protected $fileFactory;

    public function __construct(
        Action\Context $context,
        CollectionFactory $productCollectionFactory,
        FileFactory $fileFactory
    ) {
        $this->productCollectionFactory = $productCollectionFactory;
        $this->fileFactory = $fileFactory;
        parent::__construct($context);
    }

    public function execute()
    {
        

        $collection = $this->productCollectionFactory->create()
        ->addAttributeToSelect(['name', 'sku', 'news_from_date', 'news_to_date']);

    // $newProductsCount = 0;

    // foreach ($collection as $product) {
    //     $newsFromDate = $product->getNewsFromDate();
    //     $newsToDate = $product->getNewsToDate();
    //     if ($newsFromDate && $newsToDate) {
    //         $newProductsCount++;
    //     }
    // }


    foreach ($collection as $product) {
        $newsFromDate = $product->getNewsFromDate();
        $newsToDate = $product->getNewsToDate();
        if ($newsFromDate && $newsToDate) {
            print_r('SKU: ' . $product->getSku() . ', Name: ' . $product->getName() . "\n");
        }
    }

    // For debugging: print the count of 'new' products
    // print_r('Number of new products: ' . $newProductsCount);

    //         $collection = $this->productCollectionFactory->create()
    //             ->addAttributeToSelect(['name', 'sku', 'news_from_date', 'news_to_date']);
        
    //         $csvData = "sku,name\n";
        
    //         foreach ($collection as $product) {
    //             $newsFromDate = $product->getNewsFromDate();
    //             $newsToDate = $product->getNewsToDate();
    //             if ($newsFromDate && $newsToDate) {
    //                 $csvData .= $product->getSku() . ',' . $product->getName() . "\n";
    //             }
    //         }
        
    //         $this->fileFactory->create(
    //             'new_product_export.csv',
    //             $csvData,
    //             \Magento\Framework\App\Filesystem\DirectoryList::VAR_DIR,
    //             'application/octet-stream'
    //         );
        
    //         $resultRedirect = $this->resultRedirectFactory->create();
    //         return $resultRedirect->setRefererOrBaseUrl();
        
    // }

    // public function _isAllowed(){
    //     return $this->_authorization->isAllowed('Plumtree_LabelExport::newproduct_export');
    // }
}
}