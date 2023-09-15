<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Geospatial Data Visualization</title>
    <!-- Include Leaflet.js from a CDN -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <style>
        /* Styling for the dropdown menu */
        .dropdown {
            position: absolute;
            top: 50px;
            left: 50%;
            transform: translateX(-50%);
            background-color: #007BFF;
            padding: 10px;
            border-radius: 5px;
            color: #fff;
     
         
        }

        /* Styling for the data table */
        #data-table {
            width: 80%;
            margin: 20px auto;
            border-collapse: collapse;
            background-color: #f5f5f5;
        }

        #data-table th, #data-table td {
            padding: 10px;
            border: 1px solid #ccc;
            text-align: center;
        }

        #data-table th {
            background-color: #007BFF;
            color: #fff;
        }

        /* Styling for h1 and h2 */
        h1, h2 {
            text-align: center;
            color: #007BFF;
            margin-top: auto;
        
        }

        /* Styling for the button container */
        .button-container-center {
            text-align: center;
            margin-top: 20px;
            margin-bottom: 20 px;
        }

        .button {
            padding: 10px 20px;
            background-color: #007BFF;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <h1>Select Province</h1>
    <div class="dropdown">
        <label for="province-select">Select Province:</label>
        <select id="province-select" onchange="loadData()" style="background-color: #f5f5f5; color: #007BFF;">
            <option value="" disabled selected>Select a CSV file</option>
            <?php
            $folder = "Geospatial-db/";
            $files = scandir($folder);
            foreach ($files as $file) {
                if (pathinfo($file, PATHINFO_EXTENSION) === 'csv') {
                    echo "<option value=\"$folder$file\">$file</option>";
                }
            }
            ?>
        </select>
    </div>
    <br/>
    <br/>

    <div id="map" style="height: 400px;"></div>

    <!-- Table to display data -->
    <h2>Data Table</h2>
    <table id="data-table">
        <thead>
            <tr>
                <th>Latitude</th>
                <th>Longitude</th>
                <th>Infrastructure Type</th>
            </tr>
        </thead>
        <tbody>
            <!-- Data rows will be inserted here -->
        </tbody>
    </table>

    <!-- Button for entering data -->
    <div class="button-container-center">
        <a class="button" href="add-geospatial.php">Enter Data</a>
        <br/>
    </div>
    
    <script>
        var map = L.map('map').setView([0, 0], 2); // Default map view

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        var markersLayer;
        var polyline;

        function loadData() {
            var select = document.getElementById("province-select");
            var selectedFile = select.value;

            if (selectedFile) {
                // Clear existing markers and polyline if any
                if (markersLayer) {
                    map.removeLayer(markersLayer);
                }
                if (polyline) {
                    map.removeLayer(polyline);
                }

                // Load and parse the selected CSV file
                fetch(selectedFile)
                    .then(response => response.text())
                    .then(data => {
                        var lines = data.split("\n");
                        var coordinates = [];

                        for (var i = 1; i < lines.length; i++) {
                            var values = lines[i].split(",");
                            var latitude = parseFloat(values[0]);
                            var longitude = parseFloat(values[1]);
                            var infrastructureType = values[2];

                            if (!isNaN(latitude) && !isNaN(longitude)) {
                                coordinates.push([latitude, longitude]);

                                // Create a marker for each data point
                                var marker = L.marker([latitude, longitude]).addTo(map);
                                marker.bindPopup(`Type: ${infrastructureType}`);

                                // Add a row to the data table
                                var table = document.getElementById("data-table").getElementsByTagName('tbody')[0];
                                var newRow = table.insertRow(table.rows.length);
                                var cell1 = newRow.insertCell(0);
                                var cell2 = newRow.insertCell(1);
                                var cell3 = newRow.insertCell(2);
                                cell1.innerHTML = latitude.toFixed(4);
                                cell2.innerHTML = longitude.toFixed(4);
                                cell3.innerHTML = infrastructureType;
                            }
                        }

                        // Create a polyline from the coordinates and add it to the map
                        polyline = L.polyline(coordinates, { color: '#007BFF' }).addTo(map);

                        // Fit the map view to the polyline bounds
                        map.fitBounds(polyline.getBounds());
                    })
                    .catch(error => console.error(error));
            }
        }
    </script>
</body>
</html>
