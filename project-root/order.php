<?php
session_start();
require 'includes/db.php';

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: signin.php");
    exit();
}

// Handle order submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $userId = $_SESSION['user_id'];
    $docType = trim($_POST['doctype']);
    $paperSize = trim($_POST['size']);
    $quantity = (int)$_POST['quantity'];
    $documentLink = trim($_POST['pdf']);
    $color = trim($_POST['color']);
    $sides = trim($_POST['sides']);
    $paperType = trim($_POST['paper_type']);
    $paperThickness = trim($_POST['paper_thickness']);
    $binding = trim($_POST['binding']);
    $lamination = trim($_POST['lamination']);
    $corners = trim($_POST['corners']);
    $holePunch = isset($_POST['hole_punch']) ? 1 : 0;
    $perforation = isset($_POST['perforation']) ? 1 : 0;
    $foilStamping = isset($_POST['foil_stamping']) ? 1 : 0;
    $notes = trim($_POST['description']);

    // Prepare insert statement with  columns
    $stmt = $conn->prepare("INSERT INTO orders (
        user_id, document_type, paper_size, quantity, document_link, 
        color, sides, paper_type, paper_thickness, binding, 
        lamination, corners, hole_punch, perforation, foil_stamping, 
        notes
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    
    $stmt->bind_param(
        "ississssssssiiis", 
        $userId, $docType, $paperSize, $quantity, $documentLink, 
        $color, $sides, $paperType, $paperThickness, $binding, 
        $lamination, $corners, $holePunch, $perforation, $foilStamping, 
        $notes
    );

    if ($stmt->execute()) {
        $success = "Order placed successfully!";
    } else {
        $error = "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
<?php if (isset($success)): ?>
    <div class="success-message"><?= $success ?></div>
<?php endif; ?>

<?php if (isset($error)): ?>
    <div class="error-message"><?= $error ?></div>
<?php endif; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Printing - DPP Printing</title>
    
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

        .order-container {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 700px;
            padding: 40px;
            margin: 0 auto;
        }
        
        .order-header {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .order-header h1 {
            font-size: 28px;
            color: #333;
            margin-bottom: 10px;
        }

        .order-header p {
            color: #666;
            font-size: 16px;
        }
        
        .order-form .form-group {
            margin-bottom: 25px;
        }
        
        .order-form label {
            display: block;
            margin-bottom: 8px;
            color: #555;
            font-size: 15px;
            font-weight: bold;
        }
        
        .order-form input,
        .order-form select,
        .order-form textarea {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 15px;
            transition: border-color 0.3s;
        }
        
        .order-form input:focus,
        .order-form select:focus,
        .order-form textarea:focus {
            outline: none;
            border-color: #4285f4;
            box-shadow: 0 0 0 2px rgba(66, 133, 244, 0.2);
        }
        
        .order-form textarea {
            min-height: 120px;
            resize: vertical;
        }
        
        .radio-group,
        .checkbox-group {
            display: flex;
            gap: 20px;
            margin-top: 8px;
            flex-wrap: wrap;
        }
        
        .radio-option,
        .checkbox-option {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .radio-option input,
        .checkbox-option input {
            width: auto;
        }
        
        .form-columns {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
        }
        
        .form-column {
            flex: 1;
            min-width: 250px;
        }
        
        .submit-button {
            width: 100%;
            padding: 14px;
            background-color: #4285f4;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 17px;
            font-weight: 600;
            margin-top: 20px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        
        .submit-button:hover {
            background-color: #3367d6;
        }

        /* Section Styling */
        .form-section {
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 1px solid #eee;
        }
        
        .form-section-title {
            font-size: 20px;
            color: #0066ff;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #eaeaea;
        }
        /* Add user info styles */
        .user-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .user-name {
            font-weight: 500;
            color: #333;
        }
        
        .logout-btn {
            padding: 8px 20px;
            border-radius: 5px;
            background-color: #dc3545;
            color: white;
            text-decoration: none;
            font-weight: 500;
            transition: background-color 0.3s;
        }
        
        .logout-btn:hover {
            background-color: #c82333;
        }
        .success-message {
            background-color: #dff0d8;
            color: #3c763d;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            text-align: center;
        }

        .error-message {
            background-color: #f2dede;
            color: #a94442;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            text-align: center;
        }
        /* FOOTER  */
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
                <div class="logo"><img src="logodpp.png" alt="logo"></div>
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
            <div class="user-info">
                <span class="user-name"><?= htmlspecialchars($_SESSION['name']) ?></span>
                <a href="logout.php" class="logout-btn">Logout</a>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main>
        <div class="order-container">
            <div class="order-header">
                <h1>Printing Order Details</h1>
                <p>Complete all sections to place your printing order</p>
            </div>
            
            <form class="order-form" action="#" method="POST">
                <!-- Document Information Section -->
                <div class="form-section">
                    <h3 class="form-section-title">Document Information</h3>
                    
                    <div class="form-group">
                        <label for="doctype">Document Type</label>
                        <input type="text" id="doctype" name="doctype" placeholder="e.g., Contract, Report, Thesis, Brochure" required>
                    </div>
                    
                    <div class="form-columns">
                        <div class="form-column">
                            <div class="form-group">
                                <label for="size">Paper Size</label>
                                <select id="size" name="size" required>
                                    <option value="" disabled selected>Select paper size</option>
                                    <option value="A4">A4 (210 x 297 mm)</option>
                                    <option value="Letter">Letter (8.5 x 11 in)</option>
                                    <option value="Legal">Legal (8.5 x 14 in)</option>
                                    <option value="A3">A3 (297 x 420 mm)</option>
                                    <option value="A5">A5 (148 x 210 mm)</option>
                                    <option value="Other">Custom Size</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="form-column">
                            <div class="form-group">
                                <label for="quantity">Quantity</label>
                                <input type="number" id="quantity" name="quantity" min="1" value="1" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="pdf">Document Link</label>
                        <input type="url" id="pdf" name="pdf" placeholder="https://example.com/your-file.pdf" required>
                        <small>Please provide a direct download link to your file (PDF, DOC, DOCX, JPG, PNG)</small>
                    </div>
                </div>
                
                <!-- Printing Options Section -->
                <div class="form-section">
                    <h3 class="form-section-title">Printing Options</h3>
                    
                    <div class="form-columns">
                        <div class="form-column">
                            <div class="form-group">
                                <label>Color Option</label>
                                <div class="radio-group">
                                    <div class="radio-option">
                                        <input type="radio" id="bw" name="color" value="bw" checked>
                                        <label for="bw">Black & White</label>
                                    </div>
                                    <div class="radio-option">
                                        <input type="radio" id="color" name="color" value="color">
                                        <label for="color">Color</label>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label>Printing Side</label>
                                <div class="radio-group">
                                    <div class="radio-option">
                                        <input type="radio" id="single" name="sides" value="single" checked>
                                        <label for="single">Single-Sided</label>
                                    </div>
                                    <div class="radio-option">
                                        <input type="radio" id="double" name="sides" value="double">
                                        <label for="double">Double-Sided</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-column">
                            <div class="form-group">
                                <label for="paper-type">Paper Type</label>
                                <select id="paper-type" name="paper_type" required>
                                    <option value="" disabled selected>Select paper type</option>
                                    <option value="standard">Standard (80gsm)</option>
                                    <option value="premium">Premium (100gsm)</option>
                                    <option value="glossy">Glossy Photo Paper</option>
                                    <option value="matte">Matte Finish</option>
                                    <option value="recycled">Recycled Paper</option>
                                    <option value="cardstock">Cardstock</option>
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <label for="paper-thickness">Paper Thickness</label>
                                <select id="paper-thickness" name="paper_thickness" required>
                                    <option value="" disabled selected>Select thickness</option>
                                    <option value="thin">Thin (60-80 gsm)</option>
                                    <option value="medium">Medium (90-120 gsm)</option>
                                    <option value="thick">Thick (130-200 gsm)</option>
                                    <option value="card">Card Stock (200+ gsm)</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Finishing Options Section -->
                <div class="form-section">
                    <h3 class="form-section-title">Finishing Options</h3>
                    
                    <div class="form-columns">
                        <div class="form-column">
                            <div class="form-group">
                                <label for="binding">Binding Type</label>
                                <select id="binding" name="binding">
                                    <option value="none" selected>No Binding</option>
                                    <option value="stapled">Stapled (Saddle Stitch)</option>
                                    <option value="spiral">Spiral Binding</option>
                                    <option value="comb">Plastic Comb Binding</option>
                                    <option value="perfect">Perfect Binding</option>
                                    <option value="wire">Wire-O Binding</option>
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <label for="lamination">Lamination</label>
                                <select id="lamination" name="lamination">
                                    <option value="none" selected>No Lamination</option>
                                    <option value="gloss">Gloss Lamination</option>
                                    <option value="matte">Matte Lamination</option>
                                    <option value="soft-touch">Soft Touch Lamination</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="form-column">
                            <div class="form-group">
                                <label>Corner Options</label>
                                <div class="radio-group">
                                    <div class="radio-option">
                                        <input type="radio" id="square" name="corners" value="square" checked>
                                        <label for="square">Square Corners</label>
                                    </div>
                                    <div class="radio-option">
                                        <input type="radio" id="rounded" name="corners" value="rounded">
                                        <label for="rounded">Rounded Corners</label>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label>Additional Options</label>
                                <div class="checkbox-group">
                                    <div class="checkbox-option">
                                        <input type="checkbox" id="hole-punch" name="hole_punch">
                                        <label for="hole-punch">Hole Punch</label>
                                    </div>
                                    <div class="checkbox-option">
                                        <input type="checkbox" id="perforation" name="perforation">
                                        <label for="perforation">Perforation</label>
                                    </div>
                                    <div class="checkbox-option">
                                        <input type="checkbox" id="foil" name="foil_stamping">
                                        <label for="foil">Foil Stamping</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Special Instructions -->
                <div class="form-section">
                    <h3 class="form-section-title">Special Instructions</h3>
                    
                    <div class="form-group">
                        <label for="description">Additional Notes</label>
                        <textarea id="description" name="description" placeholder="Any special requirements or notes..."></textarea>
                    </div>
                </div>
                
                <button type="submit" class="submit-button">Submit Order</button>
            </form>
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
    <script>
    <?php if (isset($success)): ?>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelector('.order-form').reset();
        });
    <?php endif; ?>
    </script>
</body>
</html>
