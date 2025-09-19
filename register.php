<?php
require_once 'includes/config.php';

// Redirect if already logged in
if (isLoggedIn()) {
    redirect('donor/dashboard.php');
}

$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize inputs
    $username = sanitize($_POST['username']);
    $email = sanitize($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $full_name = sanitize($_POST['full_name']);
    $phone = sanitize($_POST['phone']);
    $blood_type = sanitize($_POST['blood_type']);
    $date_of_birth = sanitize($_POST['date_of_birth']);
    $gender = sanitize($_POST['gender']);
    $weight = sanitize($_POST['weight']);
    $medical_conditions = sanitize($_POST['medical_conditions']);
    $address = sanitize($_POST['address']);
    $city = sanitize($_POST['city']);
    $state = sanitize($_POST['state']);
    $pincode = sanitize($_POST['pincode']);
    
    // Validation
    if (empty($username)) {
        $errors[] = "Username is required";
    } elseif (strlen($username) < 3) {
        $errors[] = "Username must be at least 3 characters";
    }
    
    if (empty($email)) {
        $errors[] = "Email is required";
    } elseif (!isValidEmail($email)) {
        $errors[] = "Please enter a valid email";
    }
    
    if (empty($password)) {
        $errors[] = "Password is required";
    } elseif (strlen($password) < 6) {
        $errors[] = "Password must be at least 6 characters";
    }
    
    if ($password !== $confirm_password) {
        $errors[] = "Passwords do not match";
    }
    
    if (empty($full_name)) {
        $errors[] = "Full name is required";
    }
    
    if (empty($phone)) {
        $errors[] = "Phone number is required";
    } elseif (!isValidPhone($phone)) {
        $errors[] = "Please enter a valid 10-digit phone number";
    }
    
    if (empty($blood_type)) {
        $errors[] = "Blood type is required";
    }
    
    if (empty($date_of_birth)) {
        $errors[] = "Date of birth is required";
    }
    
    if (empty($weight) || $weight < 45) {
        $errors[] = "Weight must be at least 45 kg for blood donation";
    }
    
    // Check donor eligibility
    if (!empty($date_of_birth) && !empty($weight)) {
        if (!isDonorEligible($date_of_birth, $weight)) {
            $errors[] = "You must be between 18-65 years old and weigh at least 50 kg to donate blood";
        }
    }
    
    if (empty($errors)) {
        // Check if username or email already exists
        $db->query("SELECT id FROM users WHERE username = :username OR email = :email");
        $db->bind(':username', $username);
        $db->bind(':email', $email);
        $existing_user = $db->single();
        
        if ($existing_user) {
            $errors[] = "Username or email already exists";
        } else {
            // Insert new user
            $hashed_password = hashPassword($password);
            
            $db->query("INSERT INTO users (username, email, password, full_name, phone, blood_type, date_of_birth, gender, weight, medical_conditions, address, city, state, pincode) 
                       VALUES (:username, :email, :password, :full_name, :phone, :blood_type, :date_of_birth, :gender, :weight, :medical_conditions, :address, :city, :state, :pincode)");
            
            $db->bind(':username', $username);
            $db->bind(':email', $email);
            $db->bind(':password', $hashed_password);
            $db->bind(':full_name', $full_name);
            $db->bind(':phone', $phone);
            $db->bind(':blood_type', $blood_type);
            $db->bind(':date_of_birth', $date_of_birth);
            $db->bind(':gender', $gender);
            $db->bind(':weight', $weight);
            $db->bind(':medical_conditions', $medical_conditions);
            $db->bind(':address', $address);
            $db->bind(':city', $city);
            $db->bind(':state', $state);
            $db->bind(':pincode', $pincode);
            
            if ($db->execute()) {
                $success = true;
                setFlashMessage('success', 'Registration successful! Please wait for admin verification before you can login.');
            } else {
                $errors[] = "Registration failed. Please try again.";
            }
        }
    }
}

$pageTitle = "Register as Donor";
include 'includes/header.php';
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-danger text-white text-center">
                    <h4><i class="fas fa-user-plus me-2"></i>Register as Blood Donor</h4>
                </div>
                <div class="card-body">
                    <?php if ($success): ?>
                        <div class="alert alert-success">
                            <h5><i class="fas fa-check-circle me-2"></i>Registration Successful!</h5>
                            <p>Thank you for registering as a blood donor. Your account is now pending verification by our admin team.</p>
                            <p>You will receive an email notification once your account is verified and you can start donating blood.</p>
                            <a href="login.php" class="btn btn-success">Go to Login</a>
                        </div>
                    <?php else: ?>
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
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="username" class="form-label">Username *</label>
                                        <input type="text" class="form-control" id="username" name="username" 
                                               value="<?php echo isset($_POST['username']) ? $_POST['username'] : ''; ?>" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email Address *</label>
                                        <input type="email" class="form-control" id="email" name="email" 
                                               value="<?php echo isset($_POST['email']) ? $_POST['email'] : ''; ?>" required>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="password" class="form-label">Password *</label>
                                        <input type="password" class="form-control" id="password" name="password" required>
                                        <small class="form-text text-muted">Minimum 6 characters</small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="confirm_password" class="form-label">Confirm Password *</label>
                                        <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="full_name" class="form-label">Full Name *</label>
                                <input type="text" class="form-control" id="full_name" name="full_name" 
                                       value="<?php echo isset($_POST['full_name']) ? $_POST['full_name'] : ''; ?>" required>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="phone" class="form-label">Phone Number *</label>
                                        <input type="tel" class="form-control" id="phone" name="phone" 
                                               value="<?php echo isset($_POST['phone']) ? $_POST['phone'] : ''; ?>" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="blood_type" class="form-label">Blood Type *</label>
                                        <select class="form-select" id="blood_type" name="blood_type" required>
                                            <option value="">Select Blood Type</option>
                                            <option value="A+" <?php echo (isset($_POST['blood_type']) && $_POST['blood_type'] == 'A+') ? 'selected' : ''; ?>>A+</option>
                                            <option value="A-" <?php echo (isset($_POST['blood_type']) && $_POST['blood_type'] == 'A-') ? 'selected' : ''; ?>>A-</option>
                                            <option value="B+" <?php echo (isset($_POST['blood_type']) && $_POST['blood_type'] == 'B+') ? 'selected' : ''; ?>>B+</option>
                                            <option value="B-" <?php echo (isset($_POST['blood_type']) && $_POST['blood_type'] == 'B-') ? 'selected' : ''; ?>>B-</option>
                                            <option value="AB+" <?php echo (isset($_POST['blood_type']) && $_POST['blood_type'] == 'AB+') ? 'selected' : ''; ?>>AB+</option>
                                            <option value="AB-" <?php echo (isset($_POST['blood_type']) && $_POST['blood_type'] == 'AB-') ? 'selected' : ''; ?>>AB-</option>
                                            <option value="O+" <?php echo (isset($_POST['blood_type']) && $_POST['blood_type'] == 'O+') ? 'selected' : ''; ?>>O+</option>
                                            <option value="O-" <?php echo (isset($_POST['blood_type']) && $_POST['blood_type'] == 'O-') ? 'selected' : ''; ?>>O-</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="date_of_birth" class="form-label">Date of Birth *</label>
                                        <input type="date" class="form-control" id="date_of_birth" name="date_of_birth" 
                                               value="<?php echo isset($_POST['date_of_birth']) ? $_POST['date_of_birth'] : ''; ?>" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="gender" class="form-label">Gender *</label>
                                        <select class="form-select" id="gender" name="gender" required>
                                            <option value="">Select Gender</option>
                                            <option value="Male" <?php echo (isset($_POST['gender']) && $_POST['gender'] == 'Male') ? 'selected' : ''; ?>>Male</option>
                                            <option value="Female" <?php echo (isset($_POST['gender']) && $_POST['gender'] == 'Female') ? 'selected' : ''; ?>>Female</option>
                                            <option value="Other" <?php echo (isset($_POST['gender']) && $_POST['gender'] == 'Other') ? 'selected' : ''; ?>>Other</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="weight" class="form-label">Weight (kg) *</label>
                                        <input type="number" class="form-control" id="weight" name="weight" step="0.1" min="45"
                                               value="<?php echo isset($_POST['weight']) ? $_POST['weight'] : ''; ?>" required>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="medical_conditions" class="form-label">Medical Conditions (if any)</label>
                                <textarea class="form-control" id="medical_conditions" name="medical_conditions" rows="3"><?php echo isset($_POST['medical_conditions']) ? $_POST['medical_conditions'] : ''; ?></textarea>
                                <small class="form-text text-muted">Please mention any chronic diseases, medications, or health conditions</small>
                            </div>
                            
                            <div class="mb-3">
                                <label for="address" class="form-label">Address *</label>
                                <textarea class="form-control" id="address" name="address" rows="2" required><?php echo isset($_POST['address']) ? $_POST['address'] : ''; ?></textarea>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="city" class="form-label">City *</label>
                                        <input type="text" class="form-control" id="city" name="city" 
                                               value="<?php echo isset($_POST['city']) ? $_POST['city'] : ''; ?>" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="state" class="form-label">State *</label>
                                        <input type="text" class="form-control" id="state" name="state" 
                                               value="<?php echo isset($_POST['state']) ? $_POST['state'] : ''; ?>" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="pincode" class="form-label">PIN Code *</label>
                                        <input type="text" class="form-control" id="pincode" name="pincode" 
                                               value="<?php echo isset($_POST['pincode']) ? $_POST['pincode'] : ''; ?>" required>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="alert alert-info">
                                <h6><i class="fas fa-info-circle me-2"></i>Eligibility Criteria:</h6>
                                <ul class="mb-0">
                                    <li>Age: 18-65 years</li>
                                    <li>Weight: Minimum 50 kg</li>
                                    <li>Should be in good health</li>
                                    <li>No major medical conditions</li>
                                </ul>
                            </div>
                            
                            <div class="d-grid">
                                <button type="submit" class="btn btn-danger btn-lg">
                                    <i class="fas fa-user-plus me-2"></i>Register as Donor
                                </button>
                            </div>
                        </form>
                        
                        <div class="text-center mt-3">
                            <p>Already have an account? <a href="login.php" class="text-danger">Login here</a></p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>