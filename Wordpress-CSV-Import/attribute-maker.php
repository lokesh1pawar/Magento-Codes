this will merge attribite in new column 

id	color	ships_from	additional_attributes
28335	Black, coffee, Dark Coffee, Gray, Mosha Coffee	Ali Jee Warehouse, Ali Jee Warehouse - RF	color=Black, coffee, Dark Coffee, Gray, Mosha Coffee|ships_from=Ali Jee Warehouse, Ali Jee Warehouse - RF
28336	Dark Coffee	Ali Jee Warehouse - RF	color=Dark Coffee|ships_from=Ali Jee Warehouse - RF
28337	Dark Coffee	Ali Jee Warehouse	color=Dark Coffee|ships_from=Ali Jee Warehouse
28338	Gray	Ali Jee Warehouse	color=Gray|ships_from=Ali Jee Warehouse

<?php
// File paths
$inputCsvFile = 'attribute-test.csv'; // Input CSV file with color and ships_from columns
$outputCsvFile = 'output-proper.csv'; // Output CSV file with additional_attributes filled

// Open input CSV
if (($inputHandle = fopen($inputCsvFile, 'r')) !== false) {
    // Open output CSV
    $outputHandle = fopen($outputCsvFile, 'w');

    // Read the header of the CSV file
    $header = fgetcsv($inputHandle);

    // Write the same header to the output CSV
    fputcsv($outputHandle, $header);

    // Find the column indexes for 'color', 'ships_from', and 'additional_attributes'
    $colorIndex = array_search('color', $header);
    $shipsFromIndex = array_search('ships_from', $header);
    $additionalAttributesIndex = array_search('additional_attributes', $header);

    // Process each row
    while (($row = fgetcsv($inputHandle)) !== false) {
        // Get the values of color and ships_from
        $color = trim($row[$colorIndex]);
        $shipsFrom = trim($row[$shipsFromIndex]);

        // Prepare the additional_attributes value
        $additionalAttributes = '';

        if (!empty($color)) {
            $additionalAttributes .= 'color=' . $color;
        }

        if (!empty($shipsFrom)) {
            if (!empty($additionalAttributes)) {
                $additionalAttributes .= '|';
            }
            $additionalAttributes .= 'ships_from=' . $shipsFrom;
        }

        // Update the 'additional_attributes' column
        $row[$additionalAttributesIndex] = $additionalAttributes;

        // Write the updated row to the output CSV
        fputcsv($outputHandle, $row);
    }

    // Close file handles
    fclose($inputHandle);
    fclose($outputHandle);

    echo "CSV file has been successfully updated with additional attributes.";
} else {
    echo "Error opening the input CSV file.";
}
