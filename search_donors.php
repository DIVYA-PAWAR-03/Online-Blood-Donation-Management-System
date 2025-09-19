<?php
require_once 'includes/config.php';

// Get search parameters
$blood_type = isset($_GET['blood_type']) ? sanitize($_GET['blood_type']) : '';
$city = isset($_GET['city']) ? sanitize($_GET['city']) : '';
$state = isset($_GET['state']) ? sanitize($_GET['state']) : '';

// Build search query
$where_conditions = ["is_verified = 1", "is_available = 1"];
$params = [];

if (!empty($blood_type)) {
    $where_conditions[] = "blood_type = ?";
    $params[] = $blood_type;
}

if (!empty($city)) {
    $where_conditions[] = "city LIKE ?";
    $params[] = "%$city%";
}

if (!empty($state)) {
    $where_conditions[] = "state LIKE ?";
    $params[] = "%$state%";
}

$where_clause = implode(' AND ', $where_conditions);

// Get donors
$db->query("SELECT id, username, full_name, blood_type, city, state, phone, last_donation_date, created_at 
           FROM users 
           WHERE $where_clause 
           ORDER BY city, created_at DESC");

foreach ($params as $index => $param) {
    $db->bind($index + 1, $param);
}

$donors = $db->resultset();

// Get unique cities and states for filters
$db->query("SELECT DISTINCT city FROM users WHERE is_verified = 1 ORDER BY city");
$cities = $db->resultset();

$db->query("SELECT DISTINCT state FROM users WHERE is_verified = 1 ORDER BY state");
$states = $db->resultset();

$pageTitle = "Find Blood Donors";
include 'includes/header.php';
?>

<div class="container py-4">
    <!-- Search Form -->
    <div class="card mb-4">
        <div class="card-header bg-danger text-white">
            <h5 class="mb-0"><i class="fas fa-search me-2"></i>Search Blood Donors</h5>
        </div>
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-4">
                    <label for="blood_type" class="form-label">Blood Type</label>
                    <select class="form-select" id="blood_type" name="blood_type">
                        <option value="">All Blood Types</option>
                        <option value="A+" <?php echo ($blood_type == 'A+') ? 'selected' : ''; ?>>A+</option>
                        <option value="A-" <?php echo ($blood_type == 'A-') ? 'selected' : ''; ?>>A-</option>
                        <option value="B+" <?php echo ($blood_type == 'B+') ? 'selected' : ''; ?>>B+</option>
                        <option value="B-" <?php echo ($blood_type == 'B-') ? 'selected' : ''; ?>>B-</option>
                        <option value="AB+" <?php echo ($blood_type == 'AB+') ? 'selected' : ''; ?>>AB+</option>
                        <option value="AB-" <?php echo ($blood_type == 'AB-') ? 'selected' : ''; ?>>AB-</option>
                        <option value="O+" <?php echo ($blood_type == 'O+') ? 'selected' : ''; ?>>O+</option>
                        <option value="O-" <?php echo ($blood_type == 'O-') ? 'selected' : ''; ?>>O-</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="city" class="form-label">City</label>
                    <input type="text" class="form-control" id="city" name="city" 
                           value="<?php echo htmlspecialchars($city); ?>" 
                           placeholder="Enter city name">
                </div>
                <div class="col-md-4">
                    <label for="state" class="form-label">State</label>
                    <input type="text" class="form-control" id="state" name="state" 
                           value="<?php echo htmlspecialchars($state); ?>" 
                           placeholder="Enter state name">
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-search me-2"></i>Search Donors
                    </button>
                    <a href="search_donors.php" class="btn btn-outline-secondary ms-2">
                        <i class="fas fa-undo me-2"></i>Clear Filters
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Search Results -->
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4>
                    <i class="fas fa-users me-2"></i>Available Donors
                    <?php if (!empty($blood_type) || !empty($city) || !empty($state)): ?>
                        <small class="text-muted">
                            (<?php echo count($donors); ?> results found
                            <?php if (!empty($blood_type)): ?>for <?php echo $blood_type; ?> blood type<?php endif; ?>
                            <?php if (!empty($city)): ?>in <?php echo htmlspecialchars($city); ?><?php endif; ?>)
                        </small>
                    <?php endif; ?>
                </h4>
                <a href="request_blood.php" class="btn btn-outline-danger">
                    <i class="fas fa-hand-holding-heart me-2"></i>Request Blood
                </a>
            </div>

            <?php if (empty($donors)): ?>
                <div class="card">
                    <div class="card-body text-center py-5">
                        <i class="fas fa-user-friends text-muted mb-3" style="font-size: 4rem;"></i>
                        <h5 class="text-muted">No Donors Found</h5>
                        <p class="text-muted">No verified donors match your search criteria. Try adjusting your filters or check back later.</p>
                        <div class="mt-3">
                            <a href="search_donors.php" class="btn btn-danger me-2">Search All Donors</a>
                            <a href="request_blood.php" class="btn btn-outline-primary">Post Blood Request</a>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <div class="row">
                    <?php foreach ($donors as $donor): ?>
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="card h-100">
                                <div class="card-header bg-light">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h6 class="mb-0"><?php echo htmlspecialchars($donor['full_name']); ?></h6>
                                        <span class="badge bg-danger"><?php echo $donor['blood_type']; ?></span>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="mb-2">
                                        <i class="fas fa-map-marker-alt text-muted me-2"></i>
                                        <small><?php echo htmlspecialchars($donor['city'] . ', ' . $donor['state']); ?></small>
                                    </div>
                                    
                                    <?php if ($donor['last_donation_date']): ?>
                                        <?php
                                        $last_donation = strtotime($donor['last_donation_date']);
                                        $next_eligible = strtotime($donor['last_donation_date'] . ' + 56 days');
                                        $is_eligible = $next_eligible <= time();
                                        ?>
                                        <div class="mb-2">
                                            <i class="fas fa-calendar text-muted me-2"></i>
                                            <small>Last donated: <?php echo formatDate($donor['last_donation_date'], 'd M Y'); ?></small>
                                        </div>
                                        <div class="mb-3">
                                            <span class="badge bg-<?php echo $is_eligible ? 'success' : 'warning'; ?>">
                                                <?php echo $is_eligible ? 'Eligible to donate' : 'Next eligible: ' . date('d M Y', $next_eligible); ?>
                                            </span>
                                        </div>
                                    <?php else: ?>
                                        <div class="mb-3">
                                            <span class="badge bg-success">Eligible to donate</span>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <div class="mb-2">
                                        <small class="text-muted">
                                            <i class="fas fa-user-check me-1"></i>
                                            Donor since <?php echo formatDate($donor['created_at'], 'M Y'); ?>
                                        </small>
                                    </div>
                                </div>
                                <div class="card-footer bg-transparent">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <a href="tel:<?php echo $donor['phone']; ?>" class="btn btn-sm btn-success">
                                            <i class="fas fa-phone me-1"></i>Call
                                        </a>
                                        <button class="btn btn-sm btn-outline-primary" onclick="showDonorContact('<?php echo htmlspecialchars($donor['full_name']); ?>', '<?php echo $donor['phone']; ?>')">
                                            <i class="fas fa-info-circle me-1"></i>Contact Info
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                
                <div class="text-center mt-4">
                    <p class="text-muted">
                        Found <?php echo count($donors); ?> available donors. 
                        Contact donors directly to coordinate blood donation.
                    </p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Blood Type Compatibility Info -->
    <div class="row mt-5">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h6 class="mb-0"><i class="fas fa-info-circle me-2"></i>Blood Donation Compatibility</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Universal Donors & Recipients:</h6>
                            <ul class="mb-0">
                                <li><strong>O-:</strong> Universal donor (can donate to everyone)</li>
                                <li><strong>AB+:</strong> Universal recipient (can receive from everyone)</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h6>Donation Frequency:</h6>
                            <ul class="mb-0">
                                <li>Donors can donate every <strong>56 days</strong> (8 weeks)</li>
                                <li>Must be 18-65 years old and weigh at least 50kg</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Contact Info Modal -->
<div class="modal fade" id="contactModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-user me-2"></i>Donor Contact Information</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <h6 id="donorName"></h6>
                <p class="mb-2">
                    <strong>Phone:</strong> <span id="donorPhone"></span>
                    <a href="#" id="donorPhoneLink" class="btn btn-sm btn-success ms-2">
                        <i class="fas fa-phone me-1"></i>Call Now
                    </a>
                </p>
                <div class="alert alert-info">
                    <small>
                        <i class="fas fa-info-circle me-1"></i>
                        Please be respectful when contacting donors. Clearly explain your blood requirement and coordinate donation details.
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function showDonorContact(name, phone) {
    document.getElementById('donorName').textContent = name;
    document.getElementById('donorPhone').textContent = phone;
    document.getElementById('donorPhoneLink').href = 'tel:' + phone;
    
    var modal = new bootstrap.Modal(document.getElementById('contactModal'));
    modal.show();
}
</script>

<?php include 'includes/footer.php'; ?>