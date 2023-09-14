<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IoT Device Data</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f8ff; /* Light Blue */
            text-align: center;
        }
        
          h1 {
            font-size: 35px;
            text-transform: uppercase;
            color: #007BFF;
        }

        p {
            font-size: 18px;
            margin-top: 20px;
        }

        .container {
            margin: 50px auto;
            padding: 20px;
            background-color: #fff; /* White */
            border-radius: 10px;
            box-shadow: 0px 0px 10px 0px #000;
            width: 50%;
        }
        /* Style the section block with shadow effects */
        .section-block {
            margin: 20px 0;
            padding: 20px;
            background-color: #f2f2f2; /* Light Gray */
            border-radius: 5px;
            box-shadow: 0px 0px 10px 0px #888;
            text-align: left;
        }
        /* Style the dropdown on the left side */
        .dropdown-container {
            display: inline-block;
            vertical-align: top;
        }
        select {
            background-color: #007BF;
            color: #000;
            padding: 15px;
            border: 2px solid #007BF;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            appearance: none;
            width: 100%;
        }
        /* Style the warning and data on the right side */
        .data-container {
            display: inline-block;
            vertical-align: top;
            margin-left: 20px;
        }
        .data-container p {
            display: none;
        }
        .data-container p.show {
            display: block;
        }
        /* Style the back button at the bottom */
     .back-button {
            background-color: #007BFF;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>IoT Device Pannel</h1>
        <p>24x7hrs. IoT Connected Water Supply Networks Monitoring and Alert.</p>
        <?php
        // Define the allowed filenames array
        $allowedFilenames = ['I001D', 'I002D','I003D']; // Add your allowed filenames here

        // Task 1: Read query parameters
        $flowRate = $_GET['flowrate'] ?? null;
        $totalFlow = $_GET['total_volume'] ?? null;
        $filename = $_GET['filename'] ?? null;

        if ($flowRate !== null && $totalFlow !== null && $filename !== null) {
            // Task 2: Save data to CSV file
            $folder = 'IoT-Device';
            if (!is_dir($folder)) {
                mkdir($folder);
            }

            // Check if the filename matches the allowed list
            if (in_array($filename, $allowedFilenames)) {
                $csvData = "$flowRate,$totalFlow\n";
                $csvFilePath = "$folder/$filename.csv";
                file_put_contents($csvFilePath, $csvData);
                echo "<p>Data saved successfully to $csvFilePath</p>";
            } else {
                echo "<p>Filename not allowed.</p>";
            }
        }
        ?>

        <!-- Task 3: Dropdown to select and display data -->
        <div class="section-block">
            <div class="dropdown-container">
                <label for="fileSelect">Select a Device:</label>
                <select id="fileSelect" onchange="displayData(this.value)">
                    <option value="">Devices</option>
                    <?php
                    // List the allowed filenames
                    foreach ($allowedFilenames as $allowedFilename) {
                        echo "<option value=\"$allowedFilename\">$allowedFilename</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="data-container">
                <p>No Data Found</p>
                <p class="flow-data">Flow Rate: <span id="flowRate"></span></p>
                <p class="flow-data">Total Flow: <span id="totalFlow"></span></p>
            </div>
        </div>
    </div>

    <!-- Back button to index.html -->
    <a href="index.html" class="back-button">Back</a>

    <script>
        // Task 4: JavaScript to fetch and display data
        function displayData(filename) {
            const flowRateElement = document.getElementById('flowRate');
            const totalFlowElement = document.getElementById('totalFlow');

            if (filename) {
                fetch(`IoT-Device/${filename}.csv`)
                    .then(response => response.text())
                    .then(data => {
                        const [flowRate, totalFlow] = data.trim().split(',');
                        if (flowRate && totalFlow) {
                            flowRateElement.textContent = flowRate;
                            totalFlowElement.textContent = totalFlow;
                            document.querySelector('.data-container p').classList.add('show');
                        } else {
                            document.querySelector('.data-container p').classList.remove('show');
                        }
                    })
                    .catch(error => console.error(error));
            } else {
                document.querySelector('.data-container p').classList.remove('show');
                flowRateElement.textContent = '';
                totalFlowElement.textContent = '';
            }
        }
    </script>
</body>
</html>
