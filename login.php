<?php
require_once 'includes/config.php';

// Redirect if already logged in
if (isLoggedIn()) {
    redirect('donor/dashboard.php');
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = sanitize($_POST['email']);
    $password = $_POST['password'];
    
    // Validation
    if (empty($email)) {
        $errors[] = "Email is required";
    } elseif (!isValidEmail($email)) {
        $errors[] = "Please enter a valid email";
    }
    
    if (empty($password)) {
        $errors[] = "Password is required";
    }
    
    if (empty($errors)) {
        // Check user credentials
        $db->query("SELECT id, username, email, password, full_name, is_verified FROM users WHERE email = :email");
        $db->bind(':email', $email);
        $user = $db->single();
        
        if ($user && verifyPassword($password, $user['password'])) {
            if ($user['is_verified']) {
                // Set session variables
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['full_name'];
                $_SESSION['user_email'] = $user['email'];
                
                setFlashMessage('success', 'Welcome back, ' . $user['full_name'] . '!');
                redirect('donor/dashboard.php');
            } else {
                $errors[] = "Your account is not verified yet. Please wait for admin approval.";
            }
        } else {
            $errors[] = "Invalid email or password";
        }
    }
}

$pageTitle = "Login";
include 'includes/header.php';
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header bg-danger text-white text-center">
                    <h4><i class="fas fa-sign-in-alt me-2"></i>Donor Login</h4>
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
                            <label for="email" class="form-label">Email Address</label>
                            <input type="email" class="form-control" id="email" name="email" 
                                   value="<?php echo isset($_POST['email']) ? $_POST['email'] : ''; ?>" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        
                        <div class="d-grid">
                            <button type="submit" class="btn btn-danger">
                                <i class="fas fa-sign-in-alt me-2"></i>Login
                            </button>
                        </div>
                    </form>
                    
                    <div class="text-center mt-3">
                        <p>Don't have an account? <a href="register.php" class="text-danger">Register as Donor</a></p>
                        <p><a href="forgot_password.php" class="text-muted">Forgot Password?</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>