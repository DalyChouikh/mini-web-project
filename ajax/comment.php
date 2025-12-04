<?php
/**
 * AJAX Comment Handler
 * Receives comment submission from JavaScript
 * This file demonstrates JS ↔ PHP communication
 */

require_once '../includes/config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

header('Content-Type: application/json');

$articleId = isset($_POST['article_id']) ? intval($_POST['article_id']) : 0;
$author = isset($_POST['author']) ? trim($_POST['author']) : '';
$content = isset($_POST['content']) ? trim($_POST['content']) : '';

if ($articleId <= 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid article ID']);
    exit;
}

if (empty($content)) {
    echo json_encode(['success' => false, 'message' => 'Comment content is required']);
    exit;
}

if (empty($author)) {
    $author = 'Anonymous';
}

if (strlen($content) > 1000) {
    echo json_encode(['success' => false, 'message' => 'Comment is too long (max 1000 characters)']);
    exit;
}

try {
    $pdo = getDbConnection();
    
    $checkStmt = $pdo->prepare("SELECT id FROM articles WHERE id = :id");
    $checkStmt->execute(['id' => $articleId]);
    
    if (!$checkStmt->fetch()) {
        echo json_encode(['success' => false, 'message' => 'Article not found']);
        exit;
    }
    
    $stmt = $pdo->prepare("INSERT INTO comments (article_id, author, content, is_approved) VALUES (:article_id, :author, :content, FALSE)");
    $stmt->execute([
        'article_id' => $articleId,
        'author' => $author,
        'content' => $content
    ]);
    
    echo json_encode([
        'success' => true, 
        'message' => 'Thank you! Your comment has been submitted and is pending moderation.'
    ]);
    
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Database error. Please try again later.']);
}
?>
