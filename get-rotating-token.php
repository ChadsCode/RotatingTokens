<?php
/**
 *
 * Concept: "Rotate everything" as a defense philosophy!
 *
 * CSRF Protection Demonstration Project
 * Project Name: rotating-csrf-demo
 * Author: Chad Wigington
 * Purpose: Demonstrate rotating CSRF protection
 * GitHub: https://github.com/ChadsCode/RotatingTokens
 * Website: https://www.RotatingTokens.com
 *
 * © Chad Wigington
 * LinkedIn: https://www.linkedin.com/in/chadwigington/
 * License: MIT
 * For educational purposes only
 * 
 */
session_start();
header('Content-Type: application/json');

// Rate limiting check (basic implementation)
if (!isset($_SESSION['token_requests'])) {
    $_SESSION['token_requests'] = [];
}

// Clean old requests (older than 1 minute)
$_SESSION['token_requests'] = array_filter($_SESSION['token_requests'], function($timestamp) {
    return time() - $timestamp < 60;
});

// Check rate limit (max 30 requests per minute)
if (count($_SESSION['token_requests']) > 30) {
    http_response_code(429);
    echo json_encode(['error' => 'Rate limit exceeded - possible AI attack detected']);
    exit;
}

// Log this request
$_SESSION['token_requests'][] = time();

// Generate new token
$new_token = bin2hex(random_bytes(32));

// Store with timestamp
$_SESSION['csrf_tokens'][$new_token] = time();

// Clean old tokens
foreach ($_SESSION['csrf_tokens'] as $token => $timestamp) {
    if (time() - $timestamp > 300) { // 5 minutes
        unset($_SESSION['csrf_tokens'][$token]);
    }
}

// Keep only last 10 tokens to prevent memory issues
if (count($_SESSION['csrf_tokens']) > 10) {
    $_SESSION['csrf_tokens'] = array_slice($_SESSION['csrf_tokens'], -10, 10, true);
}

// Log for debugging
error_log("New rotating token generated: " . $new_token);

echo json_encode([
    'token' => $new_token,
    'expires_in' => 300,
    'active_tokens' => count($_SESSION['csrf_tokens'])
]);
?>