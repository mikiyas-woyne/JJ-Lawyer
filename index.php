<?php
include 'includes/database.php';
$pageTitle = 'JJ Lawyer';
include 'includes/header.php';
try {
    
    $sql = "
        SELECT 
            l.*,
            COALESCE(AVG(r.rating), 0) as avg_rating,
            COUNT(r.id) as review_count
        FROM lawyers l
        LEFT JOIN reviews r ON l.id = r.lawyer_id
        WHERE l.status = 1
        GROUP BY l.id
        ORDER BY avg_rating DESC, review_count DESC
        LIMIT 6
    ";
    
    
    $stmt = $pdo->query($sql);
    
    
    $topLawyers = $stmt->fetchAll();
    
} catch (PDOException $e) {
    
    $topLawyers = [];
    echo '<!-- Database error: ' . $e->getMessage() . ' -->';
}




try {
    
    $lawyerCount = $pdo->query("SELECT COUNT(*) FROM lawyers WHERE status = 1")->fetchColumn();
    
    
    $reviewCount = $pdo->query("SELECT COUNT(*) FROM reviews")->fetchColumn();
    
    
    $specCount = $pdo->query("SELECT COUNT(DISTINCT specialization) FROM lawyers WHERE status = 1")->fetchColumn();
    
} catch (PDOException $e) {
    $lawyerCount = 0;
    $reviewCount = 0;
    $specCount = 0;
}
?>

<!-- =====================================================
     HERO SECTION
     This is the big banner at the top of the homepage
     ===================================================== -->
<section class="hero">
    <div class="container">
        <h1>የሚስማማዎትን ጠበቃ የምታገኙበት ቦታ</h1>
        <p>Browse top-rated attorneys, read reviews, and connect with legal professionals in your area.</p>
        
        <!-- Search Box -->
        <form action="lawyers.php" method="GET" class="search-box">
            <input 
                type="text" 
                name="search" 
                placeholder="Search by name or specialization..."
                aria-label="Search lawyers"
            >
            <button type="submit">
                <i class="fas fa-search"></i> Search
            </button>
        </form>
    </div>
</section>

<!-- =====================================================
     STATISTICS SECTION
     Shows key numbers about the platform
     ===================================================== -->
<section class="stats-section">
    <div class="container">
        <div class="stats-grid">
            <div class="stat-item">
                <h3><?php echo $lawyerCount; ?></h3>
                <p>Verified Lawyers</p>
            </div>
            <div class="stat-item">
                <h3><?php echo $reviewCount; ?></h3>
                <p>Client Reviews</p>
            </div>
            <div class="stat-item">
                <h3><?php echo $specCount; ?></h3>
                <p>Specializations</p>
            </div>
            <div class="stat-item">
                <h3>24/7</h3>
                <p>Available Support</p>
            </div>
        </div>
    </div>
</section>

<!-- =====================================================
     FEATURED LAWYERS SECTION
     Shows the top-rated lawyers
     ===================================================== -->
<section class="section">
    <div class="container">
        <div class="section-title">
            <h2>Top-Rated Lawyers</h2>
            <p>Meet our highest-rated legal professionals based on client reviews</p>
        </div>
        
        <?php if (count($topLawyers) > 0): ?>
            <!-- Lawyer Cards Grid -->
            <div class="lawyers-grid">
                <?php foreach ($topLawyers as $lawyer): ?>
                    <div class="lawyer-card">
                        <!-- Lawyer Photo -->
                        <img 
                            src="images/<?php echo htmlspecialchars($lawyer['photo']); ?>" 
                            alt="<?php echo htmlspecialchars($lawyer['name']); ?>"
                            class="lawyer-photo"
                            onerror="this.src='images/default-avatar.jpg'"
                        >
                        
                        <!-- Lawyer Information -->
                        <div class="lawyer-info">
                            <h3><?php echo htmlspecialchars($lawyer['name']); ?></h3>
                            
                            <div class="lawyer-specialization">
                                <i class="fas fa-briefcase"></i>
                                <?php echo htmlspecialchars($lawyer['specialization']); ?>
                            </div>
                            
                            <div class="lawyer-meta">
                                <span>
                                    <i class="fas fa-clock"></i>
                                    <?php echo $lawyer['experience']; ?> years exp.
                                </span>
                                <span>
                                    <i class="fas fa-comment"></i>
                                    <?php echo $lawyer['review_count']; ?> reviews
                                </span>
                            </div>
                            
                            <!-- Star Rating Display -->
                            <div class="rating" style="margin-bottom: 15px;">
                                <?php 
                                
                                $rating = round($lawyer['avg_rating']);
                                for ($i = 1; $i <= 5; $i++):
                                    if ($i <= $rating):
                                ?>
                                    <i class="fas fa-star star filled"></i>
                                <?php else: ?>
                                    <i class="fas fa-star star"></i>
                                <?php 
                                    endif;
                                endfor; 
                                ?>
                                <span class="rating-number">
                                    <?php echo number_format($lawyer['avg_rating'], 1); ?>/5
                                </span>
                            </div>
                            
                            <!-- View Profile Button -->
                            <a href="lawyer-profile.php?id=<?php echo $lawyer['id']; ?>" class="btn-view-profile">
                                View Profile <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <!-- View All Button -->
            <div class="text-center mt-20">
                <a href="lawyers.php" class="btn btn-primary">
                    View All Lawyers <i class="fas fa-arrow-right"></i>
                </a>
            </div>
            
        <?php else: ?>
            <!-- No lawyers found message -->
            <div class="alert alert-info text-center">
                <i class="fas fa-info-circle"></i>
                No lawyers found in the database. 
                <a href="add-lawyer.php">Add a lawyer</a> to get started!
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- =====================================================
     CALL TO ACTION SECTION
     Encourages lawyers to join the platform
     ===================================================== -->
<section class="section" style="background-color: var(--bg-white);">
    <div class="container">
        <div class="text-center" style="padding: 40px 0;">
            <h2 style="margin-bottom: 15px;">Are You a Lawyer?</h2>
            <p style="font-size: 1.1rem; color: var(--text-light); margin-bottom: 25px;">
                Join our platform and connect with potential clients in your area.
            </p>
            <a href="add-lawyer.php" class="btn btn-primary" style="font-size: 1.1rem; padding: 15px 40px;">
                <i class="fas fa-user-plus"></i> Add a Lawyer
            </a>
        </div>
    </div>
</section>

<?php
include 'includes/footer.php';
?>

<?php




























?>
