<?php
require_once '../includes/database.php';
require_once '../includes/auth.php';

// Check if the request is GET
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Check for JWT token in header
    $headers = getallheaders();
    $token = $headers['Authorization'] ?? null;

    if (!$token) {
        echo json_encode(['message' => 'Authorization token required']);
        http_response_code(401);
        exit;
    }

    // Decode the JWT token and get the user ID
    $user_id = validateJWT($token);

    if (!$user_id) {
        echo json_encode(['message' => 'Invalid token']);
        http_response_code(401);
        exit;
    }

    // Fetch user data for the dashboard
    $stmt = $pdo->prepare("SELECT * FROM students WHERE id = :id");
    $stmt->bindParam(':id', $user_id);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        echo json_encode(['user' => $user]);
        http_response_code(200);
    } else {
        echo json_encode(['message' => 'User not found']);
        http_response_code(404);
    }
} else {
    echo json_encode(['message' => 'Invalid request method']);
    http_response_code(405);
}
?>
