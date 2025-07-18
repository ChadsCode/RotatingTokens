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

// Base64 encoded email to protect from scrapers
$encoded_email = "Y2hhZHJ3aWdpbmd0b25AZ21haWwuY29t"; // chadrwigington@gmail.com
$to = base64_decode($encoded_email);
$subject_prefix = "CSRF Demo - ";

// Get form data
$form_type = $_POST['form_type'] ?? 'unknown';
$name = $_POST['name'] ?? '';
$email = $_POST['email'] ?? '';
$message = $_POST['message'] ?? '';

// Security logging
$log_entry = date('Y-m-d H:i:s') . " - Form submission attempt - Type: $form_type - IP: " . $_SERVER['REMOTE_ADDR'] . "\n";
error_log($log_entry, 3, "csrf_demo_log.txt");

// Validate CSRF based on form type
$csrf_valid = false;
$security_status = '';

switch ($form_type) {
    case 'no-csrf':
        $csrf_valid = true; // No protection, always "valid"
        $security_status = "NO PROTECTION - Form accepted without verification";
        $subject = $subject_prefix . "Unprotected Form Submission";
        break;
        
    case 'static-csrf':
        $provided_token = $_POST['csrf_token'] ?? '';
        $session_token = $_SESSION['static_csrf_token'] ?? '';
        
        if (empty($provided_token)) {
            $security_status = "STATIC TOKEN MISSING - No token provided";
        } else if (empty($session_token)) {
            $security_status = "STATIC TOKEN ERROR - No session token found";
        } else {
            $csrf_valid = hash_equals($session_token, $provided_token);
            $security_status = $csrf_valid ? 
                "STATIC TOKEN VALID - Basic protection working" : 
                "STATIC TOKEN INVALID - Attack prevented";
        }
        $subject = $subject_prefix . "Static CSRF Form Submission";
        break;
        
    case 'rotating-csrf':
        $provided_token = $_POST['csrf_token'] ?? '';
        
        if (empty($provided_token)) {
            $security_status = "ROTATING TOKEN MISSING - No token provided";
        } else if (!isset($_SESSION['csrf_tokens'])) {
            $security_status = "ROTATING TOKEN ERROR - No token pool initialized";
        } else {
            $csrf_valid = isset($_SESSION['csrf_tokens'][$provided_token]);
            
            if ($csrf_valid) {
                // Check if token is expired
                $token_age = time() - $_SESSION['csrf_tokens'][$provided_token];
                if ($token_age > 300) {
                    $csrf_valid = false;
                    $security_status = "ROTATING TOKEN EXPIRED - Token too old ($token_age seconds)";
                } else {
                    // Remove used token
                    unset($_SESSION['csrf_tokens'][$provided_token]);
                    $security_status = "ROTATING TOKEN VALID - Advanced protection working";
                }
            } else {
                $security_status = "ROTATING TOKEN INVALID - Potential AI attack prevented";
            }
        }
        $subject = $subject_prefix . "Rotating CSRF Form Submission";
        break;
        
    default:
        $security_status = "UNKNOWN FORM TYPE - Submission rejected";
        $subject = $subject_prefix . "Unknown Form Type";
}

// Prepare email content
$email_body = "
CSRF Protection Demo Submission
==============================

Security Status: $security_status
Form Type: $form_type
CSRF Valid: " . ($csrf_valid ? 'Yes' : 'No') . "

Submitted Data:
--------------
Name: $name
Email: $email
Message: $message

Technical Details:
-----------------
IP Address: " . $_SERVER['REMOTE_ADDR'] . "
User Agent: " . $_SERVER['HTTP_USER_AGENT'] . "
Timestamp: " . date('Y-m-d H:i:s') . "

AI Defense Analysis:
-------------------
Form Type Analysis:
- No CSRF: Vulnerable to AI automation
- Static CSRF: AI can reuse tokens
- Rotating CSRF: AI must constantly adapt

This is an educational demonstration showing different levels of CSRF protection.
";

// Configure email headers
$headers = [
    'From' => 'noreply@csrf-demo.local',
    'Reply-To' => $email,
    'X-Mailer' => 'PHP/' . phpversion(),
    'Content-Type' => 'text/plain; charset=UTF-8'
];

// Send email only if CSRF is valid
$email_sent = false;
if ($csrf_valid) {
    // Convert headers array to string
    $headers_string = '';
    foreach ($headers as $key => $value) {
        $headers_string .= "$key: $value\r\n";
    }
    
    // Attempt to send email
    $email_sent = @mail($to, $subject, $email_body, $headers_string);
    
    // Log email attempt
    error_log("Email attempt - To: $to, Subject: $subject, Sent: " . ($email_sent ? 'Yes' : 'No'));
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Submission Result</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="<?php echo $csrf_valid ? 'green-theme' : 'red-theme'; ?>">
    <div class="container">
        <div class="result-box <?php echo $csrf_valid ? 'success' : 'error'; ?>">
            <h1><?php echo $csrf_valid ? '✅ Form Submitted' : '❌ Submission Blocked'; ?></h1>
            <p><strong>Security Status:</strong> <?php echo htmlspecialchars($security_status); ?></p>
            
            <?php if ($csrf_valid && $email_sent): ?>
                <p>✉️ Email notification sent successfully</p>
            <?php elseif ($csrf_valid && !$email_sent): ?>
                <p>⚠️ Form validated but email sending failed</p>
                <small>Check PHP mail configuration or SMTP settings</small>
            <?php else: ?>
                <p>🛡️ The submission was blocked - AI attack prevented!</p>
            <?php endif; ?>
        </div>
        
        <div class="submitted-data">
            <h3>Submitted Information:</h3>
            <ul>
                <li><strong>Name:</strong> <?php echo htmlspecialchars($name); ?></li>
                <li><strong>Email:</strong> <?php echo htmlspecialchars($email); ?></li>
                <li><strong>Message:</strong> <?php echo nl2br(htmlspecialchars($message)); ?></li>
                <li><strong>Form Type:</strong> <?php echo htmlspecialchars($form_type); ?></li>
            </ul>
        </div>
        
        <div class="security-analysis">
            <h3>🔍 Security Analysis:</h3>
            <p><?php 
                if ($form_type === 'no-csrf') {
                    echo "This form had no protection. AI bots could submit millions of these.";
                } elseif ($form_type === 'static-csrf') {
                    echo "Static token provided basic protection, but AI could reuse captured tokens.";
                } elseif ($form_type === 'rotating-csrf') {
                    echo "Rotating tokens forced any attacker to constantly re-authenticate. Maximum protection achieved!";
                }
            ?></p>
        </div>
        
        <a href="index.html" class="back-link">← Try Another Demo</a>
    </div>
</body>
</html>