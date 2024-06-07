<?php
// Database connection settings
$host = 'localhost'; // Or your PostgreSQL server's IP/domain if remote
$db = 'temperature';
$user = 'robert';
$password = 'webtechisfun';

// Create a connection string
$dsn = "pgsql:host=$host;dbname=$db";

try {
    // Create a PDO instance
    $pdo = new PDO($dsn, $user, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);

    // Handle GET request to fetch temperature data
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $stmt = $pdo->query('SELECT id, temperature, to_char(timestamp, \'YYYY-MM-DD HH24:MI:SS\') AS timestamp FROM temperature_data ORDER BY id DESC LIMIT 20');
        $data = $stmt->fetchAll();
        echo json_encode($data);
    }

    // Check if the request is a POST
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Get the raw POST data
        $input = file_get_contents('php://input');
        // Decode the JSON data
        $data = json_decode($input, true);

        if (isset($data['temperature'])) {
            $temperature = $data['temperature'];

            // Insert the temperature data into the database
            $stmt = $pdo->prepare('INSERT INTO temperature_data (timestamp, temperature) VALUES (NOW(), :temperature)');
            $stmt->execute(['temperature' => $temperature]);

            echo "Temperature data inserted successfully.";
        } else {
            echo "Invalid data received.";
        }
    }
    
// Handle GET request to export temperature data as CSV
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['export']) && $_GET['export'] === 'csv') {
    // Set the HTTP header to indicate a CSV file download
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="temperature_data.csv"');

    // Query the database for temperature data
    $stmt = $pdo->query('SELECT id, temperature, to_char(timestamp, \'YYYY-MM-DD HH24:MI:SS\') AS timestamp FROM temperature_data ORDER BY id');
    $data = $stmt->fetchAll();

    // Output CSV header
    $output = fopen('php://output', 'w');
    fputcsv($output, array('ID', 'Temperature', 'Timestamp'));

    // Output each row of temperature data as CSV
    foreach ($data as $row) {
        fputcsv($output, $row);
    }

    // Close the output stream
    fclose($output);
    exit(); // Stop further execution
}

} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
}
?>
