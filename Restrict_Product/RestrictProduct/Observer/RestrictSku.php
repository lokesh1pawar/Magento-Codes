<?php

namespace Plumtree\RestrictProduct\Observer;

use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\App\Response\RedirectInterface;
use Magento\Framework\Controller\Result\RedirectFactory;
use Plumtree\RestrictProduct\Helper\Data as RestrictProductHelper;
use Magento\Framework\UrlInterface;
use Magento\Framework\Controller\ResultFactory;

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
        // ResultFactory $resultFactory,
        RedirectInterface $redirect,
        \Magento\Framework\App\ResponseFactory $responseFactory,
        \Magento\Catalog\Api\ProductRepositoryInterface $productRepository,
        RedirectFactory $redirectFactory,
        UrlInterface $url,
        \Magento\Framework\Controller\Result\RedirectFactory $resultRedirectFactory,
        \Magento\Framework\Message\ManagerInterface $messageManager,
        \Magento\UrlRewrite\Model\UrlRewriteFactory $urlRewriteFactory

    ) {
        $this->restrictProductHelper = $restrictProductHelper;
        $this->redirect = $redirect;
        $this->resultRedirectFactory = $resultRedirectFactory;
        $this->productRepository = $productRepository;
        $this->responseFactory = $responseFactory;
        $this->redirectFactory = $redirectFactory;
        $this->url = $url;
        $this->urlRewriteFactory = $urlRewriteFactory;
        $this->messageManager = $messageManager;

    }

    public function execute(Observer $observer)
{
    $controller = $observer->getEvent()->getControllerAction();
    $productId = $controller->getRequest()->getParam('id');

    // Load the product by ID
    $product = $this->restrictProductHelper->getProductById($productId);
    $sku = $product->getSku();

    $cmsLink =  $this->restrictProductHelper->cmsRedirectUrl();
    $customMsg = $this->restrictProductHelper->customMsg();
   
    
    if (!$this->restrictProductHelper->isCustomerInAllowedGroup() && $this->restrictProductHelper->isRestrictedSku($sku)) {
       
        // Redirect to Custom Page and Show Custom Message
        $controller = $observer->getControllerAction();
        $this->messageManager->addWarning(__($customMsg));
        $this->redirect->redirect($controller->getResponse(), $cmsLink);

           }
        }
    }

