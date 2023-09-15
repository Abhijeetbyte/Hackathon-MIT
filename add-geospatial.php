<?php
// Function to sanitize input data
function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Define folder and file paths
$folderPath = "Geospatial-db/";

// Check if the password is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["password"])) {
    $password = sanitize_input($_POST["password"]);

    // Verify the password (replace 'your_password' with your actual password)
    $correctPassword = 'your_password';

    if ($password === $correctPassword) {
        // Check if a file is uploaded
        if (isset($_FILES["csvfile"]) && $_FILES["csvfile"]["error"] == UPLOAD_ERR_OK) {
            $tmp_name = $_FILES["csvfile"]["tmp_name"];
            $file_name = basename($_FILES["csvfile"]["name"]);
            $file_path = $folderPath . $file_name;

            // Move the uploaded file to the target folder
            if (move_uploaded_file($tmp_name, $file_path)) {
                echo "File uploaded successfully.";
            } else {
                echo "Error uploading the file.";
            }
        } else {
            echo "No file uploaded.";
        }
    } else {
        echo "Incorrect password. File not uploaded.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Entry Form</title>
    <style>
        /* Styling for the form container */
        .container {
            max-width: 400px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color: #f7f7f7;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        /* Styling for form elements */
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 3px;
            font-size: 16px;
        }

        input[type="file"] {
            margin-bottom: 10px;
        }

        input[type="submit"] {
            background-color: #007BFF;
            color: #fff;
            border: none;
            border-radius: 3px;
            padding: 10px 20px;
            cursor: pointer;
            font-size: 16px;
        }

        /* Styling for error message */
        .error {
            color: red;
        }

        /* Styling for success message */
        .success {
            color: green;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Data Entry Form</h1>
        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["password"])) {
            if ($password === $correctPassword) {
                echo "<p class='success'>File uploaded successfully.</p>";
            } else {
                echo "<p class='error'>Incorrect password. File not uploaded.</p>";
            }
        }
        ?>
        <form method="post" enctype="multipart/form-data">
            <label for="password">Password:</label>
            <input type="password" name="password" required>
            <input type="file" name="csvfile" accept=".csv">
            <input type="submit" value="Upload File">
        </form>
    </div>
</body>
</html>
