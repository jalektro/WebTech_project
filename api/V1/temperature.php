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
        $stmt = $pdo->query('SELECT * FROM temperature_data ORDER BY id DESC LIMIT 20');
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
} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
}
?>
