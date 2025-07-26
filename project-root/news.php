<?php
session_start();
require 'includes/db.php';

// get from database
$news = [];
$newsQuery = "SELECT * FROM news ORDER BY date DESC";
$result = $conn->query($newsQuery);
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $news[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>News - DPP Printing</title>
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
            text-decoration: underline;
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

        /* NEWS PAGE STYLES */
        .news-container {
            max-width: 1200px;
            margin: 40px auto;
            padding: 0 20px;
        }

        .page-title {
            text-align: center;
            margin-bottom: 40px;
            color: #333;
            font-size: 2.5rem;
        }

        .news-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 30px;
        }

        .news-card {
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            transition: transform 0.3s;
        }
        
        .news-card:hover {
            transform: translateY(-5px);
        }
        
        .news-image {
            height: 200px;
            background-size: cover;
            background-position: center;
            background-color: #f0f6ff;
        }
        
        .news-content {
            padding: 20px;
        }
        
        .news-date {
            color: #666;
            font-size: 0.9rem;
            margin-bottom: 8px;
        }
        
        .news-title {
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 10px;
            color: #333;
        }
        
        .news-excerpt {
            color: #555;
            line-height: 1.6;
            margin-bottom: 15px;
        }
        
        .no-news {
            text-align: center;
            padding: 40px;
            background: #f8f9fa;
            border-radius: 12px;
            color: #666;
            grid-column: 1 / -1;
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

            .news-grid {
                grid-template-columns: 1fr;
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
                    <li><a href="news.php" class="active">News</a></li>
                    <li><a href="contact.php">Contact</a></li>
                </ul>
            </nav>
            <div class="auth-buttons">
                <a href="signup.php" class="sign-up">Sign up</a>
                <a href="signin.php" class="sign-in">Sign in</a>
            </div>
        </div>
    </header>

    
    <!-- News Content -->
    <main>
    <div class="news-container">
        <h1 class="page-title">Latest News & Updates</h1>
        
        <div class="news-grid">
            <?php if (count($news) > 0): ?>
                <?php foreach ($news as $newsItem): ?>
                    <div class="news-card">
                        <div class="news-image" style="background-image: url('<?= $newsItem['image'] ?>')"></div>
                        <div class="news-content">
                            <div class="news-date"><?= date('F j, Y', strtotime($newsItem['date'])) ?></div>
                            <h3 class="news-title"><?= htmlspecialchars($newsItem['title']) ?></h3>
                            <p class="news-excerpt"><?= htmlspecialchars(substr($newsItem['content'], 0, 150)) ?>...</p>
                            
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="no-news">
                    <p>No news available at the moment. Check back soon!</p>
                </div>
            <?php endif; ?>
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
                        <p>info@dppprinting.com</p>
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