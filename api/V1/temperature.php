<?php
// Enable CORS
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, DELETE");
header("Access-Control-Allow-Headers: Content-Type");

// Handle preflight request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit();
}


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

    // Handle DELETE request to remove temperature data
    if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
        // Parse the input
        parse_str(file_get_contents("php://input"), $data);

        // Get the id from the input data
        if (isset($data['id'])) {
            $id = $data['id'];

            // Prepare and execute the DELETE statement
            $stmt = $pdo->prepare("DELETE FROM temperature_data WHERE id = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);

            if ($stmt->execute()) {
                http_response_code(200);
                echo json_encode(['message' => 'Record deleted successfully']);
            } else {
                http_response_code(500);
                echo json_encode(['message' => 'Failed to delete record']);
            }
        } else {
            http_response_code(400);
            echo json_encode(['message' => 'Invalid ID']);
        }
    }

    // Handle GET request to fetch temperature data
    if ($_SERVER['REQUEST_METHOD'] === 'GET' && !isset($_GET['export'])) {
        $stmt = $pdo->query('SELECT id, temperature, to_char(timestamp, \'YYYY-MM-DD HH24:MI:SS\') AS timestamp FROM temperature_data ORDER BY id DESC LIMIT 20');
        $data = $stmt->fetchAll();
        echo json_encode($data);
    }

    // Handle POST request to insert temperature data
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
