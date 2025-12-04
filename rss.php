<?php
/**
 * RSS Feed Generator
 * Generates a static RSS feed of all articles
 */

require_once 'includes/config.php';

$pdo = getDbConnection();

$stmt = $pdo->query("SELECT * FROM articles ORDER BY created_at DESC");
$articles = $stmt->fetchAll();

header('Content-Type: application/rss+xml; charset=UTF-8');

$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
$baseUrl = $protocol . '://' . $_SERVER['HTTP_HOST'];

echo '<?xml version="1.0" encoding="UTF-8"?>';
?>
<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">
    <channel>
        <title>Minimal Blog</title>
        <description>Focused thoughts for focused minds - A minimalist blog about productivity, study tips, and coding.</description>
        <link><?php echo $baseUrl; ?>/index.php</link>
        <atom:link href="<?php echo $baseUrl; ?>/rss.php" rel="self" type="application/rss+xml"/>
        <language>en-us</language>
        <lastBuildDate><?php echo date('r'); ?></lastBuildDate>
        <generator>Minimal Blog PHP</generator>
        
        <?php foreach ($articles as $article): ?>
        <item>
            <title><?php echo htmlspecialchars($article['title'], ENT_XML1, 'UTF-8'); ?></title>
            <description><?php echo htmlspecialchars($article['summary'], ENT_XML1, 'UTF-8'); ?></description>
            <link><?php echo $baseUrl; ?>/article.php?id=<?php echo $article['id']; ?></link>
            <guid isPermaLink="true"><?php echo $baseUrl; ?>/article.php?id=<?php echo $article['id']; ?></guid>
            <pubDate><?php echo date('r', strtotime($article['created_at'])); ?></pubDate>
            <author><?php echo htmlspecialchars($article['author'], ENT_XML1, 'UTF-8'); ?></author>
            <?php 
            $tags = explode(',', $article['tags']);
            foreach ($tags as $tag):
                $tag = trim($tag);
                if (!empty($tag)):
            ?>
            <category><?php echo htmlspecialchars($tag, ENT_XML1, 'UTF-8'); ?></category>
            <?php 
                endif;
            endforeach; 
            ?>
        </item>
        <?php endforeach; ?>
    </channel>
</rss>
