<?php
require_once '../db_connect.php';

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode([
        'success' => false,
        'error' => 'Method not allowed. Use POST request.'
    ]);
    exit;
}

// Get POST data (supports both form-data and JSON)
$input = json_decode(file_get_contents('php://input'), true);
if (!$input) {
    $input = $_POST;
}

$id = isset($input['id']) ? intval($input['id']) : 0;

if ($id <= 0) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'error' => 'Invalid or missing ID'
    ]);
    exit;
}

try {
    $stmt = $pdo->prepare("DELETE FROM contacts WHERE id = ?");
    $result = $stmt->execute([$id]);
    
    // Return success true if deleted (even if no rows affected)
    // Lab 2 requirement: {"success": true} for delete
    if ($result && $stmt->rowCount() > 0) {
        echo json_encode([
            'success' => true
        ]);
    } else {
        // Contact not found
        http_response_code(404);
        echo json_encode([
            'success' => false,
            'error' => 'not found'
        ]);
    }
    
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Database error: ' . $e->getMessage()
    ]);
}
?>
