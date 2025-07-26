<?php
require 'includes/auth.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isLoggedIn()) {
    $name = $_POST['name'];
    $designation = $_POST['designation'];
    $testimonial = $_POST['testimonial'];
    $userId = $_SESSION['user_id'];

    $stmt = $conn->prepare("INSERT INTO testimonials (user_id, name, designation, content) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isss", $userId, $name, $designation, $testimonial);
    
    if ($stmt->execute()) {
        $success = "Testimonial submitted!";
    }
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
            gap: 15px;
        }

        .auth-buttons a {
            padding: 8px 20px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s;
        }

        .sign-up {
            background-color: #0066ff;
            color: white;
        }

        .sign-in {
            border: 1px solid #0066ff;
            color: #0066ff;
        }

        .sign-up:hover {
            background-color: #0052cc;
        }

        .sign-in:hover {
            background-color: #f0f6ff;
        }

        /* MAIN  STYLES */
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

        .testimonial-container {
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 40px 20px;
            font-family: 'Arial', sans-serif;
        }
    
        .testimonial-box {
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            padding: 40px;
            max-width: 800px;
            width: 100%;
        }
    
        .testimonial-heading {
            text-align: center;
            font-size: 2.2rem;
            font-weight: bold;
            margin-bottom: 10px;
            color: #333;
        }
    
        .testimonial-subheading {
            text-align: center;
            font-size: 1rem;
            color: #666;
            margin-bottom: 40px;
        }
    
        .testimonial-content {
            display: flex;
            gap: 30px;
            align-items: flex-start;
        }
    
        .profile-circle {
            min-width: 80px;
        }
    
        .profile-img {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            object-fit: cover;
        }
    
        .quote-container {
            position: relative;
            padding-left: 40px; /* Space for the quote mark */
        }
    
        .quote-mark {
            position: absolute;
            font-size: 120px;
            color: rgba(0, 0, 0, 0.1);
            font-weight: bold;
            top: -50px;
            left: -20px;
            line-height: 1;
    }
    
        .testimonial-text {
            flex: 1;
        }
    
        .testimonial-text p {
            font-size: 1.1rem;
            line-height: 1.6;
            color: #555;
            margin-bottom: 20px;
            position: relative;
        }
    
        .author-info {
            display: flex;
            flex-direction: column;
        }
    
        .author-info strong {
            font-size: 1.1rem;
            color: #333;
        }
    
        .author-info span {
            font-size: 0.9rem;
            color: #777;
        }
        /* tes form*/
        .testimonial-form-container {
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 500px;
            padding: 30px;
        }
        
        .form-header {
            text-align: center;
            margin-bottom: 25px;
        }
        
        .form-header h2 {
            color: #333;
            margin-bottom: 5px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #555;
            font-weight: 500;
        }
        
        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 15px;
            transition: border-color 0.3s;
        }
        
        .form-group input:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #3a86ff;
        }
        
        .form-group textarea {
            min-height: 120px;
            resize: vertical;
        }
        
        .submit-btn {
            width: 100%;
            padding: 12px;
            background-color: #3a86ff;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            font-weight: 500;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        
        .submit-btn:hover {
            background-color: #2667d6;
        }
        /* COntact*/
        .contact-info {
            line-height: 1.6; 
        }
        .contact-info p { 
            margin: 15px 0; 
        }
        strong { 
            display: block; 
            margin-bottom: 5px; 
        }

        /* FOOTER */
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
                    <li><a href="contact.php"class="active">Contact</a></li>
                </ul>
            </nav>
            <div class="auth-buttons">
                <a href="signup.php" class="sign-up">Sign up</a>
                <a href="signin.php" class="sign-in">Sign in</a>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main>
        <div class="testimonial-box">
            <h2 class="testimonial-heading">Testimonials</h2>
            
            
            <div class="testimonial-content">
                <div class="profile-circle">
                    <img src="assets\printing.jpeg" alt="Profile" class="profile-img">
                </div>
                <div class="testimonial-text">
                    <div class="quote-container">
                        <span class="quote-mark">"</span>
                        <p>Working with DPP Printing Press has been a seamless experience from start to finish. Their attention to detail, fast turnaround time, and commitment to quality printing exceeded our expectations. 
                            We're proud to have them as a trusted 
                            partner for all our printing needs.
                        </p>
                    </div>
                    <div class="author-info">
                        <strong>Adam Smith</strong>
                        <span>Designation</span>
                    </div>
                </div>
            </div>
        </div>
        <br>
        <div class="testimonial-form-container">
            <div class="form-header">
                <h2>Add Testimonial</h2>
                <p>Share your experience with us</p>
            </div>
            
            <form action="#" method="POST">
                <div class="form-group">
                    <label for="name">Full Name</label>
                    <input type="text" id="name" name="name" placeholder="John Doe" required>
                </div>
                
                <div class="form-group">
                    <label for="designation">Designation</label>
                    <input type="text" id="designation" name="designation" placeholder="e.g. CEO, ABC Company" required>
                </div>
                
                <div class="form-group">
                    <label for="testimonial">Testimonial</label>
                    <textarea id="testimonial" name="testimonial" placeholder="Share your experience..." required></textarea>
                </div>
                
                <button type="submit" class="submit-btn">Submit Testimonial</button>
            </form>
        </div>
        <div class="contact-info">
            <p>📍 <strong>Office Address:</strong>
               DPP Printing Press<br>
               No. 1 Dharmapala Place,<br>
               Rajagiriya, Sri Lanka</p>
            
            <p>📞 <strong>Phone:</strong> +94362258724</p>
            
            <p>✉️ <strong>Email:</strong> info@dppprinting.com</p>
            
            <p>🕒 <strong>Business Hours:</strong>
               Mon-Fri: 8:30 AM - 5:30 PM<br>
               Sat: 9:00 AM - 1:00 PM<br>
               Sun: Closed</p>
        </div>
        <div class="map-section">
                <h2>Our Location</h2>
                <div class="map-container">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m12!1m3!1d294.38898723585066!2d79.89519631184287!3d6.9132706418146315!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!5e0!3m2!1sen!2slk!4v1750539589446!5m2!1sen!2slk" width="100%" height="320" style="border:0; border-radius: 12px; box-shadow: 0 2px 8px rgba(22,163,74,0.10);" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
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
