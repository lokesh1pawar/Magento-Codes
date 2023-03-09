<?php
namespace Plumtree\CustomerAttribute\Block\Order;

use Magento\Sales\Block\Order\Info as SalesInfo;

class Info extends SalesInfo
{
    /**
     * @var string
     */
    protected $_template = 'Plumtree_CustomerAttribute::order/info.phtml';
}