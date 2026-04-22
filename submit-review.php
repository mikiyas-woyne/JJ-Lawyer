<?php
include 'includes/database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    
    header('Location: index.php');
    exit;
}

$lawyerId = intval($_POST['lawyer_id'] ?? 0);
$userName = trim($_POST['user_name'] ?? '');
$rating = intval($_POST['rating'] ?? 0);
$comment = trim($_POST['comment'] ?? '');


$errors = [];


if ($lawyerId <= 0) {
    $errors[] = 'Invalid lawyer.';
}


if (empty($userName)) {
    $errors[] = 'Please enter your name.';
} elseif (strlen($userName) > 100) {
    $errors[] = 'Name is too long (max 100 characters).';
}


if ($rating < 1 || $rating > 5) {
    $errors[] = 'Please select a rating (1-5 stars).';
}


if (empty($comment)) {
    $errors[] = 'Please write a review comment.';
} elseif (strlen($comment) > 1000) {
    $errors[] = 'Comment is too long (max 1000 characters).';
}





if ($lawyerId > 0) {
    try {
        $stmt = $pdo->prepare("SELECT id FROM lawyers WHERE id = :id AND status = 1");
        $stmt->execute(['id' => $lawyerId]);
        
        if (!$stmt->fetch()) {
            $errors[] = 'Lawyer not found or not approved.';
        }
        
    } catch (PDOException $e) {
        $errors[] = 'Database error. Please try again.';
    }
}





if (!empty($errors)) {
    
    session_start();
    $_SESSION['review_errors'] = $errors;
    
    
    header("Location: lawyer-profile.php?id=$lawyerId");
    exit;
}





try {
    $sql = "
        INSERT INTO reviews (lawyer_id, user_name, rating, comment)
        VALUES (:lawyer_id, :user_name, :rating, :comment)
    ";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'lawyer_id' => $lawyerId,
        'user_name' => $userName,
        'rating' => $rating,
        'comment' => $comment
    ]);
    
    
    session_start();
    $_SESSION['review_success'] = true;
    
    header("Location: lawyer-profile.php?id=$lawyerId");
    exit;
    
} catch (PDOException $e) {
    
    session_start();
    $_SESSION['review_errors'] = ['Error saving review. Please try again.'];
    
    header("Location: lawyer-profile.php?id=$lawyerId");
    exit;
}





























?>
