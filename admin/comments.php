<?php
/**
 * Admin Comments Management
 * Approve or delete comments
 */

require_once 'includes/auth.php';
requireLogin();

require_once '../includes/config.php';

$pdo = getDbConnection();

$message = '';
$messageType = '';

if (isset($_GET['approve']) && is_numeric($_GET['approve'])) {
    $approveId = intval($_GET['approve']);
    
    try {
        $stmt = $pdo->prepare("UPDATE comments SET is_approved = TRUE WHERE id = :id");
        $stmt->execute(['id' => $approveId]);
        
        $message = 'Comment approved successfully.';
        $messageType = 'success';
    } catch (PDOException $e) {
        $message = 'Error approving comment.';
        $messageType = 'error';
    }
}

if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $deleteId = intval($_GET['delete']);
    
    try {
        $stmt = $pdo->prepare("DELETE FROM comments WHERE id = :id");
        $stmt->execute(['id' => $deleteId]);
        
        $message = 'Comment deleted successfully.';
        $messageType = 'success';
    } catch (PDOException $e) {
        $message = 'Error deleting comment.';
        $messageType = 'error';
    }
}

$filter = isset($_GET['filter']) ? $_GET['filter'] : 'pending';

if ($filter === 'approved') {
    $comments = $pdo->query("SELECT c.*, a.title as article_title FROM comments c JOIN articles a ON c.article_id = a.id WHERE c.is_approved = TRUE ORDER BY c.created_at DESC")->fetchAll();
} elseif ($filter === 'all') {
    $comments = $pdo->query("SELECT c.*, a.title as article_title FROM comments c JOIN articles a ON c.article_id = a.id ORDER BY c.created_at DESC")->fetchAll();
} else {
    $comments = $pdo->query("SELECT c.*, a.title as article_title FROM comments c JOIN articles a ON c.article_id = a.id WHERE c.is_approved = FALSE ORDER BY c.created_at DESC")->fetchAll();
}

$pendingComments = $pdo->query("SELECT COUNT(*) FROM comments WHERE is_approved = FALSE")->fetchColumn();
$currentTheme = isset($_COOKIE['theme']) ? $_COOKIE['theme'] : 'light';
?>
<!DOCTYPE html>
<html lang="en" data-theme="<?php echo htmlspecialchars($currentTheme); ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Comments - Minimal Blog Admin</title>
    <link rel="stylesheet" href="/styles/base.css">
    <link rel="stylesheet" href="/styles/themes.css">
    <link rel="stylesheet" href="/styles/admin.css">
</head>
<body>
    <div class="admin-layout">
        <!-- Sidebar -->
        <aside class="admin-sidebar">
            <div class="admin-brand">
                <h2>📝 Admin Panel</h2>
                <p>Welcome, <?php echo escape(getAdminUsername()); ?></p>
            </div>
            <nav class="admin-nav">
                <a href="/admin/index.php" class="admin-nav-link">📊 Dashboard</a>
                <a href="/admin/articles.php" class="admin-nav-link">📄 Articles</a>
                <a href="/admin/article-form.php" class="admin-nav-link">➕ New Article</a>
                <a href="/admin/comments.php" class="admin-nav-link active">
                    💬 Comments
                    <?php if ($pendingComments > 0): ?>
                    <span class="badge"><?php echo $pendingComments; ?></span>
                    <?php endif; ?>
                </a>
                <hr>
                <a href="/index.php" class="admin-nav-link" target="_blank">🌐 View Blog</a>
                <a href="/admin/logout.php" class="admin-nav-link">🚪 Logout</a>
            </nav>
        </aside>
        
        <!-- Main Content -->
        <main class="admin-main">
            <header class="admin-header">
                <h1>💬 Manage Comments</h1>
                <div class="filter-tabs">
                    <a href="?filter=pending" class="filter-tab <?php echo $filter === 'pending' ? 'active' : ''; ?>">
                        Pending (<?php echo $pendingComments; ?>)
                    </a>
                    <a href="?filter=approved" class="filter-tab <?php echo $filter === 'approved' ? 'active' : ''; ?>">
                        Approved
                    </a>
                    <a href="?filter=all" class="filter-tab <?php echo $filter === 'all' ? 'active' : ''; ?>">
                        All
                    </a>
                </div>
            </header>
            
            <?php if (!empty($message)): ?>
            <div class="alert alert-<?php echo $messageType; ?>">
                <?php echo escape($message); ?>
            </div>
            <?php endif; ?>
            
            <?php if (empty($comments)): ?>
            <div class="admin-card">
                <p class="empty-message">
                    <?php if ($filter === 'pending'): ?>
                    No pending comments. 🎉
                    <?php else: ?>
                    No comments found.
                    <?php endif; ?>
                </p>
            </div>
            <?php else: ?>
            <div class="comments-grid">
                <?php foreach ($comments as $comment): ?>
                <div class="comment-card <?php echo $comment['is_approved'] ? 'approved' : 'pending'; ?>">
                    <div class="comment-card-header">
                        <div class="comment-meta">
                            <strong><?php echo escape($comment['author']); ?></strong>
                            <span class="comment-status <?php echo $comment['is_approved'] ? 'status-approved' : 'status-pending'; ?>">
                                <?php echo $comment['is_approved'] ? '✅ Approved' : '⏳ Pending'; ?>
                            </span>
                        </div>
                        <time><?php echo formatDateTime($comment['created_at']); ?></time>
                    </div>
                    
                    <p class="comment-card-body"><?php echo escape($comment['content']); ?></p>
                    
                    <div class="comment-card-footer">
                        <span class="comment-article">
                            On: <a href="/article.php?id=<?php echo $comment['article_id']; ?>" target="_blank">
                                <?php echo escape($comment['article_title']); ?>
                            </a>
                        </span>
                        <div class="comment-actions">
                            <?php if (!$comment['is_approved']): ?>
                            <a href="?approve=<?php echo $comment['id']; ?>&filter=<?php echo $filter; ?>" 
                               class="btn btn-small btn-success">✓ Approve</a>
                            <?php endif; ?>
                            <a href="?delete=<?php echo $comment['id']; ?>&filter=<?php echo $filter; ?>" 
                               class="btn btn-small btn-danger"
                               onclick="return confirm('Are you sure you want to delete this comment?')">✕ Delete</a>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </main>
    </div>
    
    <script src="/js/app.js"></script>
</body>
</html>
