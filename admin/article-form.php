<?php
/**
 * Admin Article Form
 * Add new article or Edit existing article
 */

require_once 'includes/auth.php';
requireLogin();

require_once '../includes/config.php';

$pdo = getDbConnection();

$message = '';
$messageType = '';
$article = [
    'id' => '',
    'title' => '',
    'summary' => '',
    'content' => '',
    'author' => '',
    'image_url' => '',
    'tags' => '',
    'read_time' => 5
];

$isEdit = false;

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $isEdit = true;
    $stmt = $pdo->prepare("SELECT * FROM articles WHERE id = :id");
    $stmt->execute(['id' => intval($_GET['id'])]);
    $existingArticle = $stmt->fetch();
    
    if ($existingArticle) {
        $article = $existingArticle;
    } else {
        header('Location: /admin/articles.php');
        exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $article['title'] = isset($_POST['title']) ? trim($_POST['title']) : '';
    $article['summary'] = isset($_POST['summary']) ? trim($_POST['summary']) : '';
    $article['content'] = isset($_POST['content']) ? trim($_POST['content']) : '';
    $article['author'] = isset($_POST['author']) ? trim($_POST['author']) : 'Anonymous';
    $article['image_url'] = isset($_POST['image_url']) ? trim($_POST['image_url']) : '';
    $article['tags'] = isset($_POST['tags']) ? trim($_POST['tags']) : '';
    $article['read_time'] = isset($_POST['read_time']) ? intval($_POST['read_time']) : 5;
    
    if (empty($article['title'])) {
        $message = 'Title is required.';
        $messageType = 'error';
    } elseif (empty($article['content'])) {
        $message = 'Content is required.';
        $messageType = 'error';
    } else {
        try {
            if ($isEdit) {
                $stmt = $pdo->prepare("UPDATE articles SET 
                    title = :title, 
                    summary = :summary, 
                    content = :content, 
                    author = :author, 
                    image_url = :image_url, 
                    tags = :tags, 
                    read_time = :read_time,
                    updated_at = CURRENT_TIMESTAMP
                    WHERE id = :id");
                $stmt->execute([
                    'title' => $article['title'],
                    'summary' => $article['summary'],
                    'content' => $article['content'],
                    'author' => $article['author'],
                    'image_url' => $article['image_url'],
                    'tags' => $article['tags'],
                    'read_time' => $article['read_time'],
                    'id' => $article['id']
                ]);
                
                $message = 'Article updated successfully!';
                $messageType = 'success';
            } else {
                $stmt = $pdo->prepare("INSERT INTO articles (title, summary, content, author, image_url, tags, read_time) 
                    VALUES (:title, :summary, :content, :author, :image_url, :tags, :read_time)");
                $stmt->execute([
                    'title' => $article['title'],
                    'summary' => $article['summary'],
                    'content' => $article['content'],
                    'author' => $article['author'],
                    'image_url' => $article['image_url'],
                    'tags' => $article['tags'],
                    'read_time' => $article['read_time']
                ]);
                
                $message = 'Article created successfully!';
                $messageType = 'success';
                
                $newId = $pdo->lastInsertId();
                header("Location: /admin/article-form.php?id=$newId&created=1");
                exit;
            }
        } catch (PDOException $e) {
            $message = 'Database error: ' . $e->getMessage();
            $messageType = 'error';
        }
    }
}

if (isset($_GET['created'])) {
    $message = 'Article created successfully!';
    $messageType = 'success';
}

$pendingComments = $pdo->query("SELECT COUNT(*) FROM comments WHERE is_approved = FALSE")->fetchColumn();
$currentTheme = isset($_COOKIE['theme']) ? $_COOKIE['theme'] : 'light';
?>
<!DOCTYPE html>
<html lang="en" data-theme="<?php echo htmlspecialchars($currentTheme); ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $isEdit ? 'Edit' : 'New'; ?> Article - Minimal Blog Admin</title>
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
                <a href="/admin/article-form.php" class="admin-nav-link <?php echo !$isEdit ? 'active' : ''; ?>">➕ New Article</a>
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
                <h1><?php echo $isEdit ? '✏️ Edit Article' : '➕ New Article'; ?></h1>
                <?php if ($isEdit): ?>
                <a href="/article.php?id=<?php echo $article['id']; ?>" class="btn btn-secondary" target="_blank">View Article</a>
                <?php endif; ?>
            </header>
            
            <?php if (!empty($message)): ?>
            <div class="alert alert-<?php echo $messageType; ?>">
                <?php echo escape($message); ?>
            </div>
            <?php endif; ?>
            
            <div class="admin-card">
                <form method="POST" class="article-form">
                    <div class="form-group">
                        <label for="title" class="form-label">Title *</label>
                        <input 
                            type="text" 
                            id="title" 
                            name="title" 
                            class="form-input"
                            value="<?php echo escape($article['title']); ?>"
                            required
                            placeholder="Enter article title"
                        >
                    </div>
                    
                    <div class="form-group">
                        <label for="summary" class="form-label">Summary</label>
                        <textarea 
                            id="summary" 
                            name="summary" 
                            class="form-textarea form-textarea-small"
                            placeholder="A brief description of the article"
                        ><?php echo escape($article['summary']); ?></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="content" class="form-label">Content *</label>
                        <textarea 
                            id="content" 
                            name="content" 
                            class="form-textarea form-textarea-large"
                            required
                            placeholder="Write your article content here..."
                        ><?php echo escape($article['content']); ?></textarea>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="author" class="form-label">Author</label>
                            <input 
                                type="text" 
                                id="author" 
                                name="author" 
                                class="form-input"
                                value="<?php echo escape($article['author']); ?>"
                                placeholder="Author name"
                            >
                        </div>
                        
                        <div class="form-group">
                            <label for="read_time" class="form-label">Read Time (minutes)</label>
                            <input 
                                type="number" 
                                id="read_time" 
                                name="read_time" 
                                class="form-input"
                                value="<?php echo $article['read_time']; ?>"
                                min="1"
                                max="60"
                            >
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="image_url" class="form-label">Image URL</label>
                        <input 
                            type="url" 
                            id="image_url" 
                            name="image_url" 
                            class="form-input"
                            value="<?php echo escape($article['image_url']); ?>"
                            placeholder="https://example.com/image.jpg"
                        >
                    </div>
                    
                    <div class="form-group">
                        <label for="tags" class="form-label">Tags (comma-separated)</label>
                        <input 
                            type="text" 
                            id="tags" 
                            name="tags" 
                            class="form-input"
                            value="<?php echo escape($article['tags']); ?>"
                            placeholder="productivity, coding, tips"
                        >
                    </div>
                    
                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">
                            <?php echo $isEdit ? 'Update Article' : 'Create Article'; ?>
                        </button>
                        <a href="/admin/articles.php" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </main>
    </div>
    
    <script src="/js/app.js"></script>
</body>
</html>
