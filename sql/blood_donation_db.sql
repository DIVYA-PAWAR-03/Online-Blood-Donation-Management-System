-- Blood Donation Management System Database Schema
-- Created: September 19, 2025

DROP DATABASE IF EXISTS blood_donation_db;
CREATE DATABASE blood_donation_db;
USE blood_donation_db;

-- Admin table
CREATE TABLE admin (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Users/Donors table
CREATE TABLE users (
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
CREATE TABLE blood_requests (
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

-- Donations table (to track completed donations)
CREATE TABLE donations (
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
CREATE TABLE contact_messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    subject VARCHAR(200) NOT NULL,
    message TEXT NOT NULL,
    is_read BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Blood inventory table (optional - for tracking blood bank stock)
CREATE TABLE blood_inventory (
    id INT AUTO_INCREMENT PRIMARY KEY,
    blood_type ENUM('A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-') NOT NULL,
    units_available INT DEFAULT 0,
    last_updated TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Insert default admin user
INSERT INTO admin (username, email, password, full_name) VALUES 
('admin', 'admin@bloodbank.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'System Administrator');
-- Default password is 'password' (hashed)

-- Insert blood types in inventory
INSERT INTO blood_inventory (blood_type, units_available) VALUES 
('A+', 0), ('A-', 0), ('B+', 0), ('B-', 0), 
('AB+', 0), ('AB-', 0), ('O+', 0), ('O-', 0);

-- Sample data for testing
INSERT INTO users (username, email, password, full_name, phone, blood_type, date_of_birth, gender, weight, address, city, state, pincode, is_verified) VALUES 
('john_doe', 'john@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'John Doe', '9876543210', 'O+', '1990-05-15', 'Male', 70.5, '123 Main Street', 'Mumbai', 'Maharashtra', '400001', TRUE),
('jane_smith', 'jane@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Jane Smith', '9876543211', 'A+', '1985-08-22', 'Female', 60.0, '456 Oak Avenue', 'Delhi', 'Delhi', '110001', TRUE),
('mike_wilson', 'mike@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Mike Wilson', '9876543212', 'B-', '1992-12-10', 'Male', 75.0, '789 Pine Road', 'Bangalore', 'Karnataka', '560001', FALSE);

INSERT INTO blood_requests (requester_name, requester_phone, requester_email, patient_name, blood_type, units_needed, hospital_name, hospital_address, city, state, pincode, urgency, required_date, description) VALUES 
('Sarah Johnson', '9876543213', 'sarah@example.com', 'Robert Johnson', 'O+', 2, 'City General Hospital', '123 Hospital Road', 'Mumbai', 'Maharashtra', '400002', 'High', '2025-09-25', 'Emergency surgery required'),
('David Brown', '9876543214', 'david@example.com', 'Mary Brown', 'A+', 1, 'Apollo Hospital', '456 Medical Center', 'Delhi', 'Delhi', '110002', 'Medium', '2025-09-30', 'Routine surgery preparation');