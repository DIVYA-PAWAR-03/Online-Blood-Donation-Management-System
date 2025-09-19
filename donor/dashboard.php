<?php
require_once '../includes/config.php';

// Check if user is logged in
if (!isLoggedIn()) {
    redirect('../login.php');
}

$user_id = $_SESSION['user_id'];

// Get user details
$db->query("SELECT * FROM users WHERE id = :user_id");
$db->bind(':user_id', $user_id);
$user = $db->single();

// Get user's donation history
$db->query("SELECT d.*, br.patient_name, br.hospital_name 
           FROM donations d 
           LEFT JOIN blood_requests br ON d.request_id = br.id 
           WHERE d.donor_id = :user_id 
           ORDER BY d.donation_date DESC LIMIT 10");
$db->bind(':user_id', $user_id);
$donations = $db->resultset();

// Get matching blood requests
$compatible_types = getCompatibleBloodTypes($user['blood_type']);
$placeholders = implode(',', array_fill(0, count($compatible_types), '?'));

$db->query("SELECT * FROM blood_requests 
           WHERE status = 'Active' 
           AND blood_type IN ($placeholders) 
           AND city = ? 
           ORDER BY urgency DESC, created_at DESC 
           LIMIT 5");

foreach ($compatible_types as $index => $type) {
    $db->bind($index + 1, $type);
}
$db->bind(count($compatible_types) + 1, $user['city']);
$matching_requests = $db->resultset();

$pageTitle = "Donor Dashboard";
include '../includes/header.php';
?>

<div class="container py-4">
    <div class="row">
        <div class="col-12">
            <h2 class="mb-4">
                <i class="fas fa-tachometer-alt me-2"></i>Donor Dashboard
                <span class="text-muted fs-6">Welcome, <?php echo htmlspecialchars($user['full_name']); ?>!</span>
            </h2>
        </div>
    </div>
    
    <!-- User Status Cards -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card bg-<?php echo $user['is_verified'] ? 'success' : 'warning'; ?> text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title">Verification Status</h6>
                            <h4><?php echo $user['is_verified'] ? 'Verified' : 'Pending'; ?></h4>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-<?php echo $user['is_verified'] ? 'check-circle' : 'clock'; ?> fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 mb-3">
            <div class="card bg-<?php echo $user['is_available'] ? 'primary' : 'secondary'; ?> text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title">Availability</h6>
                            <h4><?php echo $user['is_available'] ? 'Available' : 'Not Available'; ?></h4>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-<?php echo $user['is_available'] ? 'heart' : 'heart-broken'; ?> fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 mb-3">
            <div class="card bg-danger text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title">Blood Type</h6>
                            <h4><?php echo $user['blood_type']; ?></h4>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-tint fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 mb-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title">Total Donations</h6>
                            <h4><?php echo count($donations); ?></h4>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-award fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <?php if (!$user['is_verified']): ?>
    <div class="alert alert-warning">
        <h5><i class="fas fa-exclamation-triangle me-2"></i>Account Verification Pending</h5>
        <p class="mb-0">Your account is currently under review by our admin team. You'll be able to participate in blood donation once verified.</p>
    </div>
    <?php endif; ?>
    
    <div class="row">
        <!-- Matching Blood Requests -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-danger text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-hand-holding-heart me-2"></i>
                        Blood Requests Matching Your Type (<?php echo $user['blood_type']; ?>)
                    </h5>
                </div>
                <div class="card-body">
                    <?php if (empty($matching_requests)): ?>
                        <p class="text-muted text-center">No matching blood requests in your area at the moment.</p>
                    <?php else: ?>
                        <?php foreach ($matching_requests as $request): ?>
                            <div class="border-bottom py-3">
                                <div class="row">
                                    <div class="col-md-8">
                                        <h6 class="mb-2">
                                            <span class="badge bg-danger me-2"><?php echo $request['blood_type']; ?></span>
                                            <?php echo htmlspecialchars($request['patient_name']); ?>
                                            <span class="badge bg-<?php echo $request['urgency'] == 'Emergency' ? 'danger' : ($request['urgency'] == 'High' ? 'warning' : 'info'); ?> ms-2"><?php echo $request['urgency']; ?></span>
                                        </h6>
                                        <p class="text-muted mb-1">
                                            <i class="fas fa-hospital me-1"></i><?php echo htmlspecialchars($request['hospital_name']); ?>
                                        </p>
                                        <p class="text-muted mb-1">
                                            <i class="fas fa-map-marker-alt me-1"></i><?php echo htmlspecialchars($request['city'] . ', ' . $request['state']); ?>
                                        </p>
                                        <p class="text-muted mb-0">
                                            <i class="fas fa-vials me-1"></i><?php echo $request['units_needed']; ?> Unit(s) needed by <?php echo formatDate($request['required_date'], 'd M Y'); ?>
                                        </p>
                                    </div>
                                    <div class="col-md-4 text-end">
                                        <p class="mb-2"><strong>Contact:</strong><br><?php echo htmlspecialchars($request['requester_phone']); ?></p>
                                        <a href="tel:<?php echo $request['requester_phone']; ?>" class="btn btn-sm btn-outline-danger">
                                            <i class="fas fa-phone me-1"></i>Call
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                        <div class="text-center mt-3">
                            <a href="../search_donors.php" class="btn btn-danger">View All Requests</a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <!-- Quick Actions -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-bolt me-2"></i>Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="profile.php" class="btn btn-outline-primary">
                            <i class="fas fa-user me-2"></i>Edit Profile
                        </a>
                        <a href="../search_donors.php" class="btn btn-outline-danger">
                            <i class="fas fa-search me-2"></i>Find Blood Requests
                        </a>
                        <a href="availability.php" class="btn btn-outline-success">
                            <i class="fas fa-toggle-on me-2"></i>Update Availability
                        </a>
                        <a href="donation_history.php" class="btn btn-outline-info">
                            <i class="fas fa-history me-2"></i>Donation History
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Last Donation Info -->
            <?php if ($user['last_donation_date']): ?>
            <div class="card mt-3">
                <div class="card-header">
                    <h6 class="mb-0"><i class="fas fa-calendar me-2"></i>Last Donation</h6>
                </div>
                <div class="card-body">
                    <p class="mb-1"><strong>Date:</strong> <?php echo formatDate($user['last_donation_date'], 'd M Y'); ?></p>
                    <?php
                    $next_eligible = date('d M Y', strtotime($user['last_donation_date'] . ' + 56 days'));
                    $is_eligible = strtotime($user['last_donation_date'] . ' + 56 days') <= time();
                    ?>
                    <p class="mb-0">
                        <strong>Next Eligible:</strong> 
                        <span class="badge bg-<?php echo $is_eligible ? 'success' : 'warning'; ?>">
                            <?php echo $is_eligible ? 'Now' : $next_eligible; ?>
                        </span>
                    </p>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
    
    <!-- Recent Donation History -->
    <?php if (!empty($donations)): ?>
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-history me-2"></i>Recent Donations</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Patient</th>
                                    <th>Hospital</th>
                                    <th>Units</th>
                                    <th>Notes</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach (array_slice($donations, 0, 5) as $donation): ?>
                                <tr>
                                    <td><?php echo formatDate($donation['donation_date'], 'd M Y'); ?></td>
                                    <td><?php echo $donation['patient_name'] ? htmlspecialchars($donation['patient_name']) : 'General Donation'; ?></td>
                                    <td><?php echo $donation['hospital_name'] ? htmlspecialchars($donation['hospital_name']) : htmlspecialchars($donation['donation_center']); ?></td>
                                    <td><span class="badge bg-danger"><?php echo $donation['units_donated']; ?></span></td>
                                    <td><?php echo $donation['notes'] ? htmlspecialchars($donation['notes']) : '-'; ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php if (count($donations) > 5): ?>
                    <div class="text-center">
                        <a href="donation_history.php" class="btn btn-outline-primary">View All Donations</a>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>

<?php include '../includes/footer.php'; ?>