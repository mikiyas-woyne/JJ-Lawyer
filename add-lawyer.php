<?php
include 'includes/database.php';
$pageTitle = 'Add a lawyer - JJLawyer';
include 'includes/header.php';
$success = false;
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $specialization = trim($_POST['specialization'] ?? '');
    $experience = intval($_POST['experience'] ?? 0);
    $phone = trim($_POST['phone'] ?? '');
    $email = trim($_POST['email'] ?? '');
    
    if (empty($name)) {
        $error = 'Please enter your name.';
    } elseif (empty($specialization)) {
        $error = 'Please enter your specialization.';
    } elseif (empty($phone)) {
        $error = 'Please enter your phone number.';
    } elseif (empty($email)) {
        $error = 'Please enter your email address.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } else {
            
        $photoName = 'default-avatar.jpg'; 
        
        if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
                
            $fileTmpPath = $_FILES['photo']['tmp_name'];
            $fileName = $_FILES['photo']['name'];
            $fileSize = $_FILES['photo']['size'];
            $fileType = $_FILES['photo']['type'];
            
            $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
            
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
            
            if (in_array($fileExtension, $allowedExtensions)) {
                    
                if ($fileSize <= 5 * 1024 * 1024) {
                        
                    $newFileName = time() . '_' . uniqid() . '.' . $fileExtension;
                    $uploadPath = 'images/' . $newFileName;
                    if (move_uploaded_file($fileTmpPath, $uploadPath)) {
                        $photoName = $newFileName;
                    } else {
                        $error = 'Error uploading photo. Please try again.';
                    }
                    
                } else {
                    $error = 'Photo must be less than 5MB.';
                }
                
            } else {
                $error = 'Only JPG, PNG, and GIF files are allowed.';
            }
        }
        
        if (empty($error)) {
            try {
                
                
                $sql = "
                    INSERT INTO lawyers (name, photo, specialization, experience, phone, email, status)
                    VALUES (:name, :photo, :specialization, :experience, :phone, :email, 0)
                ";
                
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    'name' => $name,
                    'photo' => $photoName,
                    'specialization' => $specialization,
                    'experience' => $experience,
                    'phone' => $phone,
                    'email' => $email
                ]);
                
                
                $success = true;
                
            } catch (PDOException $e) {
                $error = 'Error saving your profile. Please try again.';
                
                error_log('Database error: ' . $e->getMessage());
            }
        }
    }
}
?>

<!-- =====================================================
     PAGE CONTENT
     ===================================================== -->
<section class="page-header">
    <div class="container">
        <h1>List Your Practice</h1>
        <p>Join our directory and connect with potential clients</p>
    </div>
</section>

<section class="section">
    <div class="container">
        
        <?php if ($success): ?>
            <!-- Success Message -->
            <div class="form-container">
                <div class="alert alert-success text-center" style="margin-bottom: 0;">
                    <i class="fas fa-check-circle" style="font-size: 3rem; margin-bottom: 15px; display: block;"></i>
                    <h2>Thank You!</h2>
                    <p>Your profile has been submitted successfully.</p>
                    <p>It will be reviewed by our admin team and will appear on the site once approved.</p>
                    <a href="index.php" class="btn btn-primary" style="margin-top: 20px;">
                        <i class="fas fa-home"></i> Back to Home
                    </a>
                </div>
            </div>
            
        <?php else: ?>
            <!-- Form Container -->
            <div class="form-container">
                <h2>Submit Your Profile</h2>
                <p style="text-align: center; color: var(--text-light); margin-bottom: 25px;">
                    Fill out the form below to be listed in our directory.
                </p>
                
                <?php if (!empty($error)): ?>
                    <!-- Error Message -->
                    <div class="alert alert-error">
                        <i class="fas fa-exclamation-circle"></i> 
                        <?php echo htmlspecialchars($error); ?>
                    </div>
                <?php endif; ?>
                
                <!-- Lawyer Submission Form -->
                <!-- enctype="multipart/form-data" is required for file uploads -->
                <form action="add-lawyer.php" method="POST" enctype="multipart/form-data" class="validate-form">
                    
                    <!-- Full Name -->
                    <div class="form-group">
                        <label for="name">
                            <i class="fas fa-user"></i> Full Name *
                        </label>
                        <input 
                            type="text" 
                            id="name" 
                            name="name" 
                            class="form-control" 
                            placeholder="e.g., John Smith"
                            value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>"
                            required
                        >
                    </div>
                    
                    <!-- Specialization -->
                    <div class="form-group">
                        <label for="specialization">
                            <i class="fas fa-briefcase"></i> Specialization *
                        </label>
                        <input 
                            type="text" 
                            id="specialization" 
                            name="specialization" 
                            class="form-control" 
                            placeholder="e.g., Criminal Law, Family Law, Corporate Law"
                            value="<?php echo isset($_POST['specialization']) ? htmlspecialchars($_POST['specialization']) : ''; ?>"
                            required
                        >
                    </div>
                    
                    <!-- Years of Experience -->
                    <div class="form-group">
                        <label for="experience">
                            <i class="fas fa-clock"></i> Years of Experience
                        </label>
                        <input 
                            type="number" 
                            id="experience" 
                            name="experience" 
                            class="form-control" 
                            placeholder="e.g., 10"
                            min="0"
                            max="100"
                            value="<?php echo isset($_POST['experience']) ? htmlspecialchars($_POST['experience']) : ''; ?>"
                        >
                    </div>
                    
                    <!-- Phone Number -->
                    <div class="form-group">
                        <label for="phone">
                            <i class="fas fa-phone"></i> Phone Number *
                        </label>
                        <input 
                            type="tel" 
                            id="phone" 
                            name="phone" 
                            class="form-control" 
                            placeholder="e.g., 555-123-4567"
                            value="<?php echo isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : ''; ?>"
                            required
                        >
                    </div>
                    
                    <!-- Email Address -->
                    <div class="form-group">
                        <label for="email">
                            <i class="fas fa-envelope"></i> Email Address *
                        </label>
                        <input 
                            type="email" 
                            id="email" 
                            name="email" 
                            class="form-control" 
                            placeholder="e.g., john@lawfirm.com"
                            value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>"
                            required
                        >
                    </div>
                    
                    <!-- Profile Photo -->
                    <div class="form-group">
                        <label for="photo">
                            <i class="fas fa-camera"></i> Profile Photo
                        </label>
                        <input 
                            type="file" 
                            id="photo" 
                            name="photo" 
                            class="form-control"
                            accept="image/jpeg,image/png,image/gif"
                        >
                        <small style="color: var(--text-light); display: block; margin-top: 5px;">
                            <i class="fas fa-info-circle"></i> 
                            Max 5MB. JPG, PNG, or GIF. If not provided, a default avatar will be used.
                        </small>
                    </div>
                    
                    <!-- Submit Button -->
                    <button type="submit" class="btn btn-primary btn-block">
                        <i class="fas fa-paper-plane"></i> Submit Profile
                    </button>
                    
                    <p style="text-align: center; margin-top: 15px; color: var(--text-light); font-size: 0.9rem;">
                        <i class="fas fa-lock"></i> Your information will be reviewed before being published.
                    </p>
                </form>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php

include 'includes/footer.php';
?>

<?php

