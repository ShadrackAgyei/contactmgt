<?php

// Database configuration
$host = 'localhost';
$dbname = 'mobileapps_2026B_shadrack_nti';
$username = 'shadrack.nti';
$password = '0556811298';  


try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password, $options);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Database connection failed: ' . $e->getMessage()
    ]);
    exit;
}
?>
