<?php
require_once 'includes/config.php';

$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $requester_name = sanitize($_POST['requester_name']);
    $requester_phone = sanitize($_POST['requester_phone']);
    $requester_email = sanitize($_POST['requester_email']);
    $patient_name = sanitize($_POST['patient_name']);
    $blood_type = sanitize($_POST['blood_type']);
    $units_needed = (int)$_POST['units_needed'];
    $hospital_name = sanitize($_POST['hospital_name']);
    $hospital_address = sanitize($_POST['hospital_address']);
    $city = sanitize($_POST['city']);
    $state = sanitize($_POST['state']);
    $pincode = sanitize($_POST['pincode']);
    $urgency = sanitize($_POST['urgency']);
    $required_date = sanitize($_POST['required_date']);
    $description = sanitize($_POST['description']);
    
    // Validation
    if (empty($requester_name)) {
        $errors[] = "Requester name is required";
    }
    
    if (empty($requester_phone)) {
        $errors[] = "Phone number is required";
    } elseif (!isValidPhone($requester_phone)) {
        $errors[] = "Please enter a valid 10-digit phone number";
    }
    
    if (empty($requester_email)) {
        $errors[] = "Email is required";
    } elseif (!isValidEmail($requester_email)) {
        $errors[] = "Please enter a valid email";
    }
    
    if (empty($patient_name)) {
        $errors[] = "Patient name is required";
    }
    
    if (empty($blood_type)) {
        $errors[] = "Blood type is required";
    }
    
    if ($units_needed < 1 || $units_needed > 10) {
        $errors[] = "Units needed must be between 1 and 10";
    }
    
    if (empty($hospital_name)) {
        $errors[] = "Hospital name is required";
    }
    
    if (empty($required_date)) {
        $errors[] = "Required date is required";
    } elseif (strtotime($required_date) < strtotime('today')) {
        $errors[] = "Required date cannot be in the past";
    }
    
    if (empty($errors)) {
        $db->query("INSERT INTO blood_requests (requester_name, requester_phone, requester_email, patient_name, blood_type, units_needed, hospital_name, hospital_address, city, state, pincode, urgency, required_date, description) 
                   VALUES (:requester_name, :requester_phone, :requester_email, :patient_name, :blood_type, :units_needed, :hospital_name, :hospital_address, :city, :state, :pincode, :urgency, :required_date, :description)");
        
        $db->bind(':requester_name', $requester_name);
        $db->bind(':requester_phone', $requester_phone);
        $db->bind(':requester_email', $requester_email);
        $db->bind(':patient_name', $patient_name);
        $db->bind(':blood_type', $blood_type);
        $db->bind(':units_needed', $units_needed);
        $db->bind(':hospital_name', $hospital_name);
        $db->bind(':hospital_address', $hospital_address);
        $db->bind(':city', $city);
        $db->bind(':state', $state);
        $db->bind(':pincode', $pincode);
        $db->bind(':urgency', $urgency);
        $db->bind(':required_date', $required_date);
        $db->bind(':description', $description);
        
        if ($db->execute()) {
            $success = true;
            setFlashMessage('success', 'Blood request submitted successfully! Donors will be able to see your request and contact you.');
        } else {
            $errors[] = "Failed to submit request. Please try again.";
        }
    }
}

