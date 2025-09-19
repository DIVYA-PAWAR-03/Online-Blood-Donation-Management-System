<?php
// Database Setup Script
echo "<h2>🩸 Blood Donation System - Database Setup</h2>";
echo "<p>Setting up the database...</p>";

// Database connection without selecting database
try {
    $pdo = new PDO('mysql:host=localhost', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Create database
    $pdo->exec("CREATE DATABASE IF NOT EXISTS blood_donation_db");
    echo "✅ Database 'blood_donation_db' created successfully<br>";
    
    // Use the database
    $pdo->exec("USE blood_donation_db");
    
    // Create tables
    $sql = "
    -- Admin table
    CREATE TABLE IF NOT EXISTS admin (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(50) UNIQUE NOT NULL,
        email VARCHAR(100) UNIQUE NOT NULL,
        password VARCHAR(255) NOT NULL,
        full_name VARCHAR(100) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    );

    -- Users/Donors table
    CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(50) UNIQUE NOT NULL,
        email VARCHAR(100) UNIQUE NOT NULL,
        password VARCHAR(255) NOT NULL,
        full_name VARCHAR(100) NOT NULL,
        phone VARCHAR(15) NOT NULL,
        blood_type ENUM('A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-') NOT NULL,
        date_of_birth DATE NOT NULL,
        gender ENUM('Male', 'Female', 'Other') NOT NULL,
        weight DECIMAL(5,2) NOT NULL,
        medical_conditions TEXT DEFAULT NULL,
        address TEXT NOT NULL,
        city VARCHAR(100) NOT NULL,
        state VARCHAR(100) NOT NULL,
        pincode VARCHAR(10) NOT NULL,
        is_verified BOOLEAN DEFAULT FALSE,
        is_available BOOLEAN DEFAULT TRUE,
        last_donation_date DATE DEFAULT NULL,
        profile_image VARCHAR(255) DEFAULT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    );

    -- Blood requests table
    CREATE TABLE IF NOT EXISTS blood_requests (
        id INT AUTO_INCREMENT PRIMARY KEY,
        requester_name VARCHAR(100) NOT NULL,
        requester_phone VARCHAR(15) NOT NULL,
        requester_email VARCHAR(100) NOT NULL,
        patient_name VARCHAR(100) NOT NULL,
        blood_type ENUM('A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-') NOT NULL,
        units_needed INT NOT NULL,
        hospital_name VARCHAR(200) NOT NULL,
        hospital_address TEXT NOT NULL,
        city VARCHAR(100) NOT NULL,
        state VARCHAR(100) NOT NULL,
        pincode VARCHAR(10) NOT NULL,
        urgency ENUM('Low', 'Medium', 'High', 'Emergency') DEFAULT 'Medium',
        required_date DATE NOT NULL,
        description TEXT DEFAULT NULL,
        status ENUM('Active', 'Fulfilled', 'Cancelled', 'Expired') DEFAULT 'Active',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    );

    -- Donations table
    CREATE TABLE IF NOT EXISTS donations (
        id INT AUTO_INCREMENT PRIMARY KEY,
        donor_id INT NOT NULL,
        request_id INT DEFAULT NULL,
        donation_date DATE NOT NULL,
        units_donated INT DEFAULT 1,
        donation_center VARCHAR(200) NOT NULL,
        notes TEXT DEFAULT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (donor_id) REFERENCES users(id) ON DELETE CASCADE,
        FOREIGN KEY (request_id) REFERENCES blood_requests(id) ON DELETE SET NULL
    );

    -- Contact messages table
    CREATE TABLE IF NOT EXISTS contact_messages (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        email VARCHAR(100) NOT NULL,
        subject VARCHAR(200) NOT NULL,
        message TEXT NOT NULL,
        is_read BOOLEAN DEFAULT FALSE,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    );

    -- Blood inventory table
    CREATE TABLE IF NOT EXISTS blood_inventory (
        id INT AUTO_INCREMENT PRIMARY KEY,
        blood_type ENUM('A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-') NOT NULL,
        units_available INT DEFAULT 0,
        last_updated TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    );
    ";
    
    // Execute the SQL
    $pdo->exec($sql);
    echo "✅ All tables created successfully<br>";
    
    // Insert default admin user
    $adminPassword = password_hash('password', PASSWORD_DEFAULT);
    $pdo->exec("INSERT IGNORE INTO admin (username, email, password, full_name) VALUES 
               ('admin', 'admin@bloodbank.com', '$adminPassword', 'System Administrator')");
    echo "✅ Default admin user created<br>";
    
    // Insert blood inventory
    $pdo->exec("INSERT IGNORE INTO blood_inventory (blood_type, units_available) VALUES 
               ('A+', 0), ('A-', 0), ('B+', 0), ('B-', 0), 
               ('AB+', 0), ('AB-', 0), ('O+', 0), ('O-', 0)");
    echo "✅ Blood inventory initialized<br>";
    
    // Insert sample users
    $samplePassword = password_hash('password', PASSWORD_DEFAULT);
    $pdo->exec("INSERT IGNORE INTO users (username, email, password, full_name, phone, blood_type, date_of_birth, gender, weight, address, city, state, pincode, is_verified) VALUES 
               ('john_doe', 'john@example.com', '$samplePassword', 'John Doe', '9876543210', 'O+', '1990-05-15', 'Male', 70.5, '123 Main Street', 'Mumbai', 'Maharashtra', '400001', TRUE),
               ('jane_smith', 'jane@example.com', '$samplePassword', 'Jane Smith', '9876543211', 'A+', '1985-08-22', 'Female', 60.0, '456 Oak Avenue', 'Delhi', 'Delhi', '110001', TRUE)");
    echo "✅ Sample donor accounts created<br>";
    
    // Insert sample blood requests
    $pdo->exec("INSERT IGNORE INTO blood_requests (requester_name, requester_phone, requester_email, patient_name, blood_type, units_needed, hospital_name, hospital_address, city, state, pincode, urgency, required_date, description) VALUES 
               ('Sarah Johnson', '9876543213', 'sarah@example.com', 'Robert Johnson', 'O+', 2, 'City General Hospital', '123 Hospital Road', 'Mumbai', 'Maharashtra', '400002', 'High', '2025-09-25', 'Emergency surgery required'),
               ('David Brown', '9876543214', 'david@example.com', 'Mary Brown', 'A+', 1, 'Apollo Hospital', '456 Medical Center', 'Delhi', 'Delhi', '110002', 'Medium', '2025-09-30', 'Routine surgery preparation')");
    echo "✅ Sample blood requests created<br>";
    
    echo "<br><h3 style='color: green;'>🎉 Database Setup Complete!</h3>";
    echo "<p><strong>Default Login Credentials:</strong></p>";
    echo "<ul>";
    echo "<li><strong>Admin:</strong> Username: admin | Password: password</li>";
    echo "<li><strong>Sample Donor:</strong> Username: john_doe | Password: password</li>";
    echo "<li><strong>Sample Donor:</strong> Username: jane_smith | Password: password</li>";
    echo "</ul>";
    
    echo "<br><a href='test.php' style='background:blue;color:white;padding:10px;text-decoration:none;border-radius:5px;'>🧪 Test Database Connection</a><br><br>";
    echo "<a href='index.php' style='background:red;color:white;padding:10px;text-decoration:none;border-radius:5px;'>🩸 Go to Main Website</a><br><br>";
    echo "<a href='admin/' style='background:black;color:white;padding:10px;text-decoration:none;border-radius:5px;'>👨‍💼 Go to Admin Panel</a>";
    
} catch (PDOException $e) {
    echo "❌ <strong>Error:</strong> " . $e->getMessage();
    echo "<br><br><strong>Troubleshooting:</strong>";
    echo "<ul>";
    echo "<li>Make sure XAMPP MySQL service is running</li>";
    echo "<li>Check if you can access phpMyAdmin: <a href='http://localhost/phpmyadmin/' target='_blank'>http://localhost/phpmyadmin/</a></li>";
    echo "<li>Verify MySQL credentials in includes/config.php</li>";
    echo "</ul>";
}
?>