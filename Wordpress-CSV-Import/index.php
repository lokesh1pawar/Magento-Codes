This is chnage all image break into additional image if image url are so many 

<?php

// Input and output file paths
$inputFile = 'base_image.csv'; 
$outputFile = 'image_final.csv';

// Open the input file for reading
if (($handle = fopen($inputFile, 'r')) !== FALSE) {
    // Read the header
    $header = fgetcsv($handle);
    
    // Determine the indexes of the relevant columns
    $baseImageColumnIndex = array_search('base_image', $header);
    
    // Check if the base_image column exists
    if ($baseImageColumnIndex === FALSE) {
        die("Error: 'base_image' column not found.");
    }

    // Create an array to hold the output data
    $outputData = [];
    $outputData[] = $header; // Add the original header to output

    // Process each row
    while (($data = fgetcsv($handle)) !== FALSE) {
        // Get the image paths from the base_image column
        $imagePaths = explode(',', $data[$baseImageColumnIndex]);

        // Trim whitespace and filter empty values
        $imagePaths = array_filter(array_map('trim', $imagePaths));

        // Get the base image (first image) and additional images
        $baseImage = !empty($imagePaths) ? array_shift($imagePaths) : '';
        $additionalImages = !empty($imagePaths) ? implode(',', $imagePaths) : '';

        // Update the base_image column
        $data[$baseImageColumnIndex] = $baseImage;

        // Add the additional_images column (assumed to be the next column)
        $data[] = $additionalImages;

        // Add the modified row to output data
        $outputData[] = $data;
    }

    // Close the input file
    fclose($handle);

    // Open the output file for writing
    if (($handle = fopen($outputFile, 'w')) !== FALSE) {
        // Write each row to the output CSV
        foreach ($outputData as $row) {
            fputcsv($handle, $row);
        }
        // Close the output file
        fclose($handle);
    }

    echo "CSV file processed successfully!";
} else {
    echo "Error opening the input file.";
}

?>
