<?php
require_once 'includes/config.php';

echo "<h2>Blood Donation System - Database Test</h2>";
echo "Test Time: " . date('Y-m-d H:i:s') . "<br><br>";

try {
    // Test database connection
    $db = new Database();
    echo "✅ Database connection: <strong style='color:green'>SUCCESS</strong><br>";
    
    // Test admin table
    $db->query("SELECT COUNT(*) as count FROM admin");
    $adminCount = $db->single();
    echo "✅ Admin users found: <strong>" . $adminCount['count'] . "</strong><br>";
    
    // Test users table
    $db->query("SELECT COUNT(*) as count FROM users");
    $userCount = $db->single();
    echo "✅ Donor users found: <strong>" . $userCount['count'] . "</strong><br>";
    
    // Test blood_requests table
    $db->query("SELECT COUNT(*) as count FROM blood_requests");
    $requestCount = $db->single();
    echo "✅ Blood requests found: <strong>" . $requestCount['count'] . "</strong><br>";
    
    echo "<br><strong style='color:green'>🎉 ALL TESTS PASSED!</strong><br><br>";
    echo "<a href='index.php' style='background:red;color:white;padding:10px;text-decoration:none;border-radius:5px;'>🩸 Go to Main Website</a><br><br>";
    echo "<a href='admin/' style='background:black;color:white;padding:10px;text-decoration:none;border-radius:5px;'>👨‍💼 Go to Admin Panel</a>";
    
} catch (Exception $e) {
    echo "❌ Database error: " . $e->getMessage();
}
?>