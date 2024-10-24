for check duplicate product name and add 1,2,3,4 after product 

<?php
// File paths
$inputCsvFile = 'csv-alijee-final.csv'; // Your input CSV file from WooCommerce
$outputCsvFile = 'final-all-Product.csv'; // Output CSV file for Magento 2

// Open input CSV
if (($inputHandle = fopen($inputCsvFile, 'r')) !== false) {
    // Open output CSV
    $outputHandle = fopen($outputCsvFile, 'w');

    // Write the Magento 2 CSV headers with all the fields in sequence
    fputcsv($outputHandle, [
        'id', 'sku', 'name', 'attribute_set_code', 'product_type', 'configurable_variations', 'visibility',
        'short_description', 'description', 'tax_class_name', 'is_in_stock', 'qty', 'weight',
        'length', 'width', 'height', 'use_config_min_sale_qty', 'price', 'categories',
        'base_image', 'small_image', 'thumbnail_image', 'additional_images'
    ]);

    // Read the WooCommerce CSV rows
    $header = fgetcsv($inputHandle); // Skip the header
    $configurableProducts = [];
    $simpleProducts = [];
    $nextId = 28335; // Start from the given ID (or read the first ID from the input if needed)

    // Initialize an array to track duplicate names, making it case-insensitive
    $nameCounts = [];

    while (($row = fgetcsv($inputHandle)) !== false) {
        // Map columns from WooCommerce CSV to Magento 2 fields
        $sku = $row[1];
        $name = $row[2];

        // Check for duplicate names (case-insensitive)
        $lowercaseName = strtolower($name); // Convert name to lowercase for comparison
        if (isset($nameCounts[$lowercaseName])) {
            $nameCounts[$lowercaseName]++;
            $name .= ' ' . $nameCounts[$lowercaseName]; // Append the numeric suffix to make the name unique
        } else {
            $nameCounts[$lowercaseName] = 1; // First occurrence of this name
        }

        // Rest of your code remains unchanged...
        $price = $row[17];
        $attributeSetCode = 'Default';
        $visibility = $row[6];

        // Preserve the short_description and description with special characters, newlines, and HTML tags
        $shortDescription = $row[7]; // HTML content as it is
        $description = $row[8];      // HTML content as it is
        
        $taxClassName =  $row[9];
        $isInStock = $row[10];
        $qty =  $row[11];
        $weight = $row[12];
        $length = $row[13];
        $width = $row[14];
        $height = $row[15];
        $useConfigMinSaleQty = $row[16];
        $categories = $row[18];
        $baseImage = $row[19];
        $smallImage = $row[19];
        $thumbnailImage = $row[19];
        $additionalImages = $row[23];

        // If SKU is non-empty, it's a configurable product
        if (!empty($sku)) {
            // If simple products exist, link them to the last configurable product
            if (!empty($simpleProducts)) {
                $lastConfigurable = array_pop($configurableProducts);
                $lastConfigurable['configurable_variations'] = implode(' | ', $simpleProducts);
                $configurableProducts[] = $lastConfigurable;
                $simpleProducts = [];
            }

            // Add new configurable product to the list
            $configurableProducts[] = [
                'id' => $nextId++, // Use the next ID for configurable product
                'sku' => $sku,
                'name' => $name,  // Use the modified name here
                'attribute_set_code' => $attributeSetCode,
                'product_type' => 'configurable',
                'configurable_variations' => '', 
                'visibility' => $visibility,
                'short_description' => $shortDescription,
                'description' => $description,
                'tax_class_name' => $taxClassName,
                'is_in_stock' => $isInStock,
                'qty' => $qty,
                'weight' => $weight,
                'length' => $length,
                'width' => $width,
                'height' => $height,
                'use_config_min_sale_qty' => $useConfigMinSaleQty,
                'price' => $price,
                'categories' => $categories,
                'base_image' => $baseImage,
                'small_image' => $smallImage,
                'thumbnail_image' => $thumbnailImage,
                'additional_images' => $additionalImages
            ];
        } else {
            // Simple product detected, link it to the last configurable product
            $parentSku = $configurableProducts[count($configurableProducts) - 1]['sku'];
            $childSku = $parentSku . '-child' . (count($simpleProducts) + 1);

            // Write the simple product
            fputcsv($outputHandle, [
                $nextId++, // Use the next ID for simple product
                $childSku, $name, $attributeSetCode, 'simple', '', $visibility,
                $shortDescription, $description, $taxClassName, $isInStock, $qty, $weight,
                $length, $width, $height, $useConfigMinSaleQty, $price, $categories,
                $baseImage, $smallImage, $thumbnailImage, $additionalImages
            ]);
            $simpleProducts[] = "sku={$childSku}";
        }
    }

    // Add the last configurable product with its variations
    if (!empty($configurableProducts)) {
        if (!empty($simpleProducts)) {
            $lastConfigurable = array_pop($configurableProducts);
            $lastConfigurable['configurable_variations'] = implode(' | ', $simpleProducts);
            $configurableProducts[] = $lastConfigurable;
        }

        // Output configurable products
        foreach ($configurableProducts as $configurableProduct) {
            fputcsv($outputHandle, $configurableProduct);
        }
    }

    // Close file handles
    fclose($inputHandle);
    fclose($outputHandle);

    echo "CSV file has been successfully updated with synced SKUs and configurable variations.";
} else {
    echo "Error opening the WooCommerce CSV file.";
}
