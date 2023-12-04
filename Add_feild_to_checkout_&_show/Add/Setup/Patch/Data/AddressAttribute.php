<?php
/**
 * Copyright © Mucan All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Lokesh\Add\Setup\Patch\Data;

use Atwix\CatalogAdjustments\Block\Adminhtml\Form\Field\SortOrder;
use Magento\Catalog\Model\Category;
use Magento\Catalog\Setup\CategorySetup;
use Magento\Catalog\Setup\CategorySetupFactory;
use Magento\Eav\Model\Config;
use Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface;
use Magento\Eav\Model\Entity\Attribute\SetFactory as AttributeSetFactory;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Setup\Patch\PatchInterface;
use Zend_Validate_Exception;

/**
 * Class AddressAttribute
 */
class AddressAttribute implements DataPatchInterface
{
    const ADDRESS_ATTRIBUTE_CODE = 'house';

    /**
     * @var ModuleDataSetupInterface
     */
    private $moduleDataSetup;

    /**
     * @var Config
     */
    private $eavConfig;

    /**
     * @var EavSetupFactory
     */
    private $_eavSetupFactory;

    /**
     * @var AttributeSetFactory
     */
    private $attributeSetFactory;

    /**
     * @param ModuleDataSetupInterface $moduleDataSetup
     * @param Config $eavConfig
     * @param EavSetupFactory $eavSetupFactory
     * @param AttributeSetFactory $attributeSetFactory
     */
    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        Config                   $eavConfig,
        EavSetupFactory          $eavSetupFactory,
        AttributeSetFactory      $attributeSetFactory
    )
    {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->eavConfig = $eavConfig;
        $this->_eavSetupFactory = $eavSetupFactory;
        $this->attributeSetFactory = $attributeSetFactory;
    }

    /**
     * @inheritDoc
     */
    public static function getDependencies()
    {
        return [];
    }

    /**
     * @inheritDoc
     */
    public function getAliases()
    {
        return [];
    }

    /**
     * Add address attribute
     *
     * @return PatchInterface
     *
     * @throws LocalizedException
     * @throws Zend_Validate_Exception
     */
    public function apply(): PatchInterface
    {
        $eavSetup = $this->_eavSetupFactory->create(['setup' => $this->moduleDataSetup]);
        $eavSetup->addAttribute('customer_address', self::ADDRESS_ATTRIBUTE_CODE, [
            'type' => 'varchar',
            'input' => 'text',
            'label' => 'House Number',
            'visible' => true,
            'required' => false,
            'user_defined' => true,
            'system' => false,
            'group' => 'General',
            'global' => true,
            'visible_on_front' => true,
        ]);

        $attribute = $this->eavConfig->getAttribute('customer_address', self::ADDRESS_ATTRIBUTE_CODE);
        $attribute->setData('used_in_forms', [
            'adminhtml_customer',
            'adminhtml_checkout',
            'adminhtml_customer_address',
            'customer_account_edit',
            'customer_address_edit',
            'customer_register_address'
        ]);
        $attribute->save();

        return $this;
    }
}
