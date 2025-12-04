<?php
/**
 * Header Template
 * Shared HTML header for all pages
 */

$currentTheme = isset($_COOKIE['theme']) ? $_COOKIE['theme'] : 'light';
?>
<!DOCTYPE html>
<html lang="en" data-theme="<?php echo escape($currentTheme); ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="A minimalist interactive blog with dark mode, comments, and smooth navigation">
    <title><?php echo isset($pageTitle) ? escape($pageTitle) . ' - ' : ''; ?>Minimal Blog</title>
    <link rel="stylesheet" href="/styles/base.css">
    <link rel="stylesheet" href="/styles/themes.css">
    <?php if (isset($isAdmin) && $isAdmin): ?>
    <link rel="stylesheet" href="/styles/admin.css">
    <?php endif; ?>
    <!-- RSS Feed -->
    <link rel="alternate" type="application/rss+xml" title="Minimal Blog RSS Feed" href="/rss.php">
</head>
<body>
    <!-- Scroll Progress Indicator -->
    <div id="scroll-indicator" class="scroll-indicator"></div>

    <!-- Header -->
    <header class="header">
        <div class="container">
            <div class="header-content">
                <a href="/index.php" class="brand">
                    <h1 class="brand-title">Minimal Blog</h1>
                    <p class="brand-subtitle">Focused thoughts for focused minds</p>
                </a>
                <div class="header-controls">
                    <?php if (!isset($hideSearch) || !$hideSearch): ?>
                    <form action="/index.php" method="GET" class="search-box">
                        <svg class="search-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="11" cy="11" r="8"></circle>
                            <path d="m21 21-4.35-4.35"></path>
                        </svg>
                        <input
                            type="search"
                            name="search"
                            class="search-input"
                            placeholder="Search articles..."
                            value="<?php echo isset($_GET['search']) ? escape($_GET['search']) : ''; ?>"
                            autocomplete="off"
                        >
                    </form>
                    <?php endif; ?>
                    <button id="theme-toggle" class="theme-toggle" aria-label="Toggle theme">
                        <span class="theme-icon"><?php echo $currentTheme === 'dark' ? '🌙' : '☀️'; ?></span>
                    </button>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="main">
        <div class="container">
