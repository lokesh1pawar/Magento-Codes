app/code/Plumtree/CatalogExtended/Block/Product/ProductList/ThreeUnitSold.php

<?php

namespace Plumtree\CatalogExtended\Block\Product\ProductList;

use Magento\Framework\View\Element\Template;
use Magento\Sales\Model\ResourceModel\Order\Item\CollectionFactory as OrderItemCollectionFactory;
use Magento\Framework\App\ResourceConnection;

class ThreeUnitsSold extends Template
{
    protected $resourceConnection;

    /**
     * @var OrderItemCollectionFactory
     */
    private $orderItemCollectionFactory;

    /**
     * @param Template\Context $context
     * @param OrderItemCollectionFactory $orderItemCollectionFactory
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        OrderItemCollectionFactory $orderItemCollectionFactory,
        ResourceConnection $resourceConnection,
        array $data = []
    ) {
        $this->resourceConnection = $resourceConnection;
        $this->orderItemCollectionFactory = $orderItemCollectionFactory;
        parent::__construct($context, $data);
    }

    public function getTopSellingProducts()
    {
        $connection = $this->resourceConnection->getConnection();
        $tableName = $this->resourceConnection->getTableName('sales_order_item');

        $select = $connection->select()
            ->from(['oi' => $tableName], ['product_id'])
            ->join(['p' => $this->resourceConnection->getTableName('catalog_product_entity')], 'oi.product_id = p.entity_id', [])
            ->where('oi.created_at >= ?', new \Zend_Db_Expr('NOW() - INTERVAL 1 DAY'))
            ->group('oi.product_id')
            ->having('SUM(oi.qty_ordered) >= 3');

        $results = [];
        $result = $connection->fetchAll($select);
        
        foreach ($result as $row) {
            $results[] = $row['product_id'];
        }
        
        return $results;
    }
}
