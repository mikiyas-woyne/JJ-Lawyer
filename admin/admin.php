<?php
include '../includes/database.php';
session_start();
$adminUser = 'admin';
$adminPass = 'admin123';
$isLoggedIn = isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;


if (!$isLoggedIn && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    
    
    try {
        $stmt = $pdo->prepare("SELECT * FROM admins WHERE username = :username AND password = :password");
        $stmt->execute(['username' => $username, 'password' => $password]);
        
        if ($stmt->fetch()) {
            $_SESSION['admin_logged_in'] = true;
            $isLoggedIn = true;
        } else {
            $loginError = 'Invalid username or password.';
        }
    } catch (PDOException $e) {
        $loginError = 'Login error. Please try again.';
    }
}


if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: admin.php');
    exit;
}
if ($isLoggedIn && $_SERVER['REQUEST_METHOD'] === 'POST') {
    
    
    if (isset($_POST['approve_lawyer'])) {
        $lawyerId = intval($_POST['lawyer_id']);
        
        try {
            $stmt = $pdo->prepare("UPDATE lawyers SET status = 1 WHERE id = :id");
            $stmt->execute(['id' => $lawyerId]);
            
            $successMessage = 'Lawyer approved successfully!';
        } catch (PDOException $e) {
            $errorMessage = 'Error approving lawyer.';
        }
    }
    
    
    if (isset($_POST['delete_lawyer'])) {
        $lawyerId = intval($_POST['lawyer_id']);
        
        try {
            
            $stmt = $pdo->prepare("DELETE FROM lawyers WHERE id = :id");
            $stmt->execute(['id' => $lawyerId]);
            
            $successMessage = 'Lawyer deleted successfully!';
        } catch (PDOException $e) {
            $errorMessage = 'Error deleting lawyer.';
        }
    }
    
    
    if (isset($_POST['delete_review'])) {
        $reviewId = intval($_POST['review_id']);
        
        try {
            $stmt = $pdo->prepare("DELETE FROM reviews WHERE id = :id");
            $stmt->execute(['id' => $reviewId]);
            
            $successMessage = 'Review deleted successfully!';
        } catch (PDOException $e) {
            $errorMessage = 'Error deleting review.';
        }
    }
}

