<?php
require_once '../includes/database.php';
require_once '../includes/auth.php';

// Check if the request is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the input data
    $data = json_decode(file_get_contents("php://input"), true);
    $email = $data['email'];
    $password = $data['password'];

    // Validate the input data
    if (empty($email) || empty($password)) {
        echo json_encode(['message' => 'Email and password are required']);
        http_response_code(400);
        exit;
    }

    // Query the database to check user credentials
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Check if user exists and password is correct
    if ($user && password_verify($password, $user['password'])) {
        // Generate JWT token
        $token = generateJWT($user['id']); // Using the user ID as the payload
        echo json_encode(['message' => 'Login successful', 'token' => $token]);
        http_response_code(200);
    } else {
        echo json_encode(['message' => 'Invalid credentials']);
        http_response_code(401);
    }
} else {
    echo json_encode(['message' => 'Invalid request method']);
    http_response_code(405);
}
?>
