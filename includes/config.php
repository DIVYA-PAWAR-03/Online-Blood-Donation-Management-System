<?php
/**
 * Blood Donation Management System
 * Database Configuration and Utility Functions
 * Created: September 19, 2025
 */

// Database configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'blood_donation_db');

// Application configuration
define('SITE_NAME', 'Blood Donation Management System');
define('SITE_URL', 'http://localhost/Project/');
define('ADMIN_EMAIL', 'admin@bloodbank.com');

// File upload configuration
define('UPLOAD_PATH', 'assets/images/profiles/');
define('MAX_FILE_SIZE', 2097152); // 2MB in bytes
define('ALLOWED_EXTENSIONS', ['jpg', 'jpeg', 'png', 'gif']);

// Session configuration
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_secure', 0); // Set to 1 if using HTTPS

// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

/**
 * Database connection class
 */
class Database {
    private $host = DB_HOST;
    private $user = DB_USER;
    private $pass = DB_PASS;
    private $dbname = DB_NAME;
    private $dbh;
    private $error;

    public function __construct() {
        $dsn = 'mysql:host=' . $this->host . ';dbname=' . $this->dbname . ';charset=utf8mb4';
        $options = [
            PDO::ATTR_PERSISTENT => true,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];

        try {
            $this->dbh = new PDO($dsn, $this->user, $this->pass, $options);
        } catch (PDOException $e) {
            $this->error = $e->getMessage();
            die('Database connection failed: ' . $this->error);
        }
    }

    public function query($query) {
        $this->stmt = $this->dbh->prepare($query);
    }

    public function bind($param, $value, $type = null) {
        if (is_null($type)) {
            switch (true) {
                case is_int($value):
                    $type = PDO::PARAM_INT;
                    break;
                case is_bool($value):
                    $type = PDO::PARAM_BOOL;
                    break;
                case is_null($value):
                    $type = PDO::PARAM_NULL;
                    break;
                default:
                    $type = PDO::PARAM_STR;
            }
        }
        $this->stmt->bindValue($param, $value, $type);
    }

    public function execute() {
        return $this->stmt->execute();
    }

    public function resultset() {
        $this->execute();
        return $this->stmt->fetchAll();
    }

    public function single() {
        $this->execute();
        return $this->stmt->fetch();
    }

    public function rowCount() {
        return $this->stmt->rowCount();
    }

    public function lastInsertId() {
        return $this->dbh->lastInsertId();
    }
}

/**
 * Utility Functions
 */

// Sanitize input data
function sanitize($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Validate email
function isValidEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

// Validate phone number (Indian format)
function isValidPhone($phone) {
    return preg_match('/^[6-9]\d{9}$/', $phone);
}

// Check if user is logged in
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

// Check if admin is logged in
function isAdminLoggedIn() {
    return isset($_SESSION['admin_id']);
}

// Redirect function
function redirect($url) {
    header("Location: " . $url);
    exit();
}

// Flash message system
function setFlashMessage($type, $message) {
    $_SESSION['flash_message'] = [
        'type' => $type,
        'message' => $message
    ];
}

function getFlashMessage() {
    if (isset($_SESSION['flash_message'])) {
        $message = $_SESSION['flash_message'];
        unset($_SESSION['flash_message']);
        return $message;
    }
    return null;
}

// Password hashing
function hashPassword($password) {
    return password_hash($password, PASSWORD_DEFAULT);
}

// Password verification
function verifyPassword($password, $hash) {
    return password_verify($password, $hash);
}

// Generate CSRF token
function generateCSRFToken() {
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

// Verify CSRF token
function verifyCSRFToken($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

// Format date
function formatDate($date, $format = 'Y-m-d') {
    return date($format, strtotime($date));
}

// Calculate age from date of birth
function calculateAge($dateOfBirth) {
    return date_diff(date_create($dateOfBirth), date_create('today'))->y;
}

// Check if donor is eligible (age 18-65, weight >= 50kg)
function isDonorEligible($dateOfBirth, $weight) {
    $age = calculateAge($dateOfBirth);
    return ($age >= 18 && $age <= 65 && $weight >= 50);
}

// Get blood type compatibility
function getCompatibleBloodTypes($requestedType) {
    $compatibility = [
        'A+' => ['A+', 'A-', 'O+', 'O-'],
        'A-' => ['A-', 'O-'],
        'B+' => ['B+', 'B-', 'O+', 'O-'],
        'B-' => ['B-', 'O-'],
        'AB+' => ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'],
        'AB-' => ['A-', 'B-', 'AB-', 'O-'],
        'O+' => ['O+', 'O-'],
        'O-' => ['O-']
    ];
    
    return $compatibility[$requestedType] ?? [];
}

// File upload function
function uploadProfileImage($file) {
    if (!isset($file['error']) || is_array($file['error'])) {
        throw new RuntimeException('Invalid parameters.');
    }

    switch ($file['error']) {
        case UPLOAD_ERR_OK:
            break;
        case UPLOAD_ERR_NO_FILE:
            throw new RuntimeException('No file sent.');
        case UPLOAD_ERR_INI_SIZE:
        case UPLOAD_ERR_FORM_SIZE:
            throw new RuntimeException('Exceeded filesize limit.');
        default:
            throw new RuntimeException('Unknown errors.');
    }

    if ($file['size'] > MAX_FILE_SIZE) {
        throw new RuntimeException('Exceeded filesize limit.');
    }

    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mimeType = $finfo->file($file['tmp_name']);
    $allowedMimes = [
        'jpg' => 'image/jpeg',
        'png' => 'image/png',
        'gif' => 'image/gif',
    ];

    $ext = array_search($mimeType, $allowedMimes, true);
    if ($ext === false) {
        throw new RuntimeException('Invalid file format.');
    }

    $filename = sprintf('%s_%s.%s', uniqid(), time(), $ext);
    $uploadPath = UPLOAD_PATH . $filename;

    if (!is_dir(UPLOAD_PATH)) {
        mkdir(UPLOAD_PATH, 0755, true);
    }

    if (!move_uploaded_file($file['tmp_name'], $uploadPath)) {
        throw new RuntimeException('Failed to move uploaded file.');
    }

    return $filename;
}

// Error logging
function logError($message, $file = '', $line = '') {
    $logMessage = date('Y-m-d H:i:s') . " - Error: $message";
    if ($file) $logMessage .= " in file: $file";
    if ($line) $logMessage .= " on line: $line";
    $logMessage .= PHP_EOL;
    
    error_log($logMessage, 3, 'logs/error.log');
}

// Create database instance
$db = new Database();
?>