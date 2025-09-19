<?php
require_once '../includes/config.php';

// Check if user is logged in
if (!isLoggedIn()) {
    redirect('../login.php');
}

$user_id = $_SESSION['user_id'];
$errors = [];
$success = false;

// Get current user data
$db->query("SELECT * FROM users WHERE id = :user_id");
$db->bind(':user_id', $user_id);
$user = $db->single();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $full_name = sanitize($_POST['full_name']);
    $phone = sanitize($_POST['phone']);
    $blood_type = sanitize($_POST['blood_type']);
    $weight = sanitize($_POST['weight']);
    $medical_conditions = sanitize($_POST['medical_conditions']);
    $address = sanitize($_POST['address']);
    $city = sanitize($_POST['city']);
    $state = sanitize($_POST['state']);
    $pincode = sanitize($_POST['pincode']);
    
    // Validation
    if (empty($full_name)) {
        $errors[] = "Full name is required";
    }
    
    if (empty($phone)) {
        $errors[] = "Phone number is required";
    } elseif (!isValidPhone($phone)) {
        $errors[] = "Please enter a valid 10-digit phone number";
    }
    
    if (empty($weight) || $weight < 45) {
        $errors[] = "Weight must be at least 45 kg";
    }
    
    if (empty($errors)) {
        $db->query("UPDATE users SET 
                   full_name = :full_name, 
                   phone = :phone, 
                   blood_type = :blood_type, 
                   weight = :weight, 
                   medical_conditions = :medical_conditions, 
                   address = :address, 
                   city = :city, 
                   state = :state, 
                   pincode = :pincode 
                   WHERE id = :user_id");
        
        $db->bind(':full_name', $full_name);
        $db->bind(':phone', $phone);
        $db->bind(':blood_type', $blood_type);
        $db->bind(':weight', $weight);
        $db->bind(':medical_conditions', $medical_conditions);
        $db->bind(':address', $address);
        $db->bind(':city', $city);
        $db->bind(':state', $state);
        $db->bind(':pincode', $pincode);
        $db->bind(':user_id', $user_id);
        
        if ($db->execute()) {
            $success = true;
            $_SESSION['user_name'] = $full_name; // Update session
            
            // Refresh user data
            $db->query("SELECT * FROM users WHERE id = :user_id");
            $db->bind(':user_id', $user_id);
            $user = $db->single();
            
            setFlashMessage('success', 'Profile updated successfully!');
        } else {
            $errors[] = "Failed to update profile. Please try again.";
        }
    }
}

$pageTitle = "Edit Profile";
include '../includes/header.php';
?>

