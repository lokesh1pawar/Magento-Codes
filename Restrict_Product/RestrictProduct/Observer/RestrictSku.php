<?php

namespace Plumtree\RestrictProduct\Observer;

use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\App\Response\RedirectInterface;
use Magento\Framework\Controller\Result\RedirectFactory;
use Plumtree\RestrictProduct\Helper\Data as RestrictProductHelper;
use Magento\Framework\UrlInterface;


class RestrictSku implements ObserverInterface
{
    protected $restrictProductHelper;
    protected $redirect;
    protected $productRepository;
    protected $responseFactory;
    private $redirectFactory;
    private $url;
   

    public function __construct(
        RestrictProductHelper $restrictProductHelper,
        RedirectInterface $redirect,
        \Magento\Framework\App\ResponseFactory $responseFactory,
        \Magento\Catalog\Api\ProductRepositoryInterface $productRepository,
        RedirectFactory $redirectFactory,
        UrlInterface $url
    ) {
        $this->restrictProductHelper = $restrictProductHelper;
        $this->redirect = $redirect;
        $this->productRepository = $productRepository;
        $this->responseFactory = $responseFactory;
        $this->redirectFactory = $redirectFactory;
        $this->url = $url;
    }

    public function execute(Observer $observer)
{
    $controller = $observer->getEvent()->getControllerAction();
    $productId = $controller->getRequest()->getParam('id');

    // Load the product by ID
    $product = $this->restrictProductHelper->getProductById($productId);
    $sku = $product->getSku();

    if (!$this->restrictProductHelper->isCustomerInAllowedGroup() && $this->restrictProductHelper->isRestrictedSku($sku)) {
        // Redirect to a 404 page
        /** @var Redirect $redirect */

        $resultRedirect = $this->responseFactory->create();
        $resultRedirect->setRedirect($this->url->getUrl('noroute'))->sendResponse('200');
        exit();

        // $redirect = $this->redirectFactory->create();
        //     $redirect->setUrl($this->url->getUrl('noroute'));
        //     $controller->getResponse()->setStatusHeader(302);
        //     $controller->getResponse()->setRedirect($redirect->getRedirectUrl());
        //     return;

            // $resultRedirect = $this->redirectFactory->create();
            // $resultRedirect->setPath('index/index/ghg');
            // // exit;
            // return $resultRedirect;

    }
}



    // public function execute(Observer $observer)
    // {
    //     $productId = $observer->getRequest()->getParam('id');
    //     // print_r($productId);
    //     // exit;
    //     $sku = $observer->getRequest()->getParam('sku');
    //     print_r($sku);
    //     exit;
    //     if (!$this->restrictProductHelper->isCustomerInAllowedGroup() && $this->restrictProductHelper->isRestrictedSku($sku)) {
    //         // Redirect to a 404 page
    //         $this->redirect->redirectErrorPage();
    //     }


    //     $product = $observer->getProduct();
    //     if ($product) {
    //         // Get the current customer by session.
    //         $customer = $this->customerSession->getCustomer();
    //         $customerGroupId = 0;
    //         // If the current customer isn't a Guest
    //         if (!empty($customer->getId())) {
    //             // Modify the customer groupd id
    //             $customerGroupId = $customer->isCustomerInAllowedGroup();
    //         }
    //         //  Get the customer group ids of the current product. The result will be 0,1 or 0 or 1,2,3, etc,...
    //         $restrictCustomerGroup = $product->isCustomerInAllowedGroup();
    //         // Convert the string to array.
    //         $customerGroupIds = explode(',', string($restrictCustomerGroup));
    //         // If the group id of the current customer is in array, redirecting to the 404 page.
    //         if (in_array($customerGroupId, $customerGroupIds)) {
    //             // This helps us to redirect the product detail page to the 404 page, whenever this product is restricted.
    //             $resultRedirect = $this->responseFactory->create();
    //             $resultRedirect->setRedirect($this->url->getUrl('noroute'))->sendResponse('200');
    //             exit();
    //         }
    //     }
    //     return $this;
    // }
    }

