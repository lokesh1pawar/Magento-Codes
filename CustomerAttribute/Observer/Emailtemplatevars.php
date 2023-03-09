<?php
namespace Plumtree\CustomerAttribute\Observer;
 
class Emailtemplatevars implements \Magento\Framework\Event\ObserverInterface
{
	protected $helper;
	protected $order;
	public function __construct(
		\Magento\Sales\Model\Order $order
		){

		$this->order = $order;

	}
	public function execute(\Magento\Framework\Event\Observer $observer)
	{

		

		$writer = new \Zend_Log_Writer_Stream(BP . '/var/log/lokesh.log');
		$logger = new \Zend_Log();
		$logger->addWriter($writer);
		$logger->info("Hiiii");
		// $transport
		// $transport = $observer->getTransport();

		// $transport = $observer->getEvent()->getTransport();
		$transport = $observer->getTransport();
         //get order
        $po = $transport->getOrder()->getPoAttribute();
		// $order = $observer->getData('order');
		// $transport = $observer->getTransport();
        $transport['po_attribute'] = $po;
        // $transport['po_attribute'] = 'hiii';
		

		
		// $po = $order['po_attribute'];
		// $po = $order->getPoAttribute();
		// $logger->info($po);

		// $orderData = $this->order->load($order);
		// $itemCollection = $orderData->getItemsCollection();
        // $customer = $orderData->getCustomerId();
        

		// $writer = new \Zend_Log_Writer_Stream(BP . '/var/log/lokesh.log');
		// $logger = new \Zend_Log();
		// $logger->addWriter($writer);
		// $logger->info($orderData->getId());
	
		// $logger->info($customer);
		
		


   

	}
}
