<?php
include 'includes/database.php';
include 'includes/header.php';
$lawyerId = isset($_GET['id']) ? intval($_GET['id']) : 0;


if ($lawyerId === 0) {
    echo '<div class="container"><div class="alert alert-error">Invalid lawyer ID.</div></div>';
    include 'includes/footer.php';
    exit;
}

try {
    
    $sql = "
        SELECT 
            l.*,
            COALESCE(AVG(r.rating), 0) as avg_rating,
            COUNT(r.id) as review_count
        FROM lawyers l
        LEFT JOIN reviews r ON l.id = r.lawyer_id
        WHERE l.id = :id AND l.status = 1
        GROUP BY l.id
    ";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['id' => $lawyerId]);
    $lawyer = $stmt->fetch();
    
    
    if (!$lawyer) {
        echo '<div class="container"><div class="alert alert-error">Lawyer not found.</div></div>';
        include 'includes/footer.php';
        exit;
    }
    
} catch (PDOException $e) {
    echo '<div class="container"><div class="alert alert-error">Error loading profile.</div></div>';
    include 'includes/footer.php';
    exit;
}
try {
    $sql = "
        SELECT * FROM reviews 
        WHERE lawyer_id = :lawyer_id 
        ORDER BY created_at DESC
    ";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['lawyer_id' => $lawyerId]);
    $reviews = $stmt->fetchAll();
    
} catch (PDOException $e) {
    $reviews = [];
}


$pageTitle = htmlspecialchars($lawyer['name']) . ' - Lawyer Profile';
?>

<!-- =====================================================
     LAWYER PROFILE HEADER
     ===================================================== -->
<section class="section">
    <div class="container">
        <div class="profile-container">
            
            <!-- Profile Card -->
            <div class="profile-header">
                <!-- Profile Photo -->
                <img 
                    src="images/<?php echo htmlspecialchars($lawyer['photo']); ?>" 
                    alt="<?php echo htmlspecialchars($lawyer['name']); ?>"
                    class="profile-photo"
                    onerror="this.src='images/default-avatar.jpg'"
                >
                
                <!-- Profile Info -->
                <div class="profile-info">
                    <h1><?php echo htmlspecialchars($lawyer['name']); ?></h1>
                    
                    <p class="specialization">
                        <i class="fas fa-briefcase"></i>
                        <?php echo htmlspecialchars($lawyer['specialization']); ?>
                    </p>
                    
                    <p class="experience">
                        <i class="fas fa-clock"></i>
                        <?php echo $lawyer['experience']; ?> years of experience
                    </p>
                    
                    <!-- Overall Rating -->
                    <div class="rating" style="margin-bottom: 15px;">
                        <?php 
                        $avgRating = round($lawyer['avg_rating']);
                        for ($i = 1; $i <= 5; $i++):
                            if ($i <= $avgRating):
                        ?>
                            <i class="fas fa-star star filled" style="font-size: 1.3rem;"></i>
                        <?php else: ?>
                            <i class="fas fa-star star" style="font-size: 1.3rem;"></i>
                        <?php 
                            endif;
                        endfor; 
                        ?>
                        <span class="rating-number" style="font-size: 1.2rem;">
                            <?php echo number_format($lawyer['avg_rating'], 1); ?>/5 
                            (<?php echo $lawyer['review_count']; ?> reviews)
                        </span>
                    </div>
                    
                    <!-- Contact Information -->
                    <div class="contact-info">
                        <p>
                            <i class="fas fa-phone"></i>
                            <?php echo htmlspecialchars($lawyer['phone']); ?>
                        </p>
                        <p>
                            <i class="fas fa-envelope"></i>
                            <?php echo htmlspecialchars($lawyer['email']); ?>
                        </p>
                    </div>
                </div>
            </div>
            
            <!-- Reviews Section -->
            <div class="reviews-section">
                <h2>
                    <i class="fas fa-comments"></i>
                    Client Reviews
                </h2>
                
                <?php if (count($reviews) > 0): ?>
                    <!-- Display Reviews -->
                    <?php foreach ($reviews as $review): ?>
                        <div class="review">
                            <div class="review-header">
                                <span class="review-author">
                                    <i class="fas fa-user-circle"></i>
                                    <?php echo htmlspecialchars($review['user_name']); ?>
                                </span>
                                <span class="review-date">
                                    <?php echo date('M d, Y', strtotime($review['created_at'])); ?>
                                </span>
                            </div>
                            
                            <!-- Review Rating Stars -->
                            <div class="review-rating">
                                <?php 
                                for ($i = 1; $i <= 5; $i++):
                                    if ($i <= $review['rating']):
                                ?>
                                    <i class="fas fa-star star filled"></i>
                                <?php else: ?>
                                    <i class="fas fa-star star"></i>
                                <?php 
                                    endif;
                                endfor; 
                                ?>
                            </div>
                            
                            <!-- Review Comment -->
                            <p class="review-text">
                                <?php echo nl2br(htmlspecialchars($review['comment'])); ?>
                            </p>
                        </div>
                    <?php endforeach; ?>
                    
                <?php else: ?>
                    <!-- No Reviews Message -->
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        No reviews yet. Be the first to review this lawyer!
                    </div>
                <?php endif; ?>
                
                <!-- Submit Review Form -->
                <div class="review-form">
                    <h3><i class="fas fa-pen"></i> Write a Review</h3>
                    
                    <form action="submit-review.php" method="POST" class="validate-form">
                        <!-- Hidden field to pass lawyer ID -->
                        <input type="hidden" name="lawyer_id" value="<?php echo $lawyerId; ?>">
                        
                        <!-- Your Name -->
                        <div class="form-group">
                            <label for="user_name">
                                <i class="fas fa-user"></i> Your Name
                            </label>
                            <input 
                                type="text" 
                                id="user_name" 
                                name="user_name" 
                                class="form-control" 
                                placeholder="Enter your name"
                                required
                            >
                        </div>
                        
                        <!-- Star Rating -->
                        <div class="form-group">
                            <label>Your Rating</label>
                            <div class="star-rating-input">
                                <!-- Radio buttons for 1-5 stars -->
                                <!-- Note: reversed order for CSS styling -->
                                <input type="radio" id="star5" name="rating" value="5" required>
                                <label for="star5"><i class="fas fa-star"></i></label>
                                
                                <input type="radio" id="star4" name="rating" value="4">
                                <label for="star4"><i class="fas fa-star"></i></label>
                                
                                <input type="radio" id="star3" name="rating" value="3">
                                <label for="star3"><i class="fas fa-star"></i></label>
                                
                                <input type="radio" id="star2" name="rating" value="2">
                                <label for="star2"><i class="fas fa-star"></i></label>
                                
                                <input type="radio" id="star1" name="rating" value="1">
                                <label for="star1"><i class="fas fa-star"></i></label>
                            </div>
                        </div>
                        
                        <!-- Review Comment -->
                        <div class="form-group">
                            <label for="comment">
                                <i class="fas fa-comment"></i> Your Review
                            </label>
                            <textarea 
                                id="comment" 
                                name="comment" 
                                class="form-control" 
                                placeholder="Share your experience with this lawyer..."
                                required
                            ></textarea>
                        </div>
                        
                        <!-- Submit Button -->
                        <button type="submit" class="btn btn-primary btn-block">
                            <i class="fas fa-paper-plane"></i> Submit Review
                        </button>
                    </form>
                </div>
            </div>
            
        </div>
    </div>
</section>

<?php

include 'includes/footer.php';
?>
