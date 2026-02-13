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
$name = isset($input['name']) ? trim($input['name']) : '';
$phone = isset($input['phone']) ? trim($input['phone']) : '';

// Validation
if ($id <= 0) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'error' => 'Invalid or missing ID'
    ]);
    exit;
}

if (empty($name) || empty($phone)) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'error' => 'Name and phone are required'
    ]);
    exit;
}

if (strlen($name) > 100) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'error' => 'Name too long (max 100 characters)'
    ]);
    exit;
}

if (strlen($phone) > 20) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'error' => 'Phone number too long (max 20 characters)'
    ]);
    exit;
}

try {
    // Check if contact exists
    $checkStmt = $pdo->prepare("SELECT id FROM contacts WHERE id = ?");
    $checkStmt->execute([$id]);
    
    if (!$checkStmt->fetch()) {
        http_response_code(404);
        echo json_encode([
            'success' => false,
            'error' => 'not found'
        ]);
        exit;
    }
    
    // Update the contact
    $stmt = $pdo->prepare("UPDATE contacts SET name = ?, phone = ? WHERE id = ?");
    $result = $stmt->execute([$name, $phone, $id]);
    
    if ($result) {
        echo json_encode([
            'success' => true
        ]);
    } else {
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'error' => 'Failed to update contact'
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
