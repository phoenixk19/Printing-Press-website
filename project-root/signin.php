<?php
session_start();
require 'includes\db.php';

// Initialize variables
$error = '';
$email = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    // Basic validation
    if (empty($email) || empty($password)) {
        $error = "Please enter both email and password";
    } else {
        $stmt = $conn->prepare("SELECT id, name, email, password, role FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows == 1) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                // Login successful
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['name'] = $user['name'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['role'] = $user['role'];
                
                if($user['role'] === 'admin') {
                    header("Location: admin/admin_panel.php");
                } else {
                    header("Location: order.php");
                }
                exit();
            } else {
                $error = "Invalid email or password";
            }
        } else {
            $error = "Invalid email or password";
        }
        $stmt->close();
    }
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Homepage</title>
    
    <style>
       
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Arial', sans-serif;
        }

        /* HEADER STYLES */
        header {
            background-color: #ffffff;
            padding: 20px 10%;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .header-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            position: relative;
        }

        .tagline-left,
        .tagline-right {
            color: #666;
            font-size: 14px;
        }

        .logo-container {
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
        }

        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #333;
            white-space: nowrap;
        }

        .header-bottom {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-top: 1px solid #eee;
            padding-top: 15px;
        }

        nav ul {
            display: flex;
            list-style: none;
            gap: 25px;
        }

        nav ul li a {
            text-decoration: none;
            color: #333;
            font-weight: 500;
            transition: color 0.3s;
        }
        nav ul li a.active {
            color: #0066ff;
            text-decoration: underline;
        }

        nav ul li a:hover {
            color: #0066ff;
        }

        .auth-buttons {
            display: flex;
            visibility: hidden;
            
        }

        .submit  {
            padding: 8px 20px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s;
        }
        .sign-up:hover {
            background-color: #0052cc;
        }

        .sign-in:hover {
            background-color: #f0f6ff;
        }

        /* MAIN CONTENT STYLES */
        main {
            padding: 60px 10%;
            background-color: transparent;
            min-height: 400px;
        }

        main h1 {
            font-size: 32px;
            margin-bottom: 20px;
            color: #222;
        }

        main p {
            font-size: 18px;
            color: #555;
        }
        .login-container {
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            padding: 40px;
            margin-left: 25vw;
        }
        
        .logo {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .logo h1 {
            font-size: 24px;
            color: #333;
            font-weight: bold;
        }
        
        
        .login-form h2 {
            font-size: 20px;
            color: #333;
            margin-bottom: 20px;
            text-align: center;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #555;
            font-size: 14px;
        }
        
        .form-group input {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 14px;
            transition: border-color 0.3s;
        }
        
        .form-group input:focus {
            outline: none;
            border-color: #0066ff;
        }
        
        .remember-forgot {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            font-size: 13px;
        }
        
        .remember-me {
            display: flex;
            align-items: center;
        }
        
        .remember-me input {
            margin-right: 5px;
        }
        
        .forgot-password a {
            color: #0066ff;
            text-decoration: none;
        }
        
        .login-button {
            width: 100%;
            padding: 12px;
            background-color: #0066ff;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            font-weight: 500;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        
        .login-button:hover {
            background-color: #0052cc;
        }
        
        .signup-link {
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
            color: #666;
        }
        
        .signup-link a {
            color: #0066ff;
            text-decoration: none;
            font-weight: 500;
        }
        
        @media (max-width: 480px) {
            .login-container {
                padding: 30px 20px;
            }
        }
         /* Add alert styling */
         .alert {
            padding: 15px;
            margin-bottom: 20px;
            border: 1px solid transparent;
            border-radius: 4px;
            text-align: center;
        }
        
        .alert-danger {
            background-color: #f8d7da;
            border-color: #f5c6cb;
            color: #721c24;
        }
        
        .shake {
            animation: shake 0.5s;
            animation-iteration-count: 1;
        }
        
        @keyframes shake {
            0% { transform: translateX(0); }
            20% { transform: translateX(-10px); }
            40% { transform: translateX(10px); }
            60% { transform: translateX(-10px); }
            80% { transform: translateX(10px); }
            100% { transform: translateX(0); }
        }
        
        .error-field {
            border-color: #dc3545 !important;
            box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
        }
        
        .error-text {
            color: #dc3545;
            font-size: 0.875em;
            margin-top: 5px;
            display: block;
        }

        /* FOOTER STYLES */
        footer {
            background-color: #f8f9fa;
            padding: 50px 10% 30px;
            color: #333;
            border: 1px solid #ddd;
            border-radius: 12px;
            margin: 20px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }

        .footer-top {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            margin-bottom: 40px;
            border-bottom: 1px solid #ddd;
            padding-bottom: 40px;
        }

        .footer-logo {
            flex: 0 0 200px;
            margin-bottom: 30px;
        }

        .footer-logo img {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
        }

        .footer-columns {
            display: flex;
            flex-wrap: wrap;
            justify-content: flex-end;
            flex: 1;
        }

        .footer-column {
            flex: 0 0 200px;
            margin-bottom: 30px;
            padding: 0 15px;
        }

        .footer-column h3 {
            font-size: 18px;
            margin-bottom: 20px;
            color: #222;
        }

        .footer-column ul {
            list-style: none;
        }

        .footer-column ul li {
            margin-bottom: 10px;
        }

        .footer-column ul li a {
            text-decoration: none;
            color: #666;
            transition: color 0.3s;
        }

        .footer-column ul li a:hover {
            color: #000;
        }

        .contact-info {
            margin-top: 10px;
            line-height: 1.6;
            color: #666;
        }

        .footer-bottom {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            align-items: center;
            padding-top: 20px;
        }

        .copyright {
            color: #666;
            font-size: 14px;
        }

        .legal-links {
            display: flex;
            gap: 20px;
        }

        .legal-links a {
            text-decoration: none;
            color: #666;
            font-size: 14px;
            transition: color 0.3s;
        }

        .legal-links a:hover {
            color: #000;
        }

        /* Responsive */
        @media (max-width: 768px) {
            header, footer {
                padding: 15px 5%;
            }

            .header-top {
                flex-direction: column;
                gap: 10px;
                margin-bottom: 15px;
            }

            .logo-container {
                position: static;
                transform: none;
                margin-bottom: 10px;
            }

            .header-bottom {
                flex-direction: column;
                gap: 15px;
            }

            nav ul {
                flex-wrap: wrap;
                justify-content: center;
            }

            .auth-buttons {
                margin-top: 10px;
            }

            .footer-logo {
                flex: 0 0 100%;
                text-align: center;
            }

            .footer-columns {
                justify-content: space-around;
            }

            .footer-column {
                flex: 0 0 45%;
            }

            .footer-bottom {
                flex-direction: column;
                text-align: center;
                gap: 15px;
            }
        }
        /* Paper Animation */
        .paper-background {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: -1;
            overflow: hidden;
        }

        .paper {
            position: absolute;
            background-color: rgba(122,197,255,0.7);
            width: 15px;
            height: 15px;
            opacity: 0.7;
            animation: falling linear infinite;
        }

        @keyframes falling {
            0% {
                transform: translateY(-100px) rotate(0deg);
            }
            100% {
                transform: translateY(100vh) rotate(360deg);
            }
        }
    </style>
</head>
<body style="background-color: aliceblue;">

    <!-- Header Section -->
    <header>
        <div class="header-top">
            <div class="tagline-left">+94362258724</div>
            <div class="logo-container">
                <div class="logo"><img src="assets/logodpp.png" alt="logo"></div>
            </div>
            <div class="tagline-right">info.printing@dpp.com</div>
        </div>
        
        <div class="header-bottom">
            <nav>
                <ul>
                    <li><a href="home.php">Home</a></li>
                    <li><a href="aboutus.php">About us</a></li>
                    <li><a href="services.php">Services</a></li>
                    <li><a href="news.php">News</a></li>
                    <li><a href="contact.php">Contact</a></li>
                </ul>
            </nav>
            <div class="auth-buttons">
                
                
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main>
        <div class="login-container">
            <div class="logo">
                <h1>DPP Printing</h1>
            </div>
            
            <!-- Display error message -->
            <?php if (!empty($error)): ?>
                <div class="alert alert-danger" id="error-alert">
                    <?= $error ?>
                </div>
            <?php endif; ?>
            
            <div class="login-form">
                <h2>Sign in to your account</h2>
                
                <form action="signin.php" method="POST" id="loginForm">
                    <div class="form-group">
                        <label for="email">Email address</label>
                        <input type="email" id="email" name="email" class="<?php echo ($error && !empty($email)) ? 'error-field' : ''; ?>" 
                               value="<?= htmlspecialchars($email) ?>" required>
                        <?php if ($error === 'Email not found'): ?>
                            <span class="error-text">This email is not registered</span>
                        <?php endif; ?>
                    </div>
                    
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" class="<?php echo ($error === 'Incorrect password') ? 'error-field' : ''; ?>" required>
                        <?php if ($error === 'Incorrect password'): ?>
                            <span class="error-text">The password you entered is incorrect</span>
                        <?php endif; ?>
                    </div>
                    
                    <div class="remember-forgot">
                        <div class="remember-me">
                            <input type="checkbox" id="remember" name="remember">
                            <label for="remember">Remember me</label>
                        </div>
                        <div class="forgot-password">
                            <a href="#">Forgot password?</a>
                        </div>
                    </div>
                    
                    <button type="submit" class="login-button">Sign in</button>
                </form>
                
                <div class="signup-link">
                    Don't have an account? <a href="signup.php">Sign up</a>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer Section -->
    <footer>
        <div class="footer-top">
            <div class="footer-logo">
                <img src="assets/logodpp.png" alt="Company Logo">
                <p>Your professional printing partner, delivering consistent quality and value across every project</p>
            </div>
            
            <div class="footer-columns">
                <div class="footer-column">
                    <h3>Links</h3>
                    <ul>
                        <li><a href="aboutus.php">About us</a></li>
                        <li><a href="services.php">Services</a></li>
                        <li><a href="news.php">News</a></li>
                        <li><a href="contact.php">Contact</a></li>
                    </ul>
                </div>

                <div class="footer-column">
                    <h3>Services</h3>
                    <ul>
                        <li><a href="services.php#bb">Book Binding</a></li>
                        <li><a href="services.php#bnp">Banners & Poster</a></li>
                        <li><a href="services.php#sdp">Standerd Printing</a></li>
                        <li><a href="services.php#bc">Business Card</a></li>
                    </ul>
                </div>
                
                <div class="footer-column">
                    <h3>Contact us</h3>
                    <div class="contact-info">
                        <p>+94362258724</p>
                        <p>info@dpppriting.com</p>
                        <p>No 1, Dharmapala place, Rajagiriya</p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="footer-bottom">
            <div class="copyright">
                Copyright © 2025 Website. All rights reserved.
            </div>
            
            <div class="legal-links">
                <p>Terms & Conditions</p>
                <p>Privacy Policy</p>
            </div>
        </div>
    </footer>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const paperBackground = document.createElement('div');
            paperBackground.className = 'paper-background';
            document.body.appendChild(paperBackground);

            // Create 50 paper pieces
            for (let i = 0; i < 50; i++) {
                createPaper();
            }

            function createPaper() {
                const paper = document.createElement('div');
                paper.className = 'paper';
            
                // Random properties
                const size = Math.random() * 10 + 5;
                const left = Math.random() * 100;
                const animationDuration = Math.random() * 10 + 10;
                const delay = Math.random() * 15;
                const rotation = Math.random() * 360;
            
                paper.style.width = `${size}px`;
                paper.style.height = `${size}px`;
                paper.style.left = `${left}%`;
                paper.style.animationDuration = `${animationDuration}s`;
                paper.style.animationDelay = `${delay}s`;
                paper.style.transform = `rotate(${rotation}deg)`;
                paper.style.opacity = Math.random() * 0.5 + 0.3;
            
                paperBackground.appendChild(paper);
            }
        }
        );
    </script>

</body>
</html>
