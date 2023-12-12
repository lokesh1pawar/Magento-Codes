<?php

// Specify the file paths
$inputFilePath = 'four-cta-og.csv';
$outputFilePath = 'output-four.csv';

// Open the input CSV file
$inputFile = fopen($inputFilePath, 'r');

if ($inputFile) {
    // Open the output CSV file for writing
    $outputFile = fopen($outputFilePath, 'w');

    if ($outputFile) {
        // Output column headers to the output CSV file
        fputcsv($outputFile, ['sku', 'attribute_set_code', 'product_type',  'additional_attributes']);

        // Process each row in the CSV file
        while (($row = fgetcsv($inputFile)) !== false) {
            // Ensure that the row has at least two columns
            if (count($row) >= 4) {
                // Get the SKU from the first column
                $sku = $row[0];
                $at_set = $row[1];
                $p_type = $row[2];



                // Get the values of the second column
                $attributes = explode(',', $row[3]);
                $offlineProductAttribute = '';

                // Check each attribute for "offline_product=Yes" or "offline_product=No"
                // foreach ($attributes as $attribute) {
                //     if (trim($attribute) == "offline_product=Yes" || trim($attribute) == "offline_product=No") {
                //         $offlineProductAttribute = trim($attribute);
                //         break; // Stop checking once we find the relevant attribute
                //     }
                // }

                // Check each attribute for "offline_product=Yes" or "offline_product=No"
                foreach ($attributes as $attribute) {
                    // Check if the attribute starts with "offline_product="
                    if (strpos($attribute, 'offline_product=') === 0) {
                        $offlineProductAttribute = trim($attribute);
                        break; // Stop checking once we find the relevant attribute
                    }
                }


                // If the relevant attribute is found, write the SKU and attribute to the output CSV file
                if ($offlineProductAttribute) {
                    fputcsv($outputFile, [$sku, $at_set, $p_type,  $offlineProductAttribute]);
                    // echo "Done...";
                }
            }
        }

        // Close the output CSV file
        fclose($outputFile);
        echo "Processing complete. Check $outputFilePath for the result.";
    } else {
        echo "Error opening output file.";
    }

    // Close the input CSV file
    fclose($inputFile);
} else {
    echo "Error opening input file.";
}

?>
