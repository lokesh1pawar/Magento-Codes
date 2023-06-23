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
use Magento\Catalog\Model\ProductFactory;


class RestrictSku implements ObserverInterface
{
    protected $restrictProductHelper;
    protected $redirect;
    protected $productRepository;
    protected $responseFactory;
    private $redirectFactory;
    private $url;
    protected $registry;
    protected $urlInterface;
    private $productFactory;

    
    public function __construct(
        RestrictProductHelper $restrictProductHelper,
        UrlInterface $urlInterface,
        RedirectInterface $redirect,
        \Magento\Framework\App\ResponseFactory $responseFactory,
        \Magento\Catalog\Api\ProductRepositoryInterface $productRepository,
        RedirectFactory $redirectFactory,
        \Magento\Framework\Controller\Result\RedirectFactory $resultRedirectFactory,
        \Magento\Framework\Message\ManagerInterface $messageManager,
        \Magento\UrlRewrite\Model\UrlRewriteFactory $urlRewriteFactory,
        ProductFactory $productFactory,
        \Magento\Framework\Registry $registry

    ) {
        $this->restrictProductHelper = $restrictProductHelper;
        $this->redirect = $redirect;
        $this->resultRedirectFactory = $resultRedirectFactory;
        $this->productRepository = $productRepository;
        $this->responseFactory = $responseFactory;
        $this->redirectFactory = $redirectFactory;
        $this->urlRewriteFactory = $urlRewriteFactory;
        $this->messageManager = $messageManager;
        $this->urlInterface = $urlInterface;
        $this->registry = $registry;
        $this->productFactory = $productFactory;

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

    $productUrl = $this->productRepository->get($sku)->getProductUrl();

         $login_url = $this->urlInterface
         ->getUrl('customer/account/login', 
               array('referer' => base64_encode($productUrl))
           );
    
    if (!$this->restrictProductHelper->isCustomerInAllowedGroup() && $this->restrictProductHelper->isRestrictedSku($sku)) {
       
        // Redirect to Custom Page and Show Custom Message
        $controller = $observer->getControllerAction();
        $this->messageManager->addWarning(__($customMsg));
        $this->redirect->redirect($controller->getResponse(), $login_url);
           }
        }

    }

