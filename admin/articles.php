<?php
/**
 * Admin Articles Management
 * List all articles with Edit/Delete options
 */

require_once 'includes/auth.php';
requireLogin();

require_once '../includes/config.php';

$pdo = getDbConnection();

$message = '';
$messageType = '';

if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $deleteId = intval($_GET['delete']);
    
    try {
        $stmt = $pdo->prepare("DELETE FROM articles WHERE id = :id");
        $stmt->execute(['id' => $deleteId]);
        
        $message = 'Article deleted successfully.';
        $messageType = 'success';
    } catch (PDOException $e) {
        $message = 'Error deleting article.';
        $messageType = 'error';
    }
}

$articles = $pdo->query("SELECT * FROM articles ORDER BY created_at DESC")->fetchAll();

$commentCounts = [];
$countStmt = $pdo->query("SELECT article_id, COUNT(*) as count FROM comments GROUP BY article_id");
foreach ($countStmt->fetchAll() as $row) {
    $commentCounts[$row['article_id']] = $row['count'];
}

$pendingComments = $pdo->query("SELECT COUNT(*) FROM comments WHERE is_approved = FALSE")->fetchColumn();
$currentTheme = isset($_COOKIE['theme']) ? $_COOKIE['theme'] : 'light';
?>
<!DOCTYPE html>
<html lang="en" data-theme="<?php echo htmlspecialchars($currentTheme); ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Articles - Minimal Blog Admin</title>
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
                <a href="/admin/articles.php" class="admin-nav-link active">📄 Articles</a>
                <a href="/admin/article-form.php" class="admin-nav-link">➕ New Article</a>
                <a href="/admin/comments.php" class="admin-nav-link">
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
                <h1>📄 Manage Articles</h1>
                <a href="/admin/article-form.php" class="btn btn-primary">+ New Article</a>
            </header>
            
            <?php if (!empty($message)): ?>
            <div class="alert alert-<?php echo $messageType; ?>">
                <?php echo escape($message); ?>
            </div>
            <?php endif; ?>
            
            <?php if (empty($articles)): ?>
            <div class="admin-card">
                <p class="empty-message">No articles yet. <a href="/admin/article-form.php">Create your first article</a>.</p>
            </div>
            <?php else: ?>
            <div class="admin-card">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Title</th>
                            <th>Author</th>
                            <th>Date</th>
                            <th>Comments</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($articles as $article): ?>
                        <tr>
                            <td><?php echo $article['id']; ?></td>
                            <td>
                                <a href="/article.php?id=<?php echo $article['id']; ?>" target="_blank">
                                    <?php echo escape($article['title']); ?>
                                </a>
                            </td>
                            <td><?php echo escape($article['author']); ?></td>
                            <td><?php echo formatDate($article['created_at']); ?></td>
                            <td><?php echo isset($commentCounts[$article['id']]) ? $commentCounts[$article['id']] : 0; ?></td>
                            <td class="actions">
                                <a href="/admin/article-form.php?id=<?php echo $article['id']; ?>" class="btn btn-small btn-secondary">Edit</a>
                                <a href="/admin/articles.php?delete=<?php echo $article['id']; ?>" 
                                   class="btn btn-small btn-danger"
                                   onclick="return confirm('Are you sure you want to delete this article? All comments will also be deleted.')">Delete</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php endif; ?>
        </main>
    </div>
    
    <script src="/js/app.js"></script>
</body>
</html>
