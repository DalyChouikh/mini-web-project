<?php
/**
 * Article Detail Page
 * Shows full article content and comments
 * Comments are submitted via AJAX (JS ↔ PHP communication)
 */

require_once 'includes/config.php';

$pdo = getDbConnection();

$articleId = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($articleId <= 0) {
    header('Location: /index.php');
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM articles WHERE id = :id");
$stmt->execute(['id' => $articleId]);
$article = $stmt->fetch();

if (!$article) {
    header('Location: /index.php');
    exit;
}

$commentStmt = $pdo->prepare("SELECT * FROM comments WHERE article_id = :article_id AND is_approved = TRUE ORDER BY created_at DESC");
$commentStmt->execute(['article_id' => $articleId]);
$comments = $commentStmt->fetchAll();

$pageTitle = $article['title'];

include 'includes/header.php';
?>

<!-- Back Button -->
<a href="/index.php" class="back-button">
    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <path d="M19 12H5M12 19l-7-7 7-7"/>
    </svg>
    <span>Back to articles</span>
</a>

<!-- Article Detail -->
<article class="article-detail">
    <?php if (!empty($article['image_url'])): ?>
    <img src="<?php echo escape($article['image_url']); ?>" alt="" class="article-detail-image">
    <?php endif; ?>
    
    <div class="article-detail-content">
        <header class="article-detail-header">
            <h1 class="article-detail-title"><?php echo escape($article['title']); ?></h1>
            <div class="article-detail-meta">
                <span>By <?php echo escape($article['author']); ?></span>
                <span>•</span>
                <time datetime="<?php echo $article['created_at']; ?>"><?php echo formatDate($article['created_at']); ?></time>
                <span>•</span>
                <span><?php echo $article['read_time']; ?> min read</span>
            </div>
        </header>
        
        <div class="article-detail-body">
            <?php 
            $paragraphs = explode("\n", $article['content']);
            foreach ($paragraphs as $paragraph):
                $paragraph = trim($paragraph);
                if (!empty($paragraph)):
            ?>
            <p><?php echo escape($paragraph); ?></p>
            <?php 
                endif;
            endforeach; 
            ?>
        </div>
        
        <footer class="article-detail-footer">
            <div class="article-tags">
                <?php 
                $tags = explode(',', $article['tags']);
                foreach ($tags as $tag): 
                    $tag = trim($tag);
                    if (!empty($tag)):
                ?>
                <span class="tag"><?php echo escape($tag); ?></span>
                <?php 
                    endif;
                endforeach; 
                ?>
            </div>
            
            <!-- Share Buttons (simulated) -->
            <div class="share-buttons">
                <button class="share-button" data-action="copy" onclick="copyLink()">📋 Copy link</button>
                <button class="share-button" data-action="twitter" onclick="shareTwitter()">🐦 Twitter</button>
                <button class="share-button" data-action="linkedin" onclick="shareLinkedIn()">💼 LinkedIn</button>
            </div>
        </footer>
    </div>
</article>

<!-- Comments Section -->
<section class="comments-section">
    <h2 class="comments-header">Comments (<span id="comment-count"><?php echo count($comments); ?></span>)</h2>
    
    <!-- Comments List -->
    <div id="comments-list">
        <?php if (empty($comments)): ?>
        <p class="no-comments">No comments yet. Be the first to share your thoughts!</p>
        <?php else: ?>
        <ul class="comments-list">
            <?php foreach ($comments as $comment): ?>
            <li class="comment">
                <div class="comment-header">
                    <span class="comment-author"><?php echo escape($comment['author']); ?></span>
                    <time class="comment-date" datetime="<?php echo $comment['created_at']; ?>">
                        <?php echo formatDateTime($comment['created_at']); ?>
                    </time>
                </div>
                <p class="comment-body"><?php echo escape($comment['content']); ?></p>
            </li>
            <?php endforeach; ?>
        </ul>
        <?php endif; ?>
    </div>
    
    <!-- Comment Form (submitted via AJAX) -->
    <form id="comment-form" class="comment-form" data-article-id="<?php echo $articleId; ?>">
        <div class="form-group">
            <label for="comment-name" class="form-label">Name</label>
            <input
                type="text"
                id="comment-name"
                name="author"
                class="form-input"
                placeholder="Your name (optional)"
                autocomplete="name"
            >
        </div>
        <div class="form-group">
            <label for="comment-text" class="form-label">Comment</label>
            <textarea
                id="comment-text"
                name="content"
                class="form-textarea"
                placeholder="Share your thoughts..."
                required
            ></textarea>
        </div>
        <button type="submit" class="form-submit">Post Comment</button>
        <p class="form-note">Your comment will appear after moderation.</p>
    </form>
</section>

<script>
function copyLink() {
    navigator.clipboard.writeText(window.location.href).then(() => {
        showToast('Link copied to clipboard!', 'success');
    }).catch(() => {
        showToast('Failed to copy link', 'error');
    });
}

function shareTwitter() {
    const text = encodeURIComponent(document.title);
    const url = encodeURIComponent(window.location.href);
    window.open(`https://twitter.com/intent/tweet?text=${text}&url=${url}`, '_blank');
    showToast('Opening Twitter...', 'success');
}

function shareLinkedIn() {
    const url = encodeURIComponent(window.location.href);
    window.open(`https://www.linkedin.com/sharing/share-offsite/?url=${url}`, '_blank');
    showToast('Opening LinkedIn...', 'success');
}
</script>

<?php include 'includes/footer.php'; ?>
