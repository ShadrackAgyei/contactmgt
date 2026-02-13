<?php
require_once '../db_connect.php';

// Only allow GET requests
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode([
        'success' => false,
        'error' => 'Method not allowed. Use GET request.'
    ]);
    exit;
}

try {
    // Query to get all contacts with id, name, and phone
    $stmt = $pdo->query("SELECT id, name, phone FROM contacts ORDER BY id DESC");
    $contacts = $stmt->fetchAll();
    
    // Return success with data array (even if empty)
    echo json_encode([
        'success' => true,
        'data' => $contacts
    ]);
    
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Failed to fetch contacts: ' . $e->getMessage()
    ]);
}
?>
