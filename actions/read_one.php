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

// Get the id parameter
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id <= 0) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'error' => 'Invalid or missing ID parameter'
    ]);
    exit;
}

try {
    $stmt = $pdo->prepare("SELECT id, name, phone FROM contacts WHERE id = ?");
    $stmt->execute([$id]);
    $contact = $stmt->fetch();
    
    if ($contact) {
        // Convert id to integer for consistency
        $contact['id'] = (int)$contact['id'];
        
        echo json_encode([
            'success' => true,
            'data' => $contact
        ]);
    } else {
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
