<?php
/**
 * Home Page - Article List
 * Features: Search and Pagination (server-side)
 */

require_once 'includes/config.php';

$pdo = getDbConnection();

$articlesPerPage = 10;
$currentPage = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$offset = ($currentPage - 1) * $articlesPerPage;

$searchQuery = isset($_GET['search']) ? trim($_GET['search']) : '';

if (!empty($searchQuery)) {
    $searchParam = '%' . $searchQuery . '%';
    
    $sql = "SELECT * FROM articles 
            WHERE title LIKE ? 
            OR summary LIKE ? 
            OR content LIKE ? 
            OR author LIKE ? 
            OR tags LIKE ? 
            ORDER BY created_at DESC 
            LIMIT ? OFFSET ?";
    
    $countSql = "SELECT COUNT(*) FROM articles 
                 WHERE title LIKE ? 
                 OR summary LIKE ? 
                 OR content LIKE ? 
                 OR author LIKE ? 
                 OR tags LIKE ?";
    
    $countStmt = $pdo->prepare($countSql);
    $countStmt->execute([$searchParam, $searchParam, $searchParam, $searchParam, $searchParam]);
    $totalArticles = $countStmt->fetchColumn();
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$searchParam, $searchParam, $searchParam, $searchParam, $searchParam, $articlesPerPage, $offset]);
} else {
    $countStmt = $pdo->query("SELECT COUNT(*) FROM articles");
    $totalArticles = $countStmt->fetchColumn();
    
    $sql = "SELECT * FROM articles ORDER BY created_at DESC LIMIT :limit OFFSET :offset";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':limit', $articlesPerPage, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
}

$articles = $stmt->fetchAll();
$totalPages = ceil($totalArticles / $articlesPerPage);

$pageTitle = !empty($searchQuery) ? "Search: $searchQuery" : "Home";

include 'includes/header.php';
?>

<!-- Article List Section -->
<section class="view">
    <?php if (!empty($searchQuery)): ?>
    <div class="search-results-info">
        <p>Found <strong><?php echo $totalArticles; ?></strong> article<?php echo $totalArticles !== 1 ? 's' : ''; ?> for "<?php echo escape($searchQuery); ?>"</p>
        <a href="/index.php" class="clear-search">Clear search</a>
    </div>
    <?php endif; ?>

    <?php if (empty($articles)): ?>
    <div class="no-results">
        <p>No articles found.</p>
        <?php if (!empty($searchQuery)): ?>
        <p>Try a different search term or <a href="/index.php">view all articles</a>.</p>
        <?php endif; ?>
    </div>
    <?php else: ?>
    
    <!-- Article Grid -->
    <div class="article-grid">
        <?php foreach ($articles as $article): ?>
        <article class="article-card">
            <a href="/article.php?id=<?php echo $article['id']; ?>" class="article-card-link">
                <?php if (!empty($article['image_url'])): ?>
                <img src="<?php echo escape($article['image_url']); ?>" alt="" class="article-card-image" loading="lazy">
                <?php endif; ?>
                <div class="article-card-content">
                    <header class="article-card-header">
                        <h2 class="article-card-title"><?php echo escape($article['title']); ?></h2>
                        <div class="article-card-meta">
                            <time datetime="<?php echo $article['created_at']; ?>">
                                <?php echo formatDate($article['created_at']); ?>
                            </time>
                            <span>•</span>
                            <span><?php echo $article['read_time']; ?> min read</span>
                        </div>
                    </header>
                    <p class="article-card-summary"><?php echo escape($article['summary']); ?></p>
                    <footer class="article-card-footer">
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
                        <span class="read-button">Read →</span>
                    </footer>
                </div>
            </a>
        </article>
        <?php endforeach; ?>
    </div>

    <!-- Pagination -->
    <?php if ($totalPages > 1): ?>
    <nav class="pagination" aria-label="Article pagination">
        <?php 
        $queryParams = [];
        if (!empty($searchQuery)) {
            $queryParams['search'] = $searchQuery;
        }
        ?>
        
        <?php if ($currentPage > 1): ?>
        <a href="?<?php echo http_build_query(array_merge($queryParams, ['page' => $currentPage - 1])); ?>" class="pagination-link pagination-prev">
            ← Previous
        </a>
        <?php endif; ?>
        
        <div class="pagination-numbers">
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <a href="?<?php echo http_build_query(array_merge($queryParams, ['page' => $i])); ?>" 
               class="pagination-link <?php echo $i === $currentPage ? 'active' : ''; ?>">
                <?php echo $i; ?>
            </a>
            <?php endfor; ?>
        </div>
        
        <?php if ($currentPage < $totalPages): ?>
        <a href="?<?php echo http_build_query(array_merge($queryParams, ['page' => $currentPage + 1])); ?>" class="pagination-link pagination-next">
            Next →
        </a>
        <?php endif; ?>
    </nav>
    <?php endif; ?>
    
    <?php endif; ?>
</section>

<?php include 'includes/footer.php'; ?>
