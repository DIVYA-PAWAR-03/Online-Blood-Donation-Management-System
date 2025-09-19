<?php
require_once 'includes/config.php';

// Get recent blood requests
$db->query("SELECT * FROM blood_requests WHERE status = 'Active' ORDER BY urgency DESC, created_at DESC LIMIT 6");
$recent_requests = $db->resultset();

// Get donor statistics
$db->query("SELECT COUNT(*) as total_donors FROM users WHERE is_verified = 1");
$donor_stats = $db->single();

$db->query("SELECT COUNT(*) as total_requests FROM blood_requests WHERE status = 'Active'");
$request_stats = $db->single();

$db->query("SELECT COUNT(*) as completed_donations FROM donations");
$donation_stats = $db->single();

$pageTitle = "Home";
include 'includes/header.php';
?>

<!-- Hero Section -->
<section class="hero-section bg-danger text-white py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="display-4 fw-bold mb-4">Save Lives, Donate Blood</h1>
                <p class="lead mb-4">Join our community of life-savers. Every drop counts in making a difference in someone's life.</p>
                <div class="d-flex gap-3">
                    <a href="register.php" class="btn btn-light btn-lg">
                        <i class="fas fa-heart me-2"></i>Become a Donor
                    </a>
                    <a href="request_blood.php" class="btn btn-outline-light btn-lg">
                        <i class="fas fa-hand-holding-heart me-2"></i>Request Blood
                    </a>
                </div>
            </div>
            <div class="col-md-6 text-center">
                <i class="fas fa-tint" style="font-size: 15rem; opacity: 0.3;"></i>
            </div>
        </div>
    </div>
</section>

<!-- Statistics Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row text-center">
            <div class="col-md-4 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <i class="fas fa-users text-danger mb-3" style="font-size: 3rem;"></i>
                        <h3 class="text-danger"><?php echo number_format($donor_stats['total_donors']); ?></h3>
                        <p class="text-muted">Registered Donors</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <i class="fas fa-hand-holding-heart text-danger mb-3" style="font-size: 3rem;"></i>
                        <h3 class="text-danger"><?php echo number_format($request_stats['total_requests']); ?></h3>
                        <p class="text-muted">Active Requests</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <i class="fas fa-tint text-danger mb-3" style="font-size: 3rem;"></i>
                        <h3 class="text-danger"><?php echo number_format($donation_stats['completed_donations']); ?></h3>
                        <p class="text-muted">Lives Saved</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Blood Request Section -->
<section class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h2 class="text-center mb-5">
                    <i class="fas fa-exclamation-triangle text-danger me-2"></i>
                    Urgent Blood Requests
                </h2>
            </div>
        </div>
        
        <?php if (empty($recent_requests)): ?>
            <div class="text-center">
                <p class="text-muted">No active blood requests at the moment.</p>
            </div>
        <?php else: ?>
            <div class="row">
                <?php foreach ($recent_requests as $request): ?>
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card h-100 shadow-sm">
                            <div class="card-header bg-<?php echo $request['urgency'] == 'Emergency' ? 'danger' : ($request['urgency'] == 'High' ? 'warning' : 'info'); ?> text-white">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="fw-bold"><?php echo $request['blood_type']; ?> Blood Needed</span>
                                    <span class="badge bg-light text-dark"><?php echo $request['urgency']; ?></span>
                                </div>
                            </div>
                            <div class="card-body">
                                <h6 class="card-title"><?php echo htmlspecialchars($request['patient_name']); ?></h6>
                                <p class="card-text">
                                    <i class="fas fa-hospital me-2"></i><?php echo htmlspecialchars($request['hospital_name']); ?><br>
                                    <i class="fas fa-map-marker-alt me-2"></i><?php echo htmlspecialchars($request['city'] . ', ' . $request['state']); ?><br>
                                    <i class="fas fa-vials me-2"></i><?php echo $request['units_needed']; ?> Unit(s) needed<br>
                                    <i class="fas fa-calendar me-2"></i>Required by: <?php echo formatDate($request['required_date'], 'd M Y'); ?>
                                </p>
                                <?php if (!empty($request['description'])): ?>
                                    <p class="card-text"><small class="text-muted"><?php echo htmlspecialchars($request['description']); ?></small></p>
                                <?php endif; ?>
                            </div>
                            <div class="card-footer bg-transparent">
                                <div class="d-flex justify-content-between align-items-center">
                                    <small class="text-muted">Contact: <?php echo htmlspecialchars($request['requester_phone']); ?></small>
                                    <a href="search_donors.php?blood_type=<?php echo $request['blood_type']; ?>&city=<?php echo urlencode($request['city']); ?>" 
                                       class="btn btn-sm btn-outline-danger">Find Donors</a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <div class="text-center mt-4">
                <a href="request_blood.php" class="btn btn-danger btn-lg">
                    <i class="fas fa-plus me-2"></i>Post Blood Request
                </a>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- How It Works Section -->
<section class="py-5 bg-light">
    <div class="container">
        <h2 class="text-center mb-5">How It Works</h2>
        <div class="row">
            <div class="col-md-3 text-center mb-4">
                <div class="step-icon bg-danger text-white rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                    <i class="fas fa-user-plus fa-2x"></i>
                </div>
                <h5>1. Register</h5>
                <p class="text-muted">Sign up as a blood donor with your details and get verified by our admin team.</p>
            </div>
            <div class="col-md-3 text-center mb-4">
                <div class="step-icon bg-danger text-white rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                    <i class="fas fa-search fa-2x"></i>
                </div>
                <h5>2. Find Matches</h5>
                <p class="text-muted">Search for blood requests in your area that match your blood type.</p>
            </div>
            <div class="col-md-3 text-center mb-4">
                <div class="step-icon bg-danger text-white rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                    <i class="fas fa-phone fa-2x"></i>
                </div>
                <h5>3. Connect</h5>
                <p class="text-muted">Contact the requester directly to coordinate donation details.</p>
            </div>
            <div class="col-md-3 text-center mb-4">
                <div class="step-icon bg-danger text-white rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                    <i class="fas fa-heart fa-2x"></i>
                </div>
                <h5>4. Save Lives</h5>
                <p class="text-muted">Donate blood and make a life-saving difference in someone's life.</p>
            </div>
        </div>
    </div>
</section>

<!-- Blood Type Compatibility -->
<section class="py-5">
    <div class="container">
        <h2 class="text-center mb-5">Blood Type Compatibility</h2>
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="table-responsive">
                    <table class="table table-bordered text-center">
                        <thead class="bg-danger text-white">
                            <tr>
                                <th>Blood Type</th>
                                <th>Can Donate To</th>
                                <th>Can Receive From</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="fw-bold">O-</td>
                                <td>Everyone (Universal Donor)</td>
                                <td>O-</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">O+</td>
                                <td>O+, A+, B+, AB+</td>
                                <td>O-, O+</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">A-</td>
                                <td>A-, A+, AB-, AB+</td>
                                <td>O-, A-</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">A+</td>
                                <td>A+, AB+</td>
                                <td>O-, O+, A-, A+</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">B-</td>
                                <td>B-, B+, AB-, AB+</td>
                                <td>O-, B-</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">B+</td>
                                <td>B+, AB+</td>
                                <td>O-, O+, B-, B+</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">AB-</td>
                                <td>AB-, AB+</td>
                                <td>O-, A-, B-, AB-</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">AB+</td>
                                <td>AB+</td>
                                <td>Everyone (Universal Recipient)</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>