<?php
/**
 * Admin Authentication Helper
 * Checks if user is logged in, redirects to login if not
 */

session_start();

function isLoggedIn() {
    return isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;
}

function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: /admin/login.php');
        exit;
    }
}

function getAdminUsername() {
    return isset($_SESSION['admin_username']) ? $_SESSION['admin_username'] : '';
}
?>
