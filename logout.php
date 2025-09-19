<?php
require_once 'includes/config.php';

// Check if user is logged in
if (!isLoggedIn()) {
    redirect('login.php');
}

// Clear session
session_destroy();

// Redirect to home page
setFlashMessage('success', 'You have been logged out successfully.');
redirect('index.php');
?>