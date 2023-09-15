<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Grievances</title>
    <style>
        /* Accent color */
        :root {
            --accent-color: #007BFF;
        }

        /* Updated CSS */
        table {
            border-collapse: collapse;
            width: 80%;
            margin: 20px auto;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .form-container {
            display: none;
            padding: 20px;
            background-color: #f9f9f9; /* Light background color */
            border: 1px solid #ddd;
            margin-top: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); /* Box shadow for a modern look */
        }

        /* Styling the button */
        .form-button {
            background-color: var(--accent-color);
            color: white;
            padding: 15px 30px;
            border: none;
            cursor: pointer;
            border-radius: 5px;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }

        .form-button:hover {
            background-color: #0056b3; /* Darker shade of blue on hover */
        }

        /* Center the button horizontally */
        .button-container {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }

        /* Modern form styles */
        label {
            font-weight: bold;
        }

        input[type="text"],
        textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
        }

        input[type="submit"] {
            background-color: var(--accent-color);
            color: white;
            padding: 15px 30px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        input[type="submit"]:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <?php
    // Function to check if the folder exists, and create it if not
    function checkOrCreateFolder($folderName) {
        if (!file_exists($folderName)) {
            mkdir($folderName, 0755, true);
        }
    }

    // Check and create the "Grievances-db" folder
    checkOrCreateFolder('Grievances-db');

    // Check if the CSV file exists, if not, create it with header
    $csvFilePath = 'Grievances-db/grievances.csv';
    if (!file_exists($csvFilePath)) {
        $header = ['Name', 'Issue', 'Province', 'Status'];
        $file = fopen($csvFilePath, 'w');
        fputcsv($file, $header);
        fclose($file);
    }

    // Handle form submission
    if (isset($_POST['submit'])) {
        $name = $_POST['name'];
        $issue = $_POST['issue'];
        $province = $_POST['province'];
        $status = 'unresolved';

        // Append the form data to the CSV file
        $data = [$name, $issue, $province, $status];
        $file = fopen($csvFilePath, 'a');
        fputcsv($file, $data);
        fclose($file);

        // Reset form input values
        $_POST['name'] = '';
        $_POST['issue'] = '';
        $_POST['province'] = '';
    }
    ?>
    
    <h3 style="font-family: Arial, sans-serif; color: #text-indent: 50px; text-align: center;letter-spacing: 2px;;">Ticketing system to manage and track these grievances efficiently.</h3>

  <!-- Center the button within a container -->
    <div class="button-container">
        <button class="form-button" id="createNew">Create New</button>
    </div>

   <!-- Updated modern form with "Issue" as a textarea -->
    <div class="form-container" id="createForm">
        <h2>Create New Grievance</h2>
        <form method="post" action="">
            <label for="name">Name:</label>
            <input type="text" name="name" value="<?php echo isset($_POST['name']) ? $_POST['name'] : ''; ?>" required><br>
            <label for="issue">Issue:</label>
            <textarea name="issue" rows="4" required><?php echo isset($_POST['issue']) ? $_POST['issue'] : ''; ?></textarea><br>
            <label for="province">Province:</label>
            <input type="text" name="province" value="<?php echo isset($_POST['province']) ? $_POST['province'] : ''; ?>" required><br>
            <input type="submit" name="submit" value="Submit">
        </form>
    </div>


    <?php
    // Read and display CSV data in a table
    $file = fopen($csvFilePath, 'r');
    $rowCount = 0;

    echo '<table>';
    while (($data = fgetcsv($file)) !== false) {
        if ($rowCount === 0) {
            echo '<thead><tr>';
            foreach ($data as $header) {
                echo '<th>' . htmlspecialchars($header) . '</th>';
            }
            echo '</tr></thead><tbody>';
        } else {
            echo '<tr>';
            foreach ($data as $value) {
                echo '<td>' . htmlspecialchars($value) . '</td>';
            }
            echo '</tr>';
        }
        $rowCount++;
    }
    echo '</tbody></table>';

    fclose($file);
    ?>

    <script>
        // JavaScript to toggle the form visibility
        document.getElementById('createNew').addEventListener('click', function() {
            var form = document.getElementById('createForm');
            if (form.style.display === 'none' || form.style.display === '') {
                form.style.display = 'block';
            } else {
                form.style.display = 'none';
            }
        });
    </script>
</body>
</html>
