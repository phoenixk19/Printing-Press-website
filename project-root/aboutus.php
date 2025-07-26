
<?php
session_start();
require 'includes/db.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - DPP Printing Press</title>
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

        .tagline-left, .tagline-right {
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

        /* ABOUT CONTENT STYLES */
        main {
            padding: 40px 10%;
            background-color: transparent;
            min-height: calc(100vh - 300px);
        }

        .about-container {
            max-width: 1000px;
            margin: 0 auto;
        }

        .about-section {
            background: white;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            margin-bottom: 30px;
        }

        .about-title {
            font-size: 2rem;
            color: #0066ff;
            margin-bottom: 20px;
            text-align: center;
            position: relative;
            padding-bottom: 15px;
        }

        .about-title:after {
            content: "";
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 3px;
            background: #0066ff;
        }

        .about-paragraph {
            font-size: 1.1rem;
            line-height: 1.8;
            color: #444;
            margin-bottom: 25px;
            padding: 20px;
            background: rgba(255,255,255,0.9);
            border-left: 4px solid #0066ff;
            border-radius: 0 8px 8px 0;
            animation: fadeIn 1s ease-out forwards;
            opacity: 0;
        }

        .about-paragraph:nth-child(1) { animation-delay: 0.2s; }
        .about-paragraph:nth-child(2) { animation-delay: 0.4s; }
        .about-paragraph:nth-child(3) { animation-delay: 0.6s; }
        .about-paragraph:nth-child(4) { animation-delay: 0.8s; }

        .about-paragraph:first-letter {
            font-size: 180%;
            font-weight: bold;
            color: #0066ff;
            padding-right: 5px;
            line-height: 0.8;
        }

        .video-container {
            margin-top: 40px;
            text-align: center;
        }

        .about-video {
            width: 100%;
            max-width: 720px;
            height: auto;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            display: inline-block;
        }
        /* vis mis val Style*/
        .core-values {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
            margin-top: 50px;
        }

        .value-card {
            background: white;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.05);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border-top: 4px solid #0066ff;
            position: relative;
            overflow: hidden;
            
        }
        

        .value-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 10px 30px rgba(0,102,255,0.1);
        }

        .value-card h2 {
            color: #0066ff;
            margin-bottom: 20px;
            font-size: 1.5rem;
            position: relative;
            padding-left: 40px;
        }

        .value-card h2:before {
            content: "";
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 30px;
            height: 30px;
            background-size: contain;
            background-repeat: no-repeat;
        }

        .vision h2:before {
            background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="%230066ff"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm-1-13h2v6h-2V7zm0 8h2v2h-2v-2z"/></svg>');
        }

        .mission h2:before {
            background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="%230066ff"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm-1-13h2v6h-2V7zm0 8h2v2h-2v-2z"/></svg>');
        }

        .values h2:before {
            background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="%230066ff"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 17v-2h2v2h-2zm2.07-7.75-.9.92C13.45 15.9 13 16.5 13 17h-2v-.5c0-1.1.45-2.1 1.17-2.83l1.24-1.26c.37-.36.59-.86.59-1.41 0-1.1-.9-2-2-2s-2 .9-2 2H8c0-2.21 1.79-4 4-4s4 1.79 4 4c0 .88-.36 1.68-.93 2.25z"/></svg>');
        }

        .value-content {
            line-height: 1.7;
            color: #444;
        }

        .values-list {
            padding-left: 20px;
            margin-top: 15px;
        }

        .values-list li {
            margin-bottom: 10px;
            position: relative;
        }

        .values-list li:before {
            content: "•";
            color: #0066ff;
            font-weight: bold;
            position: absolute;
            left: -15px;
        }

        /* History Timeline Styles */
        .history-section {
            margin: 50px 0 40px;
        }

        .history-title {
            text-align: center;
            font-size: 2rem;
            color: #0066ff;
            margin-bottom: 40px;
            position: relative;
        }

        .history-title:after {
            content: "";
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 3px;
            background: #0066ff;
        }

        .timeline {
            position: relative;
            max-width: 800px;
            margin: 0 auto;
        }

        .timeline::after {
            content: '';
            position: absolute;
            width: 4px;
            background-color: #0066ff;
            top: 0;
            bottom: 0;
            left: 50%;
            margin-left: -2px;
        }

        .timeline-item {
            padding: 20px 40px;
            position: relative;
            width: 50%;
            box-sizing: border-box;
            animation: fadeIn 1s ease-out forwards;
            opacity: 0;
        }

        .timeline-item:nth-child(1) { animation-delay: 0.3s; }
        .timeline-item:nth-child(2) { animation-delay: 0.6s; }
        .timeline-item:nth-child(3) { animation-delay: 0.9s; }
        .timeline-item:nth-child(4) { animation-delay: 1.2s; }

        .timeline-item:nth-child(odd) {
            left: 0;
            padding-right: 70px;
            text-align: right;
        }

        .timeline-item:nth-child(even) {
            left: 50%;
            padding-left: 70px;
        }

        .timeline-content {
            padding: 20px;
            background-color: white;
            position: relative;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            border-left: 3px solid #0066ff;
        }

        .timeline-item:nth-child(odd) .timeline-content {
            border-left: none;
            border-right: 3px solid #0066ff;
        }

        .timeline-content h3 {
            color: #0066ff;
            margin-bottom: 10px;
        }

        .timeline-year {
            position: absolute;
            top: 20px;
            z-index: 1;
            background-color: #0066ff;
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            font-weight: bold;
            font-size: 1.1rem;
        }

        .timeline-item:nth-child(odd) .timeline-year {
            right: -80px;
        }

        .timeline-item:nth-child(even) .timeline-year {
            left: -80px;
        }

        /* Awards Section Styles */
        .awards-section {
            margin: 60px 0;
        }

        .awards-title {
            text-align: center;
            font-size: 2rem;
            color: #0066ff;
            margin-bottom: 40px;
            position: relative;
        }

        .awards-title:after {
            content: "";
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 3px;
            background: #0066ff;
        }

        .awards-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
        }

        .award-card {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
            border-top: 4px solid #0066ff;
        }

        .award-card:hover {
            transform: translateY(-10px);
        }

        .award-icon {
            height: 120px;
            background-color: #f0f6ff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 60px;
            color: #0066ff;
        }

        .award-content {
            padding: 25px;
            text-align: center;
        }

        .award-content h3 {
            color: #0066ff;
            margin-bottom: 10px;
        }

        .award-content p {
            color: #555;
            line-height: 1.6;
        }

        /* para entr ANIMATIONS */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
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

        /* RESPONSIVE DESIGN */
        @media (max-width: 768px) {
            header, footer, main {
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

            .about-paragraph {
                padding: 15px;
                font-size: 1rem;
            }

            .timeline::after {
                left: 31px;
            }

            .timeline-item {
                width: 100%;
                padding-left: 70px;
                padding-right: 25px;
                text-align: left !important;
            }

            .timeline-item:nth-child(odd),
            .timeline-item:nth-child(even) {
                left: 0;
                padding-right: 25px;
            }

            .timeline-item:nth-child(odd) .timeline-year,
            .timeline-item:nth-child(even) .timeline-year {
                left: 10px;
                right: auto;
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
                <div class="logo"><img src="assets/logodpp.png" alt="DPP Printing Press Logo"></div>
            </div>
            <div class="tagline-right">info.printing@dpp.com</div>
        </div>
        
        <div class="header-bottom">
            <nav>
                <ul>
                    <li><a href="home.php">Home</a></li>
                    <li><a href="aboutus.php"class="active">About us</a></li>
                    <li><a href="services.php">Services</a></li>
                    <li><a href="news.php">News</a></li>
                    <li><a href="contact.php">Contact</a></li>
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
        <div class="about-container">
            <div class="about-section">
                <h1 class="about-title">About DPP Printing Press</h1>
                
                <p class="about-paragraph">
                    DPP Printing Press stands as a trusted name in Sri Lanka's printing and packaging industry, delivering excellence and innovation with every print. 
                    With years of industry expertise and a passion for precision, 
                    we have established ourselves as a reliable partner for both local and 
                    international clients seeking high-quality printing solutions.
                </p>
                
                <p class="about-paragraph">
                    As a fully Sri Lankan-owned company, DPP Printing Press takes pride in combining traditional craftsmanship with the latest advancements in eco-friendly and cutting-edge printing technology. 
                    From pre-press to press to post-press, 
                    our streamlined processes ensure that every product meets international standards of quality, durability, 
                    and visual appeal.
                </p>
                
                <p class="about-paragraph">
                    Our operations are divided into two specialized units: DPP Local Services, catering to domestic businesses and individual clients, 
                    and DPP Exports, 
                    serving international and export-oriented customers with tailor-made solutions.
                </p>
                
                <p class="about-paragraph">
                    Our extensive product range includes printed cartons and packaging boxes, labels and tags for apparel, 
                    brochures, flyers, magazines, booklets, invitations, gift boxes, and much more. At DPP, 
                    we don't just print—we help bring your vision to life with precision and professionalism.
                </p>
                
                <div class="core-values">
                    <div class="value-card vision">
                        <h2>Our Vision</h2>
                        <div class="value-content">
                            To be Sri Lanka's leading printing solutions provider, recognized for innovation, 
                            sustainability, and exceptional quality that transforms how businesses communicate 
                            and connect with their audiences.
                        </div>
                    </div>
    
                    <div class="value-card mission">
                        <h2>Our Mission</h2>
                        <div class="value-content">
                            We empower businesses with cutting-edge printing solutions that combine traditional 
                            craftsmanship with modern technology. Through eco-friendly practices and unwavering 
                            commitment to quality, we deliver products that exceed expectations and help our 
                            clients make a lasting impression.
                        </div>
                    </div>
    
                    <div class="value-card values">
                        <h2>Our Core Values</h2>
                        <div class="value-content">
                            <ul class="values-list">
                                <li><strong>Quality Excellence:</strong> Uncompromising standards in every print</li>
                                <li><strong>Customer Focus:</strong> Tailored solutions for unique needs</li>
                                <li><strong>Innovation:</strong> Embracing new technologies and techniques</li>
                                <li><strong>Sustainability:</strong> Eco-friendly materials and processes</li>
                                <li><strong>Integrity:</strong> Honest communication and ethical practices</li>
                                <li><strong>Teamwork:</strong> Collaborative approach to success</li>
                            </ul>
                        </div>
                    </div>
                </div>
                
                <!-- History Section -->
                <div class="history-section">
                    <h2 class="history-title">Our Journey Through Time</h2>
                    <div class="timeline">
                        <!-- Timeline Item 1 -->
                        <div class="timeline-item">
                            <div class="timeline-year">2005</div>
                            <div class="timeline-content">
                                <h3>Humble Beginnings</h3>
                                <p>DPP Printing started in a modest workshop in Rajagiriya with just two employees and one small offset machine. Our first big break came when a nearby tuition center ordered a batch of custom bookmarks.</p>
                            </div>
                        </div>
                        
                        <!-- Timeline Item 2 -->
                        <div class="timeline-item">
                            <div class="timeline-year">2010</div>
                            <div class="timeline-content">
                                <h3>Expansion & Modernization</h3>
                                <p>We relocated to a larger 2,500 sq ft space to meet increasing local demand. Added a digital printer to handle short-run jobs for schools, shops, and small offices in the area.</p>
                            </div>
                        </div>
                        
                        <!-- Timeline Item 3 -->
                        <div class="timeline-item">
                            <div class="timeline-year">2015</div>
                            <div class="timeline-content">
                                <h3>Going Green</h3>
                                <p>Introduced basic eco-friendly practices by offering recycled paper options and minimizing waste. Our green printing choices became popular with local NGOs and educational institutions.</p>
                            </div>
                        </div>
                        
                        <!-- Timeline Item 4 -->
                        <div class="timeline-item">
                            <div class="timeline-year">2020</div>
                            <div class="timeline-content">
                                <h3>International Recognition</h3>
                                <p>Built strong ties with neighborhood businesses, handling printing needs for small events, flyers, and menus. Grew to a close-knit team of 10, focused on quality and reliability within the community.</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Awards Section -->
                <div class="awards-section">
                    <h2 class="awards-title">Awards & Recognition</h2>
                    <div class="awards-grid">
                        <!-- Award 1 -->
                        <div class="award-card">
                            <div class="award-icon">🏆</div>
                            <div class="award-content">
                                <h3>Print Excellence Award 2023</h3>
                                <p>Awarded by the Srilankan Printing Federation for outstanding quality in commercial printing and innovative use of sustainable materials.</p>
                            </div>
                        </div>
                        
                        <!-- Award 2 -->
                        <div class="award-card">
                            <div class="award-icon">🌟</div>
                            <div class="award-content">
                                <h3>Eco Business Leader 2022</h3>
                                <p>Recognized by Sri Lanka Green Initiative for our commitment to environmentally responsible printing practices and waste reduction programs.</p>
                            </div>
                        </div>
                        
                        <!-- Award 3 -->
                        <div class="award-card">
                            <div class="award-icon">🏅</div>
                            <div class="award-content">
                                <h3>Best Packaging Design 2021</h3>
                                <p>Received at the Colombo Design Expo for our innovative tea packaging that combined traditional motifs with modern sustainable materials.</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="video-container">
                    <video class="about-video" controls poster="video-poster.jpg">
                        <source src="video.mp4" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer Section -->
    <footer>
        <div class="footer-top">
            <div class="footer-logo">
                <img src="assets/logodpp.png" alt="DPP Printing Press Logo">
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
                Copyright © 2025 DPP Printing Press. All rights reserved.
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
        });
    </script>
</body>
</html>
