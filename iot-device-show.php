<!DOCTYPE html>
<html>
<head>
    <title>IoT Device Pannel</title>
  <style>
        /* Style for the container */
        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh; /* 100% viewport height to center vertically */
            background-color: #F0F0F0; /* Light gray background color */
        }

        /* Style for the content box */
        .content-box {
            width: 600px; /* Adjust the width as needed */
            padding: 20px;
            background-color: #FFFFFF; /* White background color */
            border: 2px solid #007BFF; /* Blue border */
            border-radius: 10px; /* Rounded border */
        }

        select {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            margin-top: 10px;
        }

        /* Style for the table */
        table {
            width: 100%;
            border-collapse: collapse;
            text-align: center;
        }

        th, td {
            border: 1px solid #007BFF; /* Blue border */
            padding: 10px;
        }

        th {
            background-color: #007BFF; /* Blue background color */
            color: #FFFFFF; /* White text color */
            font-weight: bold; /* Bold text */
        }

        /* Style for the data widget */
        .data-widget {
            text-align: center;
            margin-top: 20px;
        }

        .button {
            display: block;
            width: 100%;
            padding: 15px;
            background-color: #007BFF; /* Blue background color */
            color: #FFFFFF; /* White text color */
            font-size: 18px;
            text-align: center;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 10px;
        }

        .button:hover {
            background-color: #0056b3; /* Darker blue color on hover */
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="content-box">
           <h1 style="text-align: center;">IoT Device Pannel</h1>
         <h3 style="font-family: Arial, sans-serif; color: #text-indent: 50px; text-align: center;letter-spacing: 2px;"> 24x7hrs. IoT Connected Water Supply Network Alert Monitoring. </h3>
       
            <!-- Left dropdown block -->
            <form method="post">
                <label for="selected_file"><b>Select a Device by ID :</b></label>
                <select name="selected_file" id="selected_file">
                    <option value="" selected disabled>Devices</option>
                    <?php
                    $iot_db_folder = 'IoT-db';
                    $csv_files = glob("$iot_db_folder/*.csv");
                    foreach ($csv_files as $file) {
                        echo "<option value=\"$file\">$file</option>";
                    }
                    ?>
                </select>
                <input type="submit" value="Submit" class="button">
            </form>
            
            <!-- Right data showing widget -->
            <div class="data-widget" id="data-widget">
                <?php
                if (isset($_POST['selected_file'])) {
                    $selected_file = $_POST['selected_file'];
                    if (in_array($selected_file, $csv_files)) {
                        // Read and display the data
                        $data = file_get_contents($selected_file);
                        echo "<h2>Data from $selected_file:</h2>";
                        
                        // Assuming the data format is "Flowrate, Total Volume"
                        list($flowrate, $total_volume) = str_getcsv($data);
                        
                        echo "<table>";
                        echo "<tr><th>Flowrate (LPH)</th><th>Total Volume (Ltrs.)</th></tr>";
                        echo "<tr><td>$flowrate</td><td>$total_volume</td></tr>";
                        echo "</table>";
                    } else {
                        echo "<p style='color: red;'>File not found</p>";
                    }
                } else {
                    // Display a message if no file is selected
                    echo "<p>Select a Device to view data.</p>";
                }
                ?>
            </div>
        </div>
    </div>
    
    <script>
        // Refresh the page every 2000 seconds
        setTimeout(function () {
            location.reload();
        }, 5000);
    </script>
</body>
</html>
