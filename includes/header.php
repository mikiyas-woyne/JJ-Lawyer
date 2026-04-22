<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Character encoding - ensures special characters display correctly -->
    <meta charset="UTF-8">
    
    <!-- Viewport meta tag - makes the site responsive on mobile devices -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- Page title - appears in the browser tab -->
    <title><?php echo isset($pageTitle)? $pageTitle : 'JJ Lawyer'; ?></title>
    
    <!-- Link to our CSS stylesheet -->
    <link rel="stylesheet" href="css/style.css">
    
    <!-- Font Awesome for icons (stars, phone, email, etc.) -->
    <link rel="stylesheet" href="https:
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar">
        <div class="container">
            <!-- Logo/Brand Name - clicking it goes to home page -->
            <a href="index.php" class="logo">
                <i class="fas fa-balance-scale"></i> JJ Lawyer
            </a>
            
            <!-- Navigation Links -->
            <ul class="nav-links">
                <!-- Home link -->
                <li><a href="index.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : ''; ?>">
                    <i class="fas fa-home"></i> Home
                </a></li>
                
                <!-- All Lawyers link -->
                <li><a href="lawyers.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'lawyers.php' ? 'active' : ''; ?>">
                    <i class="fas fa-users"></i> Find Lawyers
                </a></li>
                
                <!-- Add Lawyer link -->
                <li><a href="add-lawyer.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'add-lawyer.php' ? 'active' : ''; ?>">
                    <i class="fas fa-user-plus"></i> Add a Lawyer
                </a></li>
                
                <!-- Admin link -->
                <li><a href="admin/admin.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'admin.php' ? 'active' : ''; ?>">
                    <i class="fas fa-cog"></i> Admin
                </a></li>
            </ul>
            
            <!-- Mobile Menu Button (hamburger icon) -->
            <!-- This only shows on small screens -->
            <button class="mobile-menu-btn" onclick="toggleMobileMenu()">
                <i class="fas fa-bars"></i>
            </button>
        </div>
    </nav>
    
    <!-- Mobile Navigation Menu (hidden by default) -->
    <div class="mobile-nav" id="mobileNav">
        <a href="index.php"><i class="fas fa-home"></i> Home</a>
        <a href="lawyers.php"><i class="fas fa-users"></i> Find Lawyers</a>
        <a href="add-lawyer.php"><i class="fas fa-user-plus"></i> Add Lawyer</a>
        <a href="admin/admin.php"><i class="fas fa-cog"></i> Admin</a>
    </div>
    
    <!-- Main Content Container -->
    <!-- All page content goes inside this container -->
    <main class="main-content">
