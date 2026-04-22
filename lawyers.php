<?php
include 'includes/database.php';
$pageTitle = 'Find Lawyers - JJ Lawyer';
include 'includes/header.php';

$search = isset($_GET['search']) ? trim($_GET['search']) : '';

try {
    
    if (!empty($search)) {
        
        $sql = "
            SELECT 
                l.*,
                COALESCE(AVG(r.rating), 0) as avg_rating,
                COUNT(r.id) as review_count
            FROM lawyers l
            LEFT JOIN reviews r ON l.id = r.lawyer_id
            WHERE l.status = 1 
            AND (l.name LIKE :search OR l.specialization LIKE :search)
            GROUP BY l.id
            ORDER BY avg_rating DESC, l.name ASC
        ";
        
        
        $stmt = $pdo->prepare($sql);
        
        
        $searchTerm = "%$search%";
        
        
        $stmt->execute(['search' => $searchTerm]);
        
    } else {
        
        $sql = "
            SELECT 
                l.*,
                COALESCE(AVG(r.rating), 0) as avg_rating,
                COUNT(r.id) as review_count
            FROM lawyers l
            LEFT JOIN reviews r ON l.id = r.lawyer_id
            WHERE l.status = 1
            GROUP BY l.id
            ORDER BY avg_rating DESC, l.name ASC
        ";
        
        
        $stmt = $pdo->query($sql);
    }
    
    
    $lawyers = $stmt->fetchAll();
    
} catch (PDOException $e) {
    
    $lawyers = [];
    $error = "Error fetching lawyers: " . $e->getMessage();
}
?>

<!-- =====================================================
     PAGE HEADER
     ===================================================== -->
<section class="page-header">
    <div class="container">
        <h1>Find a Lawyer</h1>
        <p>Browse our directory of experienced legal professionals</p>
    </div>
</section>

<!-- =====================================================
     SEARCH SECTION
     ===================================================== -->
<section class="section" style="padding-bottom: 20px;">
    <div class="container">
        <div class="form-container" style="max-width: 700px; padding: 25px;">
            <form action="lawyers.php" method="GET" class="search-box" style="border-radius: 8px;">
                <input 
                    type="text" 
                    name="search" 
                    id="lawyerSearch"
                    value="<?php echo htmlspecialchars($search); ?>"
                    placeholder="Search by name or specialization (e.g., Criminal, Family)..."
                    aria-label="Search lawyers"
                >
                <button type="submit" style="border-radius: 0;">
                    <i class="fas fa-search"></i> Search
                </button>
            </form>
            
            <?php if (!empty($search)): ?>
                <p style="margin-top: 15px; text-align: center; color: var(--text-light);">
                    Showing results for: <strong>"<?php echo htmlspecialchars($search); ?>"</strong>
                    <a href="lawyers.php" style="margin-left: 10px; color: var(--primary-color);">
                        <i class="fas fa-times"></i> Clear
                    </a>
                </p>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- =====================================================
     LAWYERS GRID SECTION
     ===================================================== -->
<section class="section" style="padding-top: 20px;">
    <div class="container">
        
        <!-- Results Count -->
        <p style="margin-bottom: 20px; color: var(--text-light);">
            <i class="fas fa-list"></i> 
            Found <?php echo count($lawyers); ?> lawyer<?php echo count($lawyers) !== 1 ? 's' : ''; ?>
        </p>
        
        <?php if (isset($error)): ?>
            <!-- Error Message -->
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle"></i> 
                <?php echo $error; ?>
            </div>
        <?php endif; ?>
        
        <?php if (count($lawyers) > 0): ?>
            <!-- Lawyers Grid -->
            <div class="lawyers-grid">
                <?php foreach ($lawyers as $lawyer): ?>
                    <div class="lawyer-card">
                        <!-- Lawyer Photo -->
                        <img 
                            src="images/<?php echo htmlspecialchars($lawyer['photo']); ?>" 
                            alt="<?php echo htmlspecialchars($lawyer['name']); ?>"
                            class="lawyer-photo"
                            onerror="this.src='images/default-avatar.jpg'"
                        >
                        
                        <!-- Lawyer Info -->
                        <div class="lawyer-info">
                            <h3><?php echo htmlspecialchars($lawyer['name']); ?></h3>
                            
                            <div class="lawyer-specialization">
                                <i class="fas fa-briefcase"></i>
                                <?php echo htmlspecialchars($lawyer['specialization']); ?>
                            </div>
                            
                            <div class="lawyer-meta">
                                <span>
                                    <i class="fas fa-clock"></i>
                                    <?php echo $lawyer['experience']; ?> years
                                </span>
                                <span>
                                    <i class="fas fa-comment"></i>
                                    <?php echo $lawyer['review_count']; ?> reviews
                                </span>
                            </div>
                            
                            <!-- Rating Stars -->
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
                            
                            <!-- Contact Info Preview -->
                            <p style="color: var(--text-light); font-size: 0.9rem; margin-bottom: 15px;">
                                <i class="fas fa-phone"></i> 
                                <?php echo htmlspecialchars($lawyer['phone']); ?>
                            </p>
                            
                            <!-- View Profile Button -->
                            <a href="lawyer-profile.php?id=<?php echo $lawyer['id']; ?>" class="btn-view-profile">
                                View Full Profile <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            
        <?php else: ?>
            <!-- No Results Message -->
            <div class="alert alert-info text-center" style="padding: 40px;">
                <i class="fas fa-search" style="font-size: 3rem; margin-bottom: 15px; display: block;"></i>
                <h3>No lawyers found</h3>
                <p>
                    <?php if (!empty($search)): ?>
                        No lawyers match your search for "<?php echo htmlspecialchars($search); ?>".
                        <br>Try a different search term or <a href="lawyers.php">view all lawyers</a>.
                    <?php else: ?>
                        There are no approved lawyers in the directory yet.
                    <?php endif; ?>
                </p>
                <a href="add-lawyer.php" class="btn btn-primary" style="margin-top: 15px;">
                    <i class="fas fa-user-plus"></i> Add a Lawyer
                </a>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php

include 'includes/footer.php';
?>