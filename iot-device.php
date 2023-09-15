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
?>
