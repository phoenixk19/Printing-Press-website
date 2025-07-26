<?php
session_start();
require 'includes/db.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Services - DPP Printing</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Arial', sans-serif;
        }

        /* Header Styles */
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

        /* Main Content Styles */
        main {
            padding: 40px 10%;
            background-color: transparent;
            min-height: 400px;
        }

        .services-intro {
            text-align: center;
            margin-bottom: 50px;
        }

        .services-intro h1 {
            font-size: 36px;
            color: #0066ff;
            margin-bottom: 15px;
        }

        .services-intro p {
            font-size: 18px;
            color: #555;
            max-width: 800px;
            margin: 0 auto;
        }

        .services-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 30px;
            margin-bottom: 50px;
        }

        .service-card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            padding: 30px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .service-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }

        .service-icon {
            font-size: 40px;
            color: #0066ff;
            margin-bottom: 20px;
        }

        .service-card h3 {
            font-size: 22px;
            color: #333;
            margin-bottom: 15px;
        }

        .service-card p {
            color: #555;
            margin-bottom: 20px;
            line-height: 1.6;
        }

        .service-pricing {
            background: #f0f6ff;
            padding: 15px;
            border-radius: 8px;
            margin-top: 20px;
        }

        .service-pricing h4 {
            font-size: 18px;
            color: #0066ff;
            margin-bottom: 10px;
        }

        .pricing-table {
            width: 100%;
            border-collapse: collapse;
        }

        .pricing-table th,
        .pricing-table td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        .pricing-table th {
            background-color: #e6f0ff;
            color: #333;
        }

        .pricing-table tr:last-child td {
            border-bottom: none;
        }

        .cta-section {
            text-align: center;
            padding: 40px;
            background: linear-gradient(135deg, #0066ff 0%, #0044cc 100%);
            border-radius: 10px;
            color: white;
            margin-top: 30px;
        }

        .cta-section h2 {
            font-size: 32px;
            margin-bottom: 20px;
        }

        .cta-section p {
            font-size: 18px;
            margin-bottom: 30px;
            max-width: 700px;
            margin-left: auto;
            margin-right: auto;
        }

        .cta-button {
            display: inline-block;
            padding: 14px 40px;
            background: white;
            color: #0066ff;
            text-decoration: none;
            font-weight: bold;
            border-radius: 50px;
            font-size: 18px;
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .cta-button:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.2);
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

            .services-grid {
                grid-template-columns: 1fr;
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
                    <li><a href="services.php" class="active">Services</a></li>
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
        <div class="services-intro">
            <h1>Our Printing Services</h1>
            <p>DPP Printing offers a wide range of high-quality printing services to meet all your personal and business needs. Explore our services and competitive pricing below.</p>
        </div>
        
        <div class="services-grid">
            <!-- Service 1 -->
            <div class="service-card" id = "sdp">
                <div class="service-icon">📄</div>
                <h3>Standard Document Printing</h3>
                <p>Professional printing for documents, reports, contracts, and more. High-quality output on premium paper.</p>
                
                <div class="service-pricing">
                    <h4>Pricing</h4>
                    <table class="pricing-table">
                        <tr>
                            <th>Size</th>
                            <th>Black & White</th>
                            <th>Color</th>
                        </tr>
                        <tr>
                            <td>A4</td>
                            <td>Rs. 10/page</td>
                            <td>Rs. 25/page</td>
                        </tr>
                        <tr>
                            <td>A3</td>
                            <td>Rs. 20/page</td>
                            <td>Rs. 50/page</td>
                        </tr>
                        <tr>
                            <td>Letter</td>
                            <td>Rs. 12/page</td>
                            <td>Rs. 30/page</td>
                        </tr>
                    </table>
                </div>
            </div>
            
            <!-- Service 2 -->
            <div class="service-card">
                <div class="service-icon">📊</div>
                <h3>Brochures & Flyers</h3>
                <p>Eye-catching marketing materials with vibrant colors and premium finishes to promote your business.</p>
                
                <div class="service-pricing">
                    <h4>Pricing</h4>
                    <table class="pricing-table">
                        <tr>
                            <th>Quantity</th>
                            <th>Single-Sided</th>
                            <th>Double-Sided</th>
                        </tr>
                        <tr>
                            <td>100</td>
                            <td>Rs. 1,500</td>
                            <td>Rs. 2,500</td>
                        </tr>
                        <tr>
                            <td>500</td>
                            <td>Rs. 5,000</td>
                            <td>Rs. 8,000</td>
                        </tr>
                        <tr>
                            <td>1000</td>
                            <td>Rs. 8,000</td>
                            <td>Rs. 12,000</td>
                        </tr>
                    </table>
                </div>
            </div>
            
            <!-- Service 3 -->
            <div class="service-card" id ="bb">
                <div class="service-icon">📚</div>
                <h3>Book Binding</h3>
                <p>Professional binding services for books, theses, reports, and presentations with various binding options.</p>
                
                <div class="service-pricing">
                    <h4>Pricing</h4>
                    <table class="pricing-table">
                        <tr>
                            <th>Binding Type</th>
                            <th>Up to 50 pages</th>
                            <th>51-200 pages</th>
                        </tr>
                        <tr>
                            <td>Stapled</td>
                            <td>Rs. 50</td>
                            <td>Rs. 100</td>
                        </tr>
                        <tr>
                            <td>Spiral</td>
                            <td>Rs. 100</td>
                            <td>Rs. 200</td>
                        </tr>
                        <tr>
                            <td>Perfect</td>
                            <td>Rs. 300</td>
                            <td>Rs. 500</td>
                        </tr>
                    </table>
                </div>
            </div>
            
            <!-- Service 4 -->
            <div class="service-card" id = "bc">
                <div class="service-icon">🎨</div>
                <h3>Business Cards</h3>
                <p>Premium quality business cards with various finishes to make a lasting impression.</p>
                
                <div class="service-pricing">
                    <h4>Pricing</h4>
                    <table class="pricing-table">
                        <tr>
                            <th>Finish</th>
                            <th>100 cards</th>
                            <th>500 cards</th>
                        </tr>
                        <tr>
                            <td>Standard</td>
                            <td>Rs. 800</td>
                            <td>Rs. 2,500</td>
                        </tr>
                        <tr>
                            <td>Glossy</td>
                            <td>Rs. 1,200</td>
                            <td>Rs. 4,000</td>
                        </tr>
                        <tr>
                            <td>Matte</td>
                            <td>Rs. 1,500</td>
                            <td>Rs. 5,000</td>
                        </tr>
                    </table>
                </div>
            </div>
            
            <!-- Service 5 -->
            <div class="service-card" id = "bnp">
                <div class="service-icon">📋</div>
                <h3>Posters & Banners</h3>
                <p>Large format printing for posters, banners, and signage with vibrant colors and durable materials.</p>
                
                <div class="service-pricing">
                    <h4>Pricing</h4>
                    <table class="pricing-table">
                        <tr>
                            <th>Size</th>
                            <th>Material</th>
                            <th>Price</th>
                        </tr>
                        <tr>
                            <td>A2 (420x594mm)</td>
                            <td>Standard Paper</td>
                            <td>Rs. 500</td>
                        </tr>
                        <tr>
                            <td>A1 (594x841mm)</td>
                            <td>Standard Paper</td>
                            <td>Rs. 800</td>
                        </tr>
                        <tr>
                            <td>A0 (841x1189mm)</td>
                            <td>Vinyl</td>
                            <td>Rs. 1,500</td>
                        </tr>
                    </table>
                </div>
            </div>
            
            <!-- Service 6 -->
            <div class="service-card">
                <div class="service-icon">✉️</div>
                <h3>Stationery & Letterheads</h3>
                <p>Professional stationery sets including letterheads, envelopes, and business forms.</p>
                
                <div class="service-pricing">
                    <h4>Pricing</h4>
                    <table class="pricing-table">
                        <tr>
                            <th>Item</th>
                            <th>Quantity</th>
                            <th>Price</th>
                        </tr>
                        <tr>
                            <td>Letterhead</td>
                            <td>100 sheets</td>
                            <td>Rs. 1,200</td>
                        </tr>
                        <tr>
                            <td>Envelopes</td>
                            <td>100 pieces</td>
                            <td>Rs. 800</td>
                        </tr>
                        <tr>
                            <td>Combo Pack</td>
                            <td>100 each</td>
                            <td>Rs. 1,800</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        
        <div class="cta-section">
            <h2>Ready to Print Your Project?</h2>
            <p>Get started with our easy online ordering system or contact us for custom printing solutions.</p>
            <a href="services" class="cta-button">Place an Order Now</a>
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
