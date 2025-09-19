<?php
require_once '../includes/config.php';

// Redirect if already logged in
if (isAdminLoggedIn()) {
    redirect('dashboard.php');
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = sanitize($_POST['username']);
    $password = $_POST['password'];
    
    // Validation
    if (empty($username)) {
        $errors[] = "Username is required";
    }
    
    if (empty($password)) {
        $errors[] = "Password is required";
    }
    
    if (empty($errors)) {
        // Check admin credentials
        $db->query("SELECT id, username, email, password, full_name FROM admin WHERE username = :username OR email = :username");
        $db->bind(':username', $username);
        $admin = $db->single();
        
        if ($admin && verifyPassword($password, $admin['password'])) {
            // Set session variables
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_name'] = $admin['full_name'];
            $_SESSION['admin_username'] = $admin['username'];
            
            setFlashMessage('success', 'Welcome to admin panel, ' . $admin['full_name'] . '!');
            redirect('dashboard.php');
        } else {
            $errors[] = "Invalid username or password";
        }
    }
}

$pageTitle = "Admin Login";
include '../includes/header.php';
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow">
                <div class="card-header bg-dark text-white text-center">
                    <h4><i class="fas fa-user-shield me-2"></i>Admin Login</h4>
                </div>
                <div class="card-body">
                    <?php if (!empty($errors)): ?>
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                <?php foreach ($errors as $error): ?>
                                    <li><?php echo $error; ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                    
                    <form method="POST">
                        <div class="mb-3">
                            <label for="username" class="form-label">Username or Email</label>
                            <input type="text" class="form-control" id="username" name="username" 
                                   value="<?php echo isset($_POST['username']) ? $_POST['username'] : ''; ?>" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        
                        <div class="d-grid">
                            <button type="submit" class="btn btn-dark">
                                <i class="fas fa-sign-in-alt me-2"></i>Login to Admin Panel
                            </button>
                        </div>
                    </form>
                    
                    <div class="text-center mt-3">
                        <a href="../index.php" class="text-muted">Back to Main Site</a>
                    </div>
                    
                    <div class="mt-4 p-3 bg-light rounded">
                        <small class="text-muted">
                            <strong>Demo Admin Credentials:</strong><br>
                            Username: admin<br>
                            Password: password
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>