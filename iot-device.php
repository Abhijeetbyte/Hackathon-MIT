<?php
// Step 1: Get the query parameters from the URL
if (isset($_GET['flowrate']) && isset($_GET['total_volume']) && isset($_GET['filename'])) {
    $flowrate = $_GET['flowrate'];
    $total_volume = $_GET['total_volume'];
    $filename = $_GET['filename'];
} else {
    die("Missing required parameters");
}

// Step 2: Check if the filename is in the hardcoded list
$allowed_filenames = ['I001D', 'I002D', 'I003D'];
if (!in_array($filename, $allowed_filenames)) {
    die("Invalid filename");
}

// Step 3: Create the 'Iot-db' folder if it doesn't exist
$iot_db_folder = 'IoT-db';
if (!file_exists($iot_db_folder)) {
    mkdir($iot_db_folder, 0755, true);
}

// Step 4: Create or open the CSV file and write data to it
$csv_file = "$iot_db_folder/$filename.csv";
$fp = fopen($csv_file, "w"); // 'create mode
if ($fp) {
    fputcsv($fp, [$flowrate, $total_volume]);
    fclose($fp);
    echo "Data saved successfully!";
} else {
    die("Failed to open file");
}




// 6-----------------------------------------------------------



function updateCSVFile() {
    
    if($filename = "I001D") { /// Device ID
    
    $filename = 'Manipal.csv'; // Specify the filename
    $incomingFlowrate = $flowrate;    // Replace with your incoming flowrate value

    
    
    
    // Path to the CSV file
    $csvFilePath = 'Geospatial-db/' . $filename;

    // Check if the file exists
    if (!file_exists($csvFilePath)) {
        die("CSV file not found.");
    }

    // Read the CSV file into an array
    $csvData = array_map('str_getcsv', file($csvFilePath));

    // Find the row to update (assuming the row format is "latitude,longitude,name,status")
    $rowToUpdate = array_search('13.352532,74.792822,Pump House (MIT),DOWN', $csvData);

    // Check if the row was found
    if ($rowToUpdate !== false) {
        // Determine the new status based on the flowrate
        $newStatus = ($flowrate > 0) ? 'UP' : 'DOWN';

        // Update the status column (assuming it's the fourth column, index 3)
        $csvData[$rowToUpdate][3] = $newStatus;

        // Open the CSV file for writing
        $fileHandle = fopen($csvFilePath, 'w');

        // Check if the file was opened successfully
        if ($fileHandle) {
            // Write the updated data back to the CSV file
            foreach ($csvData as $row) {
                fputcsv($fileHandle, $row);
            }

            // Close the file handle
            fclose($fileHandle);

            echo "CSV file updated successfully!";
        } else {
            die("Failed to open CSV file for writing.");
        }
    } else {
        die("Row not found in CSV file.");
    }
}

}


?>