<div class="container py-4">
    <div class="row">
        <div class="col-md-3">
            <!-- Sidebar -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-user me-2"></i>Profile Menu</h5>
                </div>
                <div class="list-group list-group-flush">
                    <a href="dashboard.php" class="list-group-item list-group-item-action">
                        <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                    </a>
                    <a href="profile.php" class="list-group-item list-group-item-action active">
                        <i class="fas fa-user-edit me-2"></i>Edit Profile
                    </a>
                    <a href="change_password.php" class="list-group-item list-group-item-action">
                        <i class="fas fa-key me-2"></i>Change Password
                    </a>
                    <a href="availability.php" class="list-group-item list-group-item-action">
                        <i class="fas fa-toggle-on me-2"></i>Availability
                    </a>
                    <a href="donation_history.php" class="list-group-item list-group-item-action">
                        <i class="fas fa-history me-2"></i>Donation History
                    </a>
                </div>
            </div>
        </div>
        
        <div class="col-md-9">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-user-edit me-2"></i>Edit Profile</h5>
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
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="username" class="form-label">Username</label>
                                    <input type="text" class="form-control" id="username" value="<?php echo htmlspecialchars($user['username']); ?>" disabled>
                                    <small class="form-text text-muted">Username cannot be changed</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email Address</label>
                                    <input type="email" class="form-control" id="email" value="<?php echo htmlspecialchars($user['email']); ?>" disabled>
                                    <small class="form-text text-muted">Email cannot be changed</small>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="full_name" class="form-label">Full Name *</label>
                            <input type="text" class="form-control" id="full_name" name="full_name" 
                                   value="<?php echo htmlspecialchars($user['full_name']); ?>" required>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="phone" class="form-label">Phone Number *</label>
                                    <input type="tel" class="form-control" id="phone" name="phone" 
                                           value="<?php echo htmlspecialchars($user['phone']); ?>" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="blood_type" class="form-label">Blood Type *</label>
                                    <select class="form-select" id="blood_type" name="blood_type" required>
                                        <option value="">Select Blood Type</option>
                                        <option value="A+" <?php echo ($user['blood_type'] == 'A+') ? 'selected' : ''; ?>>A+</option>
                                        <option value="A-" <?php echo ($user['blood_type'] == 'A-') ? 'selected' : ''; ?>>A-</option>
                                        <option value="B+" <?php echo ($user['blood_type'] == 'B+') ? 'selected' : ''; ?>>B+</option>
                                        <option value="B-" <?php echo ($user['blood_type'] == 'B-') ? 'selected' : ''; ?>>B-</option>
                                        <option value="AB+" <?php echo ($user['blood_type'] == 'AB+') ? 'selected' : ''; ?>>AB+</option>
                                        <option value="AB-" <?php echo ($user['blood_type'] == 'AB-') ? 'selected' : ''; ?>>AB-</option>
                                        <option value="O+" <?php echo ($user['blood_type'] == 'O+') ? 'selected' : ''; ?>>O+</option>
                                        <option value="O-" <?php echo ($user['blood_type'] == 'O-') ? 'selected' : ''; ?>>O-</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="date_of_birth" class="form-label">Date of Birth</label>
                                    <input type="date" class="form-control" id="date_of_birth" 
                                           value="<?php echo $user['date_of_birth']; ?>" disabled>
                                    <small class="form-text text-muted">Cannot be changed</small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="gender" class="form-label">Gender</label>
                                    <input type="text" class="form-control" id="gender" 
                                           value="<?php echo htmlspecialchars($user['gender']); ?>" disabled>
                                    <small class="form-text text-muted">Cannot be changed</small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="weight" class="form-label">Weight (kg) *</label>
                                    <input type="number" class="form-control" id="weight" name="weight" step="0.1" min="45"
                                           value="<?php echo $user['weight']; ?>" required>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="medical_conditions" class="form-label">Medical Conditions</label>
                            <textarea class="form-control" id="medical_conditions" name="medical_conditions" rows="3"><?php echo htmlspecialchars($user['medical_conditions']); ?></textarea>
                            <small class="form-text text-muted">Please mention any chronic diseases, medications, or health conditions</small>
                        </div>
                        
                        <div class="mb-3">
                            <label for="address" class="form-label">Address *</label>
                            <textarea class="form-control" id="address" name="address" rows="2" required><?php echo htmlspecialchars($user['address']); ?></textarea>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="city" class="form-label">City *</label>
                                    <input type="text" class="form-control" id="city" name="city" 
                                           value="<?php echo htmlspecialchars($user['city']); ?>" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="state" class="form-label">State *</label>
                                    <input type="text" class="form-control" id="state" name="state" 
                                           value="<?php echo htmlspecialchars($user['state']); ?>" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="pincode" class="form-label">PIN Code *</label>
                                    <input type="text" class="form-control" id="pincode" name="pincode" 
                                           value="<?php echo htmlspecialchars($user['pincode']); ?>" required>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Account Status</label>
                                    <div class="form-control-plaintext">
                                        <span class="badge bg-<?php echo $user['is_verified'] ? 'success' : 'warning'; ?>">
                                            <?php echo $user['is_verified'] ? 'Verified' : 'Pending Verification'; ?>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Registration Date</label>
                                    <div class="form-control-plaintext">
                                        <?php echo formatDate($user['created_at'], 'd M Y'); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-between">
                            <a href="dashboard.php" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
                            </a>
                            <button type="submit" class="btn btn-danger">
                                <i class="fas fa-save me-2"></i>Update Profile
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>