if ($isLoggedIn) {
    
    try {
        $pendingLawyers = $pdo->query("
            SELECT * FROM lawyers 
            WHERE status = 0 
            ORDER BY created_at DESC
        ")->fetchAll();
    } catch (PDOException $e) {
        $pendingLawyers = [];
    }
    
    
    try {
        $approvedCount = $pdo->query("SELECT COUNT(*) FROM lawyers WHERE status = 1")->fetchColumn();
    } catch (PDOException $e) {
        $approvedCount = 0;
    }
    
    
    try {
        $allReviews = $pdo->query("
            SELECT r.*, l.name as lawyer_name 
            FROM reviews r
            JOIN lawyers l ON r.lawyer_id = l.id
            ORDER BY r.created_at DESC
        ")->fetchAll();
    } catch (PDOException $e) {
        $allReviews = [];
    }
}


$pageTitle = 'Admin Panel - LawyerConnect';


include '../includes/header.php';
?>

<!-- =====================================================
     ADMIN PANEL CONTENT
     ===================================================== -->

<?php if (!$isLoggedIn): ?>
    <!-- LOGIN FORM -->
    <section class="section">
        <div class="container">
            <div class="form-container" style="max-width: 400px;">
                <h2 class="text-center"><i class="fas fa-lock"></i> Admin Login</h2>
                <p style="text-align: center; color: var(--text-light); margin-bottom: 25px;">
                    Please enter your credentials to continue.
                </p>
                
                <?php if (isset($loginError)): ?>
                    <div class="alert alert-error">
                        <i class="fas fa-exclamation-circle"></i> 
                        <?php echo $loginError; ?>
                    </div>
                <?php endif; ?>
                
                <form action="admin.php" method="POST">
                    <input type="hidden" name="login" value="1">
                    
                    <div class="form-group">
                        <label for="username">
                            <i class="fas fa-user"></i> Username
                        </label>
                        <input 
                            type="text" 
                            id="username" 
                            name="username" 
                            class="form-control" 
                            required
                            autofocus
                        >
                    </div>
                    
                    <div class="form-group">
                        <label for="password">
                            <i class="fas fa-key"></i> Password
                        </label>
                        <input 
                            type="password" 
                            id="password" 
                            name="password" 
                            class="form-control" 
                            required
                        >
                    </div>
                    
                    <button type="submit" class="btn btn-primary btn-block">
                        <i class="fas fa-sign-in-alt"></i> Login
                    </button>
                </form>
                
                <div class="alert alert-info" style="margin-top: 20px;">
                    <strong>Default Login:</strong><br>
                    Username: <code>admin</code><br>
                    Password: <code>admin123</code>
                </div>
            </div>
        </div>
    </section>

<?php else: ?>
    <!-- ADMIN DASHBOARD -->
    <section class="page-header">
        <div class="container">
            <h1><i class="fas fa-cog"></i> Admin Panel</h1>
            <p>Manage lawyers, reviews, and platform settings</p>
        </div>
    </section>
    
    <section class="section">
        <div class="container">
            
            <!-- Success/Error Messages -->
            <?php if (isset($successMessage)): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i> 
                    <?php echo $successMessage; ?>
                </div>
            <?php endif; ?>
            
            <?php if (isset($errorMessage)): ?>
                <div class="alert alert-error">
                    <i class="fas fa-exclamation-circle"></i> 
                    <?php echo $errorMessage; ?>
                </div>
            <?php endif; ?>
            
            <!-- Admin Actions Bar -->
            <div style="margin-bottom: 30px; display: flex; justify-content: space-between; align-items: center;">
                <p style="color: var(--text-light);">
                    <i class="fas fa-user-shield"></i> 
                    Logged in as Admin | 
                    <a href="admin.php?logout=1" style="color: var(--danger-color);">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a>
                </p>
            </div>
            
            <!-- PENDING LAWYERS SECTION -->
            <div class="admin-section">
                <h2>
                    <i class="fas fa-user-clock"></i> 
                    Pending Approvals 
                    <span class="badge badge-pending"><?php echo count($pendingLawyers); ?></span>
                </h2>
                
                <?php if (count($pendingLawyers) > 0): ?>
                    <div class="data-table">
                        <table>
                            <thead>
                                <tr>
                                    <th>Photo</th>
                                    <th>Name</th>
                                    <th>Specialization</th>
                                    <th>Experience</th>
                                    <th>Contact</th>
                                    <th>Submitted</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($pendingLawyers as $lawyer): ?>
                                    <tr>
                                        <td>
                                            <img 
                                                src="../images/<?php echo htmlspecialchars($lawyer['photo']); ?>" 
                                                alt="" 
                                                style="width: 50px; height: 50px; border-radius: 50%; object-fit: cover;"
                                                onerror="this.src='../images/default-avatar.jpg'"
                                            >
                                        </td>
                                        <td><?php echo htmlspecialchars($lawyer['name']); ?></td>
                                        <td><?php echo htmlspecialchars($lawyer['specialization']); ?></td>
                                        <td><?php echo $lawyer['experience']; ?> years</td>
                                        <td>
                                            <small>
                                                <?php echo htmlspecialchars($lawyer['phone']); ?><br>
                                                <?php echo htmlspecialchars($lawyer['email']); ?>
                                            </small>
                                        </td>
                                        <td><?php echo date('M d, Y', strtotime($lawyer['created_at'])); ?></td>
                                        <td>
                                            <form action="admin.php" method="POST" style="display: inline;">
                                                <input type="hidden" name="lawyer_id" value="<?php echo $lawyer['id']; ?>">
                                                <button type="submit" name="approve_lawyer" class="btn btn-success btn-sm">
                                                    <i class="fas fa-check"></i> Approve
                                                </button>
                                            </form>
                                            <form action="admin.php" method="POST" style="display: inline;" 
                                                  onsubmit="return confirm('Are you sure you want to delete this lawyer?');">
                                                <input type="hidden" name="lawyer_id" value="<?php echo $lawyer['id']; ?>">
                                                <button type="submit" name="delete_lawyer" class="btn btn-danger btn-sm">
                                                    <i class="fas fa-trash"></i> Delete
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> 
                        No pending lawyers to approve. All caught up!
                    </div>
                <?php endif; ?>
            </div>
            
            <!-- ALL REVIEWS SECTION -->
            <div class="admin-section">
                <h2>
                    <i class="fas fa-comments"></i> 
                    All Reviews 
                    <span class="badge badge-success"><?php echo count($allReviews); ?></span>
                </h2>
                
                <?php if (count($allReviews) > 0): ?>
                    <div class="data-table">
                        <table>
                            <thead>
                                <tr>
                                    <th>Reviewer</th>
                                    <th>Lawyer</th>
                                    <th>Rating</th>
                                    <th>Comment</th>
                                    <th>Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($allReviews as $review): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($review['user_name']); ?></td>
                                        <td><?php echo htmlspecialchars($review['lawyer_name']); ?></td>
                                        <td>
                                            <span class="badge badge-success">
                                                <?php echo $review['rating']; ?>/5 <i class="fas fa-star"></i>
                                            </span>
                                        </td>
                                        <td style="max-width: 300px;">
                                            <small><?php echo htmlspecialchars(substr($review['comment'], 0, 100)); ?>...</small>
                                        </td>
                                        <td><?php echo date('M d, Y', strtotime($review['created_at'])); ?></td>
                                        <td>
                                            <form action="admin.php" method="POST" 
                                                  onsubmit="return confirm('Are you sure you want to delete this review?');">
                                                <input type="hidden" name="review_id" value="<?php echo $review['id']; ?>">
                                                <button type="submit" name="delete_review" class="btn btn-danger btn-sm">
                                                    <i class="fas fa-trash"></i> Delete
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> 
                        No reviews yet.
                    </div>
                <?php endif; ?>
            </div>
            
            <!-- PLATFORM STATS -->
            <div class="admin-section">
                <h2><i class="fas fa-chart-bar"></i> Platform Statistics</h2>
                <div class="stats-grid" style="max-width: 600px;">
                    <div class="stat-item" style="background: var(--bg-white); padding: 20px; border-radius: 12px; box-shadow: var(--shadow-md);">
                        <h3 style="color: var(--success-color);"><?php echo $approvedCount; ?></h3>
                        <p>Approved Lawyers</p>
                    </div>
                    <div class="stat-item" style="background: var(--bg-white); padding: 20px; border-radius: 12px; box-shadow: var(--shadow-md);">
                        <h3 style="color: var(--warning-color);"><?php echo count($pendingLawyers); ?></h3>
                        <p>Pending Approval</p>
                    </div>
                    <div class="stat-item" style="background: var(--bg-white); padding: 20px; border-radius: 12px; box-shadow: var(--shadow-md);">
                        <h3 style="color: var(--primary-color);"><?php echo count($allReviews); ?></h3>
                        <p>Total Reviews</p>
                    </div>
                </div>
            </div>
            
        </div>
    </section>
<?php endif; ?>

<?php

include '../includes/footer.php';
?>

<?php





























?>
