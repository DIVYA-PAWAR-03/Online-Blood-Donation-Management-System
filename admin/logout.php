<?php
require_once '../includes/config.php';

// Check if admin is logged in
if (!isAdminLoggedIn()) {
    redirect('login.php');
}

// Clear session
session_destroy();

// Redirect to admin login
setFlashMessage('success', 'You have been logged out successfully.');
redirect('login.php');
?>