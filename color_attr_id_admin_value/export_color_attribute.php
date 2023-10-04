<?php
use Magento\Framework\App\Bootstrap;
require __DIR__ . '/app/bootstrap.php';

$bootstrap = Bootstrap::create(BP, $_SERVER);
$objectManager = $bootstrap->getObjectManager();
$resource = $objectManager->get('Magento\Framework\App\ResourceConnection');
$connection = $resource->getConnection();

$attributeCode = 'color';

// Get attribute id
$sql = "SELECT attribute_id FROM eav_attribute WHERE attribute_code = ?";
$attributeId = $connection->fetchOne($sql, [$attributeCode]);

if (!$attributeId) {
    die("Attribute code '$attributeCode' not found");
}

// Get attribute options
$sql = "SELECT option_id FROM eav_attribute_option WHERE attribute_id = ?";
$optionIds = $connection->fetchCol($sql, [$attributeId]);

if (empty($optionIds)) {
    die("No options found for attribute code '$attributeCode'");
}

// Get attribute option values
foreach ($optionIds as $optionId) {
    $sql = "SELECT store_id, value FROM eav_attribute_option_value WHERE option_id = ?";
    $values = $connection->fetchAll($sql, [$optionId]);

    $adminValue = '';
    $storeValue = '';

    foreach ($values as $value) {
        if ($value['store_id'] == 0) {
            $adminValue = $value['value'];
        } else {
            $storeValue = $value['value'];
        }
    }

    echo "ID: $optionId, Admin: $adminValue, Color: $storeValue\n";
}
