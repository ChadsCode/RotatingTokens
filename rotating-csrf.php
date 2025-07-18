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

// Initialize rotating token system
if (!isset($_SESSION['csrf_tokens'])) {
    $_SESSION['csrf_tokens'] = [];
}

// Generate initial token
$initial_token = bin2hex(random_bytes(32));
$_SESSION['csrf_tokens'][$initial_token] = time();

// Clean old tokens (older than 5 minutes)
foreach ($_SESSION['csrf_tokens'] as $token => $timestamp) {
    if (time() - $timestamp > 300) {
        unset($_SESSION['csrf_tokens'][$token]);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rotating CSRF Protection - Advanced Security</title>
    <link rel="stylesheet" href="style.css">
    <script>
        // Rotating token system
        let currentToken = '<?php echo $initial_token; ?>';
        
        // Log initial token for demo
        console.log('Initial Rotating Token:', currentToken);
        
        async function getNewToken() {
            try {
                const response = await fetch('get-rotating-token.php');
                const data = await response.json();
                console.log('New Token Received:', data.token);
                return data.token;
            } catch (error) {
                console.error('Failed to get new token:', error);
                return currentToken;
            }
        }
        
        async function rotateToken(fieldName) {
            const newToken = await getNewToken();
            currentToken = newToken;
            
            // Update hidden token field
            document.getElementById('csrf_token').value = newToken;
            
            // Update console display
            updateConsole(`Token rotated after ${fieldName} field interaction`);
        }
        
        function updateConsole(action) {
            const console = document.getElementById('token-display');
            const timestamp = new Date().toLocaleTimeString();
            console.innerHTML = `<strong>Current Token:</strong>
<span style="color: #4caf50;">${currentToken}</span>

<strong>Last Action:</strong> ${action}
<strong>Timestamp:</strong> ${timestamp}

<em>Check Network Tab: Each interaction creates a new token!</em>
<em>AI can't predict the next token - true randomness</em>`;
        }
        
        // Initialize display
        window.onload = function() {
            updateConsole('Page loaded');
            
            // Add rotation listeners to form fields
            const inputs = document.querySelectorAll('input[type="text"], input[type="email"], textarea');
            inputs.forEach(input => {
                input.addEventListener('blur', function() {
                    rotateToken(this.name);
                });
            });
        }
    </script>
</head>
<body class="green-theme">
    <header class="green-header">
        <h1>✅ Rotating CSRF Token Protection</h1>
        <p>Advanced protection with tokens that change on every interaction</p>
    </header>
    
    <div class="container">
        <div class="warning-box green-warning">
            <strong>🔒 Security Status:</strong> Rotating Protection
            <br>AI-resistant design - Tokens expire and rotate unpredictably
        </div>
        
        <form action="process-form.php" method="POST" class="demo-form" onsubmit="rotateToken('form submission')">
            <input type="hidden" name="form_type" value="rotating-csrf">
            <input type="hidden" id="csrf_token" name="csrf_token" value="<?php echo $initial_token; ?>">
            
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" required>
                <small>Token rotates when you leave this field</small>
            </div>
            
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
                <small>Token rotates when you leave this field</small>
            </div>
            
            <div class="form-group">
                <label for="message">Message:</label>
                <textarea id="message" name="message" rows="4" required></textarea>
                <small>Token rotates when you leave this field</small>
            </div>
            
            <button type="submit" class="submit-button green-button">Submit (Maximum Protection)</button>
        </form>
        
        <div class="console-output">
            <h3>🔍 Developer Console - Live Token Rotation</h3>
            <pre id="token-display">Loading...</pre>
        </div>
        
        <div class="explanation-box">
            <h4>How This Defeats AI & Quantum Attacks:</h4>
            <ul>
                <li><strong>Unpredictable:</strong> Cryptographically secure randomness</li>
                <li><strong>Moving Target:</strong> Tokens invalid after single use</li>
                <li><strong>Time Windows:</strong> 5-minute expiration limits attack duration</li>
                <li><strong>Resource Intensive:</strong> Forces attackers to maintain state</li>
                <li><strong>Detection Opportunity:</strong> Rapid token requests = red flag</li>
            </ul>
            
            <p class="quantum-note"><strong>Quantum Note:</strong> Even quantum computers can't break true randomness - they can factor numbers, but can't predict random token generation!</p>
        </div>
        
        <a href="index.html" class="back-link">← Back to Demo Selection</a>
    </div>
    
    <footer class="green-footer">
        <p>Educational Demo - Advanced CSRF protection buying time against AI attacks</p>
    </footer>
</body>
</html>