Add bulk attribut -> Options to magento 2 admin (2.4.7)

CSV EX: 
color	Black
color	coffee
color	Dark Coffee
color	Gray
color	Mosha Coffee
color	1
color	2
color	3

https://dolphinwebsolution.com/how-to-add-attribute-options-values-into-dropdown-attribute-programmatically

<?php

ini_set('display_errors', '1');
ini_set('error_reporting', E_ALL);

use Magento\Framework\App\Bootstrap;

// Correct the path to the bootstrap.php
require __DIR__ . '/../app/bootstrap.php';  // Adjusted this line

$bootstrap = Bootstrap::create(BP, $_SERVER);
$objectManager = $bootstrap->getObjectManager();
$entityType = 'catalog_product';
$directory = $objectManager->get('\Magento\Framework\Filesystem\DirectoryList');
$path  =  $directory->getRoot().'/scripts/';
$objectManager =  \Magento\Framework\App\ObjectManager::getInstance();
$eavConfig = $objectManager->get('\Magento\Eav\Model\Config');
$eavSetup = $objectManager->get('\Magento\Eav\Setup\EavSetup');
$storeManager = $objectManager->get('Magento\Store\Model\StoreManagerInterface');
$stores = $storeManager->getStores();
$storeArray[0] = "All Store Views";
foreach ($stores  as $store) 
{
    $storeArray[$store->getId()] = $store->getName();
}

$fname = 'colorOption-withoutHeader.csv';

$file = fopen($path.$fname, "r");
while (($data = fgetcsv($file, 100000, ",")) !== FALSE)
{
    foreach((array)$data[0] as $attributeCode)
    {
        $option = array();
        $attribute = $eavConfig->getAttribute($entityType, $attributeCode);
if (!$attribute->getId()) {
    echo "Attribute not found for code: " . $attributeCode;
    continue;
} else {
    echo "Found attribute ID: " . $attribute->getAttributeId();
}
        $option['attribute_id'] = $attribute->getAttributeId();
        $options = $attribute->getSource()->getAllOptions();
        if ($data[1])
        {
            foreach ((array)$data[1] as $key => $value)
            {
                $str = '"'.$value.'"';
                $option['value'][$str][0] = str_replace('"','', $str);
                foreach ($storeArray as $storeKey => $store) 
                {
                    $option['value'][$str][$storeKey] = str_replace('"','', $str);
                }   
            }

            $eavSetup->addAttributeOption($option);
        }
    }
}
fclose($file);

echo "Attribute option values have been associated to color attribute SUCCESSFULLY";
