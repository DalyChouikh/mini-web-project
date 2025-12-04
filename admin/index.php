<?php
/**
 * Admin Dashboard
 * Shows statistics and quick links
 */

require_once 'includes/auth.php';
requireLogin();

require_once '../includes/config.php';

$pdo = getDbConnection();

$totalArticles = $pdo->query("SELECT COUNT(*) FROM articles")->fetchColumn();
$totalComments = $pdo->query("SELECT COUNT(*) FROM comments")->fetchColumn();
$pendingComments = $pdo->query("SELECT COUNT(*) FROM comments WHERE is_approved = FALSE")->fetchColumn();
$approvedComments = $pdo->query("SELECT COUNT(*) FROM comments WHERE is_approved = TRUE")->fetchColumn();

$recentArticles = $pdo->query("SELECT * FROM articles ORDER BY created_at DESC LIMIT 5")->fetchAll();

$recentPending = $pdo->query("SELECT c.*, a.title as article_title FROM comments c JOIN articles a ON c.article_id = a.id WHERE c.is_approved = FALSE ORDER BY c.created_at DESC LIMIT 5")->fetchAll();

$pageTitle = 'Dashboard';
$isAdmin = true;
$hideSearch = true;
$currentTheme = isset($_COOKIE['theme']) ? $_COOKIE['theme'] : 'light';
?>
<!DOCTYPE html>
<html lang="en" data-theme="<?php echo htmlspecialchars($currentTheme); ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Minimal Blog</title>
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
                <a href="/admin/index.php" class="admin-nav-link active">📊 Dashboard</a>
                <a href="/admin/articles.php" class="admin-nav-link">📄 Articles</a>
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
                <h1>Dashboard</h1>
            </header>
            
            <!-- Stats Cards -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon">📄</div>
                    <div class="stat-content">
                        <div class="stat-number"><?php echo $totalArticles; ?></div>
                        <div class="stat-label">Total Articles</div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">💬</div>
                    <div class="stat-content">
                        <div class="stat-number"><?php echo $totalComments; ?></div>
                        <div class="stat-label">Total Comments</div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">⏳</div>
                    <div class="stat-content">
                        <div class="stat-number"><?php echo $pendingComments; ?></div>
                        <div class="stat-label">Pending Approval</div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">✅</div>
                    <div class="stat-content">
                        <div class="stat-number"><?php echo $approvedComments; ?></div>
                        <div class="stat-label">Approved Comments</div>
                    </div>
                </div>
            </div>
            
            <!-- Recent Content -->
            <div class="admin-grid">
                <!-- Recent Articles -->
                <section class="admin-card">
                    <h2>Recent Articles</h2>
                    <?php if (empty($recentArticles)): ?>
                    <p class="empty-message">No articles yet.</p>
                    <?php else: ?>
                    <ul class="admin-list">
                        <?php foreach ($recentArticles as $article): ?>
                        <li class="admin-list-item">
                            <a href="/admin/article-form.php?id=<?php echo $article['id']; ?>">
                                <?php echo escape($article['title']); ?>
                            </a>
                            <small><?php echo formatDate($article['created_at']); ?></small>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                    <?php endif; ?>
                    <a href="/admin/articles.php" class="admin-link">View all articles →</a>
                </section>
                
                <!-- Pending Comments -->
                <section class="admin-card">
                    <h2>Pending Comments</h2>
                    <?php if (empty($recentPending)): ?>
                    <p class="empty-message">No pending comments. 🎉</p>
                    <?php else: ?>
                    <ul class="admin-list">
                        <?php foreach ($recentPending as $comment): ?>
                        <li class="admin-list-item">
                            <div>
                                <strong><?php echo escape($comment['author']); ?></strong> on 
                                <em><?php echo escape($comment['article_title']); ?></em>
                            </div>
                            <small><?php echo escape(substr($comment['content'], 0, 50)); ?>...</small>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                    <?php endif; ?>
                    <a href="/admin/comments.php" class="admin-link">Manage comments →</a>
                </section>
            </div>
        </main>
    </div>
    
    <script src="/js/app.js"></script>
</body>
</html>
