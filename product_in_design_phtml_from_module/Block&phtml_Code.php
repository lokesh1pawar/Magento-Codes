Block code :-

<?php

namespace Plumtree\CatalogExtended\Block\Product\ProductList;

class CurrentProduct extends \Magento\Framework\View\Element\Template
{
    protected $registry;
    protected $_productRepository;

    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Catalog\Api\ProductRepositoryInterface $productRepository,
        array $data = []
    )
    {
        $this->registry = $registry;
        $this->_productRepository = $productRepository;
        parent::__construct($context, $data);
    }

    public function _prepareLayout()
    {
        return parent::_prepareLayout();
    }

    public function getCurrentProduct()
    {
        return $this->_registry->registry('current_product');
    }

    public function getProductById($id)
    {
        return $this->_productRepository->getById($id);
    }
}

?>

Phtml Code:-

   $customBlock = $block->getLayout()->createBlock('Plumtree\CatalogExtended\Block\Product\ProductList\CurrentProduct');
    $product = $customBlock->getProductById($item->getProductId());

     if ($product && $product->getData('doogma_product_style')) : ?>
