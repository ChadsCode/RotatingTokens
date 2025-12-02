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

// Generate a static CSRF token for the session
if (!isset($_SESSION['static_csrf_token'])) {
    $_SESSION['static_csrf_token'] = bin2hex(random_bytes(32));
}
$csrf_token = $_SESSION['static_csrf_token'];

// Debug: Log token generation
error_log("Static CSRF Token Generated: " . $csrf_token);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Static CSRF Protection - Standard Security</title>
    <link rel="stylesheet" href="style.css">
    <script>
        // Make token visible in console for demo purposes
        console.log('Static CSRF Token:', '<?php echo $csrf_token; ?>');
    </script>
</head>
<body class="yellow-theme">
    <header class="yellow-header">
        <h1>⚠️ Static CSRF Token Protection</h1>
        <p>Industry standard protection - One token per session</p>
    </header>
    
    <div class="container">
        <div class="warning-box yellow-warning">
            <strong>🛡️ Security Status:</strong> BASIC PROTECTION
            <br>Protected against simple attacks, but AI can learn static patterns
        </div>
        
        <form action="process-form.php" method="POST" class="demo-form">
            <input type="hidden" name="form_type" value="static-csrf">
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token); ?>" id="static-csrf-token">
            
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
            
            <button type="submit" class="submit-button yellow-button">Submit (Static Protection)</button>
        </form>
        
        <div class="console-output">
            <h3>🔍 Developer Console</h3>
            <pre>CSRF Token (static for this session):
<span style="color: #ffeb3b;"><?php echo htmlspecialchars($csrf_token); ?></span>

Check Network Tab: Look for "csrf_token" in Form Data
This token remains constant - AI can reuse it!</pre>
        </div>
        
        <div class="ai-vulnerability">
            <h4>⚠️ AI Vulnerability:</h4>
            <p>Once AI captures this token, it can reuse it for the entire session. Traditional CAPTCHA won't help - AI solves those too!</p>
        </div>
        
        <a href="index.html" class="back-link">← Back to Demo Selection</a>
    </div>
    
    <footer class="yellow-footer">
        <p>Educational Demo - Standard CSRF protection used by most websites</p>
    </footer>
</body>
</html>