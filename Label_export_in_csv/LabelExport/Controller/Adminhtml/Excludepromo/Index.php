<?php
namespace Plumtree\LabelExport\Controller\Adminhtml\Excludepromo;

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
            ->addAttributeToFilter('exclude_tag', ['eq' => 1])
            // ->addAttributeToSelect('*');
            ->addAttributeToSelect(['name', 'sku']);

        $csvData = "sku,name\n";

        foreach ($collection as $product) {
            $csvData .= $product->getSku() . ',' . $product->getName() . "\n";
        }

        return 
        $this
        ->fileFactory->create(
            'exclude_promo_export.csv',
            $csvData,
            \Magento\Framework\App\Filesystem\DirectoryList::VAR_DIR,
            'application/octet-stream'
        );
    $resultRedirect = $this->resultRedirectFactory->create();

    }

}