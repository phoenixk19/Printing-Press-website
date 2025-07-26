<?php
session_start();
require '../includes/db.php';

// Redirect if not admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../signin.php");
    exit();
}

// Initialize variables
$currentTab = $_GET['tab'] ?? 'dashboard';

// Handle order deletion
if (isset($_GET['delete_order'])) {
    $orderId = (int)$_GET['delete_order'];
    $conn->query("DELETE FROM orders WHERE order_id = $orderId");
    header("Location: admin_panel.php?tab=orders");
    exit();
}

// Handle user deletion
if (isset($_GET['delete_user'])) {
    $userId = (int)$_GET['delete_user'];
    $conn->query("DELETE FROM users WHERE id = $userId");
    header("Location: admin_panel.php?tab=users");
    exit();
}

// Handle testimonial approval/deletion
if (isset($_GET['approve_testimonial'])) {
    $testId = (int)$_GET['approve_testimonial'];
    $conn->query("UPDATE testimonials SET approved = 1 WHERE testimonial_id = $testId");
    header("Location: admin_panel.php?tab=testimonials");
    exit();
}

if (isset($_GET['delete_testimonial'])) {
    $testId = (int)$_GET['delete_testimonial'];
    $conn->query("DELETE FROM testimonials WHERE testimonial_id = $testId");
    header("Location: admin_panel.php?tab=testimonials");
    exit();
}

// Handle news 
if ($_SERVER['REQUEST_METHOD'] == 'POST' && $currentTab == 'news') {
    if (isset($_POST['add_news'])) {
        $title = $conn->real_escape_string($_POST['title']);
        $content = $conn->real_escape_string($_POST['content']);
        $date = $conn->real_escape_string($_POST['date']);
        $image = "../assets/news" . rand(1, 6) . ".jpg"; // Placeholder for demo
        
        $conn->query("INSERT INTO news (title, content, image, date) 
                     VALUES ('$title', '$content', '$image', '$date')");
    }
    elseif (isset($_POST['update_news'])) {
        $id = (int)$_POST['id'];
        $title = $conn->real_escape_string($_POST['title']);
        $content = $conn->real_escape_string($_POST['content']);
        $date = $conn->real_escape_string($_POST['date']);
        
        $conn->query("UPDATE news SET title='$title', content='$content', date='$date' 
                     WHERE news_id = $id");
    }
    elseif (isset($_GET['delete_news'])) {
        $newsId = (int)$_GET['delete_news'];
        $conn->query("DELETE FROM news WHERE news_id = $newsId");
    }
    
    header("Location: admin_panel.php?tab=news");
    exit();
}

// get data for each tab
$orders = $conn->query("SELECT * FROM orders ORDER BY order_date DESC");
$users = $conn->query("SELECT * FROM users WHERE role = 'customer'");
$testimonials = $conn->query("SELECT * FROM testimonials");
$news = $conn->query("SELECT * FROM news ORDER BY date DESC");

// Stats for dashboard
$totalOrders = $conn->query("SELECT COUNT(*) FROM orders")->fetch_row()[0];
$totalUsers = $conn->query("SELECT COUNT(*) FROM users WHERE role = 'customer'")->fetch_row()[0];
$pendingOrders = $conn->query("SELECT COUNT(*) FROM orders WHERE status = 'pending'")->fetch_row()[0];
$pendingTestimonials = $conn->query("SELECT COUNT(*) FROM testimonials WHERE approved = 0")->fetch_row()[0];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - DPP Printing</title>
    <style>
        :root {
            --primary: #0066ff;
            --primary-dark: #0052cc;
            --secondary: #f8f9fa;
            --danger: #dc3545;
            --danger-dark: #c82333;
            --success: #28a745;
            --success-dark: #218838;
            --warning: #ffc107;
            --warning-dark: #e0a800;
            --dark: #343a40;
            --light: #f8f9fa;
            --gray: #6c757d;
            --border: #dee2e6;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background-color: #f5f7fa;
            color: #333;
            display: flex;
            min-height: 100vh;
        }
        
        /* Sidebar */
        .sidebar {
            width: 250px;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white;
            height: 100vh;
            position: fixed;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
            z-index: 100;
        }
        
        .logo-area {
            padding: 20px 15px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            display: flex;
            align-items: center;
        }
        
        .logo-area img {
            height: 40px;
            margin-right: 10px;
        }
        
        .logo-area h2 {
            font-size: 1.3rem;
            font-weight: 600;
        }
        
        .admin-info {
            padding: 20px 15px;
            text-align: center;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        
        .admin-info .avatar {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: white;
            margin: 0 auto 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            color: var(--primary);
            font-weight: bold;
        }
        
        .admin-info h3 {
            font-size: 1.1rem;
            margin-bottom: 5px;
        }
        
        .admin-info p {
            font-size: 0.9rem;
            opacity: 0.8;
        }
        
        .nav-links {
            padding: 15px 0;
        }
        
        .nav-links li {
            list-style: none;
        }
        
        .nav-links a {
            display: flex;
            align-items: center;
            padding: 12px 20px;
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            transition: all 0.3s;
            border-left: 3px solid transparent;
        }
        
        .nav-links a:hover, 
        .nav-links a.active {
            background: rgba(255,255,255,0.1);
            color: white;
            border-left: 3px solid white;
        }
        
        .nav-links a i {
            margin-right: 10px;
            font-size: 1.2rem;
            width: 24px;
            text-align: center;
        }
        
        /* Main Content */
        .main-content {
            flex: 1;
            margin-left: 250px;
        }
        
        .top-bar {
            background: white;
            padding: 15px 25px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
            position: sticky;
            top: 0;
            z-index: 99;
        }
        
        .search-box {
            display: flex;
            align-items: center;
            background: var(--secondary);
            border-radius: 4px;
            padding: 8px 15px;
            width: 300px;
        }
        
        .search-box input {
            border: none;
            background: transparent;
            outline: none;
            padding: 5px;
            width: 100%;
        }
        
        .user-actions {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .notifications {
            position: relative;
        }
        
        .notification-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background: var(--danger);
            color: white;
            border-radius: 50%;
            width: 18px;
            height: 18px;
            font-size: 0.7rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .logout-btn {
            background: var(--danger);
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 4px;
            cursor: pointer;
            transition: background 0.3s;
        }
        
        .logout-btn:hover {
            background: var(--danger-dark);
        }
        
        /* Content Area */
        .content {
            padding: 25px;
        }
        
        .page-title {
            font-size: 1.8rem;
            margin-bottom: 20px;
            color: var(--dark);
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        /* Dashboard Stats */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .stat-card {
            background: white;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            display: flex;
            align-items: center;
        }
        
        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            margin-right: 15px;
        }
        
        .orders-icon { background: rgba(0,102,255,0.1); color: var(--primary); }
        .users-icon { background: rgba(40,167,69,0.1); color: var(--success); }
        .pending-icon { background: rgba(255,193,7,0.1); color: var(--warning); }
        .testimonials-icon { background: rgba(220,53,69,0.1); color: var(--danger); }
        
        .stat-info h3 {
            font-size: 1.8rem;
            font-weight: 600;
            margin-bottom: 5px;
        }
        
        .stat-info p {
            color: var(--gray);
            font-size: 0.9rem;
        }
        
        /* Tables */
        .table-container {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            overflow: hidden;
            margin-bottom: 30px;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
        }
        
        thead {
            background: var(--secondary);
        }
        
        th {
            padding: 15px 20px;
            text-align: left;
            font-weight: 600;
            color: var(--dark);
            border-bottom: 1px solid var(--border);
        }
        
        td {
            padding: 12px 20px;
            border-bottom: 1px solid var(--border);
        }
        
        tr:last-child td {
            border-bottom: none;
        }
        
        tr:hover td {
            background: rgba(0,102,255,0.02);
        }
        
        .status-badge {
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.85rem;
            display: inline-block;
        }
        
        .status-pending { background: rgba(255,193,7,0.2); color: #856404; }
        .status-processing { background: rgba(0,123,255,0.2); color: #004085; }
        .status-completed { background: rgba(40,167,69,0.2); color: #155724; }
        
        .action-btn {
            padding: 6px 12px;
            border-radius: 4px;
            border: none;
            cursor: pointer;
            font-size: 0.85rem;
            margin-right: 5px;
        }
        
        .view-btn { background: rgba(0,102,255,0.1); color: var(--primary); }
        .delete-btn { background: rgba(220,53,69,0.1); color: var(--danger); }
        .approve-btn { background: rgba(40,167,69,0.1); color: var(--success); }
        
        .action-btn:hover {
            opacity: 0.8;
        }
        
        /* Forms */
        .form-container {
            background: white;
            border-radius: 8px;
            padding: 25px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            margin-bottom: 30px;
        }
        
        .form-row {
            display: flex;
            gap: 20px;
            margin-bottom: 20px;
        }
        
        .form-group {
            flex: 1;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: var(--dark);
        }
        
        .form-control {
            width: 100%;
            padding: 10px 15px;
            border: 1px solid var(--border);
            border-radius: 4px;
            font-size: 1rem;
        }
        
        textarea.form-control {
            min-height: 120px;
            resize: vertical;
        }
        
        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 1rem;
            font-weight: 500;
            transition: all 0.3s;
        }
        
        .btn-primary {
            background: var(--primary);
            color: white;
        }
        
        .btn-primary:hover {
            background: var(--primary-dark);
        }
        
        .btn-secondary {
            background: var(--gray);
            color: white;
        }
        
        .btn-secondary:hover {
            background: #5a6268;
        }
        
        /* News Cards */
        .news-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 25px;
            margin-top: 20px;
        }
        
        .news-card {
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 3px 10px rgba(0,0,0,0.08);
            transition: transform 0.3s;
        }
        
        .news-card:hover {
            transform: translateY(-5px);
        }
        
        .news-image {
            height: 180px;
            background-size: cover;
            background-position: center;
        }
        
        .news-content {
            padding: 20px;
        }
        
        .news-date {
            font-size: 0.85rem;
            color: var(--gray);
            margin-bottom: 8px;
        }
        
        .news-title {
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 10px;
        }
        
        .news-actions {
            display: flex;
            gap: 10px;
            margin-top: 15px;
        }
        
        /* Tab Content */
        .tab-content {
            display: none;
        }
        
        .tab-content.active {
            display: block;
        }
        
        /* Responsive */
        @media (max-width: 992px) {
            .sidebar {
                width: 70px;
            }
            
            .logo-area h2, .admin-info, .nav-links span {
                display: none;
            }
            
            .main-content {
                margin-left: 70px;
            }
            
            .nav-links a {
                justify-content: center;
                padding: 15px 0;
            }
            
            .nav-links a i {
                margin-right: 0;
                font-size: 1.4rem;
            }
        }
        
        @media (max-width: 768px) {
            .search-box {
                width: 200px;
            }
            
            .form-row {
                flex-direction: column;
                gap: 15px;
            }
        }
        
        @media (max-width: 576px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }
            
            .top-bar {
                flex-direction: column;
                gap: 15px;
                align-items: flex-start;
            }
            
            .search-box {
                width: 100%;
            }
        }
    </style>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="logo-area">
            <img src="../logodpp.png" alt="DPP Printing">
            <h2>Admin Panel</h2>
        </div>
        
        <div class="admin-info">
            <div class="avatar"><?= substr($_SESSION['name'], 0, 1) ?></div>
            <h3><?= htmlspecialchars($_SESSION['name']) ?></h3>
            <p>Administrator</p>
        </div>
        
        <ul class="nav-links">
            <li>
                <a href="?tab=dashboard" class="<?= $currentTab === 'dashboard' ? 'active' : '' ?>">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li>
                <a href="?tab=orders" class="<?= $currentTab === 'orders' ? 'active' : '' ?>">
                    <i class="fas fa-shopping-cart"></i>
                    <span>Orders</span>
                </a>
            </li>
            <li>
                <a href="?tab=users" class="<?= $currentTab === 'users' ? 'active' : '' ?>">
                    <i class="fas fa-users"></i>
                    <span>Users</span>
                </a>
            </li>
            <li>
                <a href="?tab=testimonials" class="<?= $currentTab === 'testimonials' ? 'active' : '' ?>">
                    <i class="fas fa-comment-alt"></i>
                    <span>Testimonials</span>
                </a>
            </li>
            <li>
                <a href="?tab=news" class="<?= $currentTab === 'news' ? 'active' : '' ?>">
                    <i class="fas fa-newspaper"></i>
                    <span>News</span>
                </a>
            </li>
        </ul>
    </div>
    
    <!-- Main Content -->
    <div class="main-content">
        <!-- Top Bar -->
        <div class="top-bar">
            <div class="search-box">
                <i class="fas fa-search"></i>
                <input type="text" placeholder="Search...">
            </div>
            
            <div class="user-actions">
                <div class="notifications">
                    <i class="fas fa-bell"></i>
                    <span class="notification-badge">3</span>
                </div>
                <button class="logout-btn" onclick="window.location.href='../logout.php'">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </button>
            </div>
        </div>
        
        <!-- Content -->
        <div class="content">
            <!-- Dashboard Tab -->
            <div class="tab-content <?= $currentTab === 'dashboard' ? 'active' : '' ?>" id="dashboard">
                <h1 class="page-title"><i class="fas fa-tachometer-alt"></i> Dashboard</h1>
                
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-icon orders-icon">
                            <i class="fas fa-shopping-cart"></i>
                        </div>
                        <div class="stat-info">
                            <h3><?= $totalOrders ?></h3>
                            <p>Total Orders</p>
                        </div>
                    </div>
                    
                    <div class="stat-card">
                        <div class="stat-icon users-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="stat-info">
                            <h3><?= $totalUsers ?></h3>
                            <p>Registered Users</p>
                        </div>
                    </div>
                    
                    <div class="stat-card">
                        <div class="stat-icon pending-icon">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div class="stat-info">
                            <h3><?= $pendingOrders ?></h3>
                            <p>Pending Orders</p>
                        </div>
                    </div>
                    
                    <div class="stat-card">
                        <div class="stat-icon testimonials-icon">
                            <i class="fas fa-comment-alt"></i>
                        </div>
                        <div class="stat-info">
                            <h3><?= $pendingTestimonials ?></h3>
                            <p>Pending Testimonials</p>
                        </div>
                    </div>
                </div>
                
                <h2 class="page-title"><i class="fas fa-shopping-cart"></i> Recent Orders</h2>
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Customer</th>
                                <th>Document Type</th>
                                <th>Date</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($order = $orders->fetch_assoc()): ?>
                            <tr>
                                <td>#<?= $order['order_id'] ?></td>
                                <td>User <?= $order['user_id'] ?></td>
                                <td><?= $order['document_type'] ?></td>
                                <td><?= date('M d, Y', strtotime($order['order_date'])) ?></td>
                                <td>
                                    <span class="status-badge status-pending">Pending</span>
                                </td>
                                <td>
                                    <button class="action-btn view-btn">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="action-btn delete-btn" 
                                        onclick="if(confirm('Are you sure?')) window.location.href='?tab=orders&delete_order=<?= $order['order_id'] ?>'">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            
            <!-- Orders Tab -->
            <div class="tab-content <?= $currentTab === 'orders' ? 'active' : '' ?>" id="orders">
                <h1 class="page-title"><i class="fas fa-shopping-cart"></i> Manage Orders</h1>
                
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Customer</th>
                                <th>Document Type</th>
                                <th>Paper Size</th>
                                <th>Quantity</th>
                                <th>Date</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $orders = $conn->query("SELECT * FROM orders ORDER BY order_date DESC");
                            while($order = $orders->fetch_assoc()): 
                            ?>
                            <tr>
                                <td>#<?= $order['order_id'] ?></td>
                                <td>User <?= $order['user_id'] ?></td>
                                <td><?= $order['document_type'] ?></td>
                                <td><?= $order['paper_size'] ?></td>
                                <td><?= $order['quantity'] ?></td>
                                <td><?= date('M d, Y', strtotime($order['order_date'])) ?></td>
                                <td>
                                    <span class="status-badge status-pending">Pending</span>
                                </td>
                                <td>
                                    <button class="action-btn view-btn">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="action-btn delete-btn" 
                                        onclick="if(confirm('Are you sure?')) window.location.href='?tab=orders&delete_order=<?= $order['order_id'] ?>'">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            
            <!-- Users Tab -->
            <div class="tab-content <?= $currentTab === 'users' ? 'active' : '' ?>" id="users">
                <h1 class="page-title"><i class="fas fa-users"></i> Manage Users</h1>
                
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>User ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Registration Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($user = $users->fetch_assoc()): ?>
                            <tr>
                                <td>#<?= $user['id'] ?></td>
                                <td><?= $user['name'] ?></td>
                                <td><?= $user['email'] ?></td>
                                <td><?= $user['phone'] ?></td>
                                <td><?= date('M d, Y', strtotime($user['created_at'])) ?></td>
                                <td>
                                    <button class="action-btn view-btn">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="action-btn delete-btn" 
                                        onclick="if(confirm('Are you sure?')) window.location.href='?tab=users&delete_user=<?= $user['id'] ?>'">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            
            <!-- Testimonials Tab -->
            <div class="tab-content <?= $currentTab === 'testimonials' ? 'active' : '' ?>" id="testimonials">
                <h1 class="page-title"><i class="fas fa-comment-alt"></i> Manage Testimonials</h1>
                
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Designation</th>
                                <th>Content</th>
                                <th>Date</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($testimonial = $testimonials->fetch_assoc()): ?>
                            <tr>
                                <td>#<?= $testimonial['testimonial_id'] ?></td>
                                <td><?= $testimonial['name'] ?></td>
                                <td><?= $testimonial['designation'] ?></td>
                                <td><?= substr($testimonial['content'], 0, 50) ?>...</td>
                                <td><?= date('M d, Y', strtotime($testimonial['created_at'])) ?></td>
                                <td>
                                    <?php if($testimonial['approved']): ?>
                                    <span class="status-badge status-completed">Approved</span>
                                    <?php else: ?>
                                    <span class="status-badge status-pending">Pending</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if(!$testimonial['approved']): ?>
                                    <button class="action-btn approve-btn" 
                                        onclick="window.location.href='?tab=testimonials&approve_testimonial=<?= $testimonial['testimonial_id'] ?>'">
                                        <i class="fas fa-check"></i>
                                    </button>
                                    <?php endif; ?>
                                    <button class="action-btn delete-btn" 
                                        onclick="if(confirm('Are you sure?')) window.location.href='?tab=testimonials&delete_testimonial=<?= $testimonial['testimonial_id'] ?>'">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            
            <!-- News Tab -->
            <div class="tab-content <?= $currentTab === 'news' ? 'active' : '' ?>" id="news">
                <h1 class="page-title"><i class="fas fa-newspaper"></i> Manage News</h1>
                
                <div class="form-container">
                    <h2><i class="fas fa-plus-circle"></i> Add New News Item</h2>
                    <form method="POST">
                        <div class="form-row">
                            <div class="form-group">
                                <label for="title">Title</label>
                                <input type="text" class="form-control" id="title" name="title" required>
                            </div>
                            <div class="form-group">
                                <label for="date">Date</label>
                                <input type="date" class="form-control" id="date" name="date" required>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="content">Content</label>
                            <textarea class="form-control" id="content" name="content" required></textarea>
                        </div>
                        
                        <button type="submit" class="btn btn-primary" name="add_news">
                            <i class="fas fa-plus"></i> Add News
                        </button>
                    </form>
                </div>
                
                <h2 class="page-title"><i class="fas fa-list"></i> Existing News</h2>
                <div class="news-grid">
                    <?php while($newsItem = $news->fetch_assoc()): ?>
                    <div class="news-card">
                        <div class="news-image" style="background-image: url('<?= $newsItem['image'] ?>')"></div>
                        <div class="news-content">
                            <div class="news-date"><?= date('F d, Y', strtotime($newsItem['date'])) ?></div>
                            <div class="news-title"><?= $newsItem['title'] ?></div>
                            <p><?= substr($newsItem['content'], 0, 100) ?>...</p>
                            
                            <div class="news-actions">
                                <button class="action-btn view-btn">
                                    <i class="fas fa-edit"></i> Edit
                                </button>
                                <button class="action-btn delete-btn" 
                                    onclick="if(confirm('Are you sure?')) window.location.href='?tab=news&delete_news=<?= $newsItem['news_id'] ?>'">
                                    <i class="fas fa-trash"></i> Delete
                                </button>
                            </div>
                        </div>
                    </div>
                    <?php endwhile; ?>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        // Set current date for news form
        document.addEventListener('DOMContentLoaded', function() {
            const today = new Date().toISOString().split('T')[0];
            document.getElementById('date').value = today;
        });
    </script>
</body>
</html>
