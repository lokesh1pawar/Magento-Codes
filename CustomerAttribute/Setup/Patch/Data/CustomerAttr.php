<?php
/**
* Copyright © Magento, Inc. All rights reserved.
* See COPYING.txt for license details.
*/

/**
 * Created By : Lokesh Pawar
 */
declare (strict_types = 1);

namespace Plumtree\CustomerAttribute\Setup\Patch\Data;

use Magento\Catalog\Ui\DataProvider\Product\ProductCollectionFactory;
use Magento\Customer\Model\Customer;
use Magento\Eav\Model\Config;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Setup\Patch\PatchRevertableInterface;
use Psr\Log\LoggerInterface;

/**
 * Class CustomerAttribute for Create Customer Attribute using Data Patch.
 * @package RH\Helloworld\Setup\Patch\Data
 */
class CustomerAttr implements DataPatchInterface, PatchRevertableInterface
{
   /**
    * @var ModuleDataSetupInterface
    */
   private $moduleDataSetup;

   /**
    * @var EavSetupFactory
    */
   private $eavSetupFactory;
   
   /**
    * @var ProductCollectionFactory
    */
   private $productCollectionFactory;
   
   /**
    * @var LoggerInterface
    */
   private $logger;
   
   /**
    * @var Config
    */
   private $eavConfig;
   
   /**
    * @var \Magento\Customer\Model\ResourceModel\Attribute
    */
   private $attributeResource;

   /**
    * CustomerAttribute Constructor
    * @param EavSetupFactory $eavSetupFactory
    * @param Config $eavConfig
    * @param LoggerInterface $logger
    * @param \Magento\Customer\Model\ResourceModel\Attribute $attributeResource
    * @param \Magento\Framework\Setup\ModuleDataSetupInterface $moduleDataSetup
    */
   public function __construct(
       EavSetupFactory $eavSetupFactory,
       Config $eavConfig,
       LoggerInterface $logger,
       \Magento\Customer\Model\ResourceModel\Attribute $attributeResource,
       \Magento\Framework\Setup\ModuleDataSetupInterface $moduleDataSetup
   ) {
       $this->eavSetupFactory = $eavSetupFactory;
       $this->eavConfig = $eavConfig;
       $this->logger = $logger;
       $this->attributeResource = $attributeResource;
       $this->moduleDataSetup = $moduleDataSetup;
   }

   /**
    * {@inheritdoc}
    */
   public function apply()
   {
       $this->moduleDataSetup->getConnection()->startSetup();
       $this->addPhoneAttribute();
       $this->moduleDataSetup->getConnection()->endSetup();
   }

   /**
    * @throws \Magento\Framework\Exception\AlreadyExistsException
    * @throws \Magento\Framework\Exception\LocalizedException
    * @throws \Zend_Validate_Exception
    */
   public function addPhoneAttribute()
   {
       $eavSetup = $this->eavSetupFactory->create();
       $eavSetup->addAttribute(
           \Magento\Customer\Model\Customer::ENTITY,
           'po_field',
           [
               'type' => 'varchar',
               'label' => 'PO#',
               'input' => 'text',
               'required' => 0,
               'visible' => 1,
               'user_defined' => 1,
               'sort_order' => 999,
               'position' => 999,
               'system' => 0
           ]
       );

       $attributeSetId = $eavSetup->getDefaultAttributeSetId(Customer::ENTITY);
       $attributeGroupId = $eavSetup->getDefaultAttributeGroupId(Customer::ENTITY);

       $attribute = $this->eavConfig->getAttribute(Customer::ENTITY, 'po_field');
       $attribute->setData('attribute_set_id', $attributeSetId);
       $attribute->setData('attribute_group_id', $attributeGroupId);

       $attribute->setData('used_in_forms', [
           'adminhtml_customer',
           'adminhtml_customer_address',
           'customer_account_edit',
           'customer_address_edit',
           'customer_register_address',
           'customer_account_create',
           'checkout_register'
       ]);

       $this->attributeResource->save($attribute);
   }

   /**
    * {@inheritdoc}
    */
   public static function getDependencies()
   {
       return [];
   }

   /**
    *
    */
   public function revert()
   {
   }

   /**
    * {@inheritdoc}
    */
   public function getAliases()
   {
       return [];
   }
}