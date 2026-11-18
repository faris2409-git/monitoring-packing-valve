<?php
/**
 * Auto-redirect to Laravel public directory
 * Prevents 403 Forbidden or Directory Listing when accessing via localhost
 */
$uri = $_SERVER['REQUEST_URI'] ?? '/';
if (strpos($uri, '/public') === false) {
    $redirectUrl = rtrim($uri, '/') . '/public/';
    header("Location: " . $redirectUrl, true, 302);
    exit;
}
