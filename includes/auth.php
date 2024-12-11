<?php
// JWT Secret Key (make sure to keep it safe)
define('JWT_SECRET', 'your_secret_key');

// Base64 URL Safe encoding function
function base64UrlEncode($data) {
    return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
}

// Base64 URL Safe decoding function
function base64UrlDecode($data) {
    return base64_decode(str_pad(strtr($data, '-_', '+/'), strlen($data) % 4, '=', STR_PAD_RIGHT));
}

// Function to generate JWT token
function generateJWT($user_id) {
    $issuedAt = time();
    $expirationTime = $issuedAt + 3600;  // jwt valid for 1 hour from the issued time
    $payload = array(
        'user_id' => $user_id,
        'iat' => $issuedAt,
        'exp' => $expirationTime
    );

    // Encode the header
    $header = json_encode(['alg' => 'HS256', 'typ' => 'JWT']);
    $encodedHeader = base64UrlEncode($header);

    // Encode the payload
    $encodedPayload = base64UrlEncode(json_encode($payload));

    // Create the signature
    $signature = hash_hmac('sha256', "$encodedHeader.$encodedPayload", JWT_SECRET, true);
    $encodedSignature = base64UrlEncode($signature);

    // Combine header, payload, and signature to form the JWT
    $jwt = "$encodedHeader.$encodedPayload.$encodedSignature";
    return $jwt;
}

// Function to validate JWT token
function validateJWT($token) {
    try {
        // Split the JWT into header, payload, and signature
        list($encodedHeader, $encodedPayload, $encodedSignature) = explode('.', $token);

        // Decode the payload
        $decodedPayload = json_decode(base64UrlDecode($encodedPayload), true);

        // Check if the token has expired
        if ($decodedPayload['exp'] < time()) {
            return null;
        }

        // Recreate the signature to validate
        $recreatedSignature = base64UrlEncode(hash_hmac('sha256', "$encodedHeader.$encodedPayload", JWT_SECRET, true));

        // Validate the signature
        if ($recreatedSignature !== $encodedSignature) {
            return null;
        }

        return $decodedPayload['user_id'];
    } catch (Exception $e) {
        return null;
    }
}
?>