$pageTitle = "Request Blood";
include 'includes/header.php';
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-danger text-white text-center">
                    <h4><i class="fas fa-hand-holding-heart me-2"></i>Request Blood</h4>
                    <p class="mb-0">Fill out this form to request blood from our donor community</p>
                </div>
                <div class="card-body">
                    <?php if ($success): ?>
                        <div class="alert alert-success">
                            <h5><i class="fas fa-check-circle me-2"></i>Request Submitted Successfully!</h5>
                            <p>Your blood request has been posted to our system. Eligible donors in your area will be able to see your request and contact you directly.</p>
                            <p class="mb-0">
                                <a href="search_donors.php?blood_type=<?php echo urlencode($_POST['blood_type']); ?>&city=<?php echo urlencode($_POST['city']); ?>" class="btn btn-success me-2">
                                    <i class="fas fa-search me-1"></i>Find Donors Now
                                </a>
                                <a href="index.php" class="btn btn-outline-primary">Back to Home</a>
                            </p>
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
                            <h5 class="border-bottom pb-2 mb-3">Requester Information</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="requester_name" class="form-label">Your Name *</label>
                                        <input type="text" class="form-control" id="requester_name" name="requester_name" 
                                               value="<?php echo isset($_POST['requester_name']) ? $_POST['requester_name'] : ''; ?>" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="requester_phone" class="form-label">Your Phone Number *</label>
                                        <input type="tel" class="form-control" id="requester_phone" name="requester_phone" 
                                               value="<?php echo isset($_POST['requester_phone']) ? $_POST['requester_phone'] : ''; ?>" required>
                                        <small class="form-text text-muted">Donors will contact you on this number</small>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="requester_email" class="form-label">Your Email Address *</label>
                                <input type="email" class="form-control" id="requester_email" name="requester_email" 
                                       value="<?php echo isset($_POST['requester_email']) ? $_POST['requester_email'] : ''; ?>" required>
                            </div>
                            
                            <h5 class="border-bottom pb-2 mb-3 mt-4">Patient Information</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="patient_name" class="form-label">Patient Name *</label>
                                        <input type="text" class="form-control" id="patient_name" name="patient_name" 
                                               value="<?php echo isset($_POST['patient_name']) ? $_POST['patient_name'] : ''; ?>" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="blood_type" class="form-label">Blood Type Required *</label>
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
                                        <label for="units_needed" class="form-label">Units Needed *</label>
                                        <input type="number" class="form-control" id="units_needed" name="units_needed" min="1" max="10"
                                               value="<?php echo isset($_POST['units_needed']) ? $_POST['units_needed'] : '1'; ?>" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="urgency" class="form-label">Urgency Level *</label>
                                        <select class="form-select" id="urgency" name="urgency" required>
                                            <option value="">Select Urgency</option>
                                            <option value="Low" <?php echo (isset($_POST['urgency']) && $_POST['urgency'] == 'Low') ? 'selected' : ''; ?>>Low</option>
                                            <option value="Medium" <?php echo (isset($_POST['urgency']) && $_POST['urgency'] == 'Medium') ? 'selected' : ''; ?>>Medium</option>
                                            <option value="High" <?php echo (isset($_POST['urgency']) && $_POST['urgency'] == 'High') ? 'selected' : ''; ?>>High</option>
                                            <option value="Emergency" <?php echo (isset($_POST['urgency']) && $_POST['urgency'] == 'Emergency') ? 'selected' : ''; ?>>Emergency</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="required_date" class="form-label">Required Date *</label>
                                        <input type="date" class="form-control" id="required_date" name="required_date" 
                                               value="<?php echo isset($_POST['required_date']) ? $_POST['required_date'] : ''; ?>" required>
                                    </div>
                                </div>
                            </div>
                            
                            <h5 class="border-bottom pb-2 mb-3 mt-4">Hospital Information</h5>
                            <div class="mb-3">
                                <label for="hospital_name" class="form-label">Hospital Name *</label>
                                <input type="text" class="form-control" id="hospital_name" name="hospital_name" 
                                       value="<?php echo isset($_POST['hospital_name']) ? $_POST['hospital_name'] : ''; ?>" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="hospital_address" class="form-label">Hospital Address *</label>
                                <textarea class="form-control" id="hospital_address" name="hospital_address" rows="2" required><?php echo isset($_POST['hospital_address']) ? $_POST['hospital_address'] : ''; ?></textarea>
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
                            
                            <div class="mb-3">
                                <label for="description" class="form-label">Additional Information</label>
                                <textarea class="form-control" id="description" name="description" rows="3" 
                                          placeholder="Any additional details about the requirement..."><?php echo isset($_POST['description']) ? $_POST['description'] : ''; ?></textarea>
                            </div>
                            
                            <div class="alert alert-info">
                                <h6><i class="fas fa-info-circle me-2"></i>Important Information:</h6>
                                <ul class="mb-0">
                                    <li>Your contact information will be visible to eligible donors</li>
                                    <li>Donors will contact you directly to coordinate donation</li>
                                    <li>Please ensure all information is accurate</li>
                                    <li>You can also actively search for donors using our "Find Donors" feature</li>
                                </ul>
                            </div>
                            
                            <div class="d-grid">
                                <button type="submit" class="btn btn-danger btn-lg">
                                    <i class="fas fa-hand-holding-heart me-2"></i>Submit Blood Request
                                </button>
                            </div>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Set minimum date to today
document.getElementById('required_date').min = new Date().toISOString().split('T')[0];
</script>

<?php include 'includes/footer.php'; ?>