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
// No CSRF token generation - intentionally vulnerable
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>No CSRF Protection - Vulnerable Form</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="red-theme">
    <header class="red-header">
        <h1>❌ No CSRF Protection</h1>
        <p>This form has NO protection against Cross-Site Request Forgery</p>
    </header>
    
    <div class="container">
        <div class="warning-box red-warning">
            <strong>⚠️ Security Status:</strong> VULNERABLE TO AI ATTACKS
            <br>AI bots can submit this form millions of times per second
        </div>
        
        <form action="process-form.php" method="POST" class="demo-form">
            <input type="hidden" name="form_type" value="no-csrf">
            
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" required>
            </div>
            
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </div>
            
            <div class="form-group">
                <label for="message">Message:</label>
                <textarea id="message" name="message" rows="4" required></textarea>
            </div>
            
            <button type="submit" class="submit-button red-button">Submit (Unprotected)</button>
        </form>
        
        <div class="console-output">
            <h3>🔍 Developer Console</h3>
            <pre>Security Analysis:
- No CSRF token present
- No request validation
- AI can automate submissions
- Zero defense against bots

Network Tab: No token visible (expected - none exists)</pre>
        </div>
        
        <a href="index.html" class="back-link">← Back to Demo Selection</a>
    </div>
    
    <footer class="red-footer">
        <p>Educational Demo - Showing vulnerability without CSRF protection</p>
    </footer>
</body>
</html>