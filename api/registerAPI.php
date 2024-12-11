<?php
require_once '../includes/database.php';

// Check if the request is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the input data
    $data = json_decode(file_get_contents("php://input"), true);
    $name = $data['name'];
    $email = $data['email'];
    $password = $data['password'];

    // Validate the input data
    if (empty($name) || empty($email) || empty($password)) {
        echo json_encode(['message' => 'Name, email, and password are required']);
        http_response_code(400);
        exit;
    }

    // Check if the email already exists
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    if ($stmt->rowCount() > 0) {
        echo json_encode(['message' => 'Email is already registered']);
        http_response_code(409);
        exit;
    }

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    // Insert user data into the database
    $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (:username, :email, :password)");
    $stmt->bindParam(':username', $name);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $hashed_password);
    if ($stmt->execute()) {
        echo json_encode(['message' => 'User registered successfully']);
        http_response_code(201);
    } else {
        echo json_encode(['message' => 'Error registering user']);
        http_response_code(500);
    }
} else {
    echo json_encode(['message' => 'Invalid request method']);
    http_response_code(405);
}
?>
