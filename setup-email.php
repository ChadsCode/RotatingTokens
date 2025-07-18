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
// Email Configuration Helper
// Run this file to test email configuration

$encoded_email = "Y2hhZHJ3aWdpbmd0b25AZ21haWwuY29t";
$to = base64_decode($encoded_email);

echo "Email Configuration Test\n";
echo "========================\n\n";

// Check PHP mail configuration
echo "PHP Version: " . phpversion() . "\n";
echo "Mail function exists: " . (function_exists('mail') ? 'Yes' : 'No') . "\n\n";

// Get php.ini mail settings
echo "Current mail settings in php.ini:\n";
echo "SMTP: " . ini_get('SMTP') . "\n";
echo "smtp_port: " . ini_get('smtp_port') . "\n";
echo "sendmail_from: " . ini_get('sendmail_from') . "\n";
echo "sendmail_path: " . ini_get('sendmail_path') . "\n\n";

// Test email
$subject = "CSRF Demo - Email Configuration Test";
$message = "This is a test email from the CSRF demo project.\n\nIf you receive this, email is configured correctly!";
$headers = "From: noreply@csrf-demo.local\r\n";

echo "Attempting to send test email to: " . $to . "\n";
$result = mail($to, $subject, $message, $headers);

if ($result) {
    echo "✅ Email sent successfully!\n";
} else {
    echo "❌ Email failed to send.\n\n";
    echo "Common fixes:\n";
    echo "1. Install a mail server (sendmail, postfix, etc.)\n";
    echo "2. Configure SMTP in php.ini\n";
    echo "3. Use a service like MailHog for local development\n";
}
?>