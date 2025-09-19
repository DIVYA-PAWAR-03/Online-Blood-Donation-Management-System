<?php
require_once '../includes/config.php';

// Check if admin is logged in
if (!isAdminLoggedIn()) {
    redirect('login.php');
}

// Get statistics
$db->query("SELECT COUNT(*) as total_users FROM users");
$total_users = $db->single()['total_users'];

$db->query("SELECT COUNT(*) as verified_users FROM users WHERE is_verified = 1");
$verified_users = $db->single()['verified_users'];

$db->query("SELECT COUNT(*) as pending_users FROM users WHERE is_verified = 0");
$pending_users = $db->single()['pending_users'];

$db->query("SELECT COUNT(*) as total_requests FROM blood_requests");
$total_requests = $db->single()['total_requests'];

$db->query("SELECT COUNT(*) as active_requests FROM blood_requests WHERE status = 'Active'");
$active_requests = $db->single()['active_requests'];

$db->query("SELECT COUNT(*) as total_donations FROM donations");
$total_donations = $db->single()['total_donations'];

// Get recent registrations
$db->query("SELECT id, full_name, email, blood_type, city, created_at, is_verified 
           FROM users 
           ORDER BY created_at DESC 
           LIMIT 5");
$recent_users = $db->resultset();

// Get recent blood requests
$db->query("SELECT id, patient_name, blood_type, city, urgency, created_at, status 
           FROM blood_requests 
           ORDER BY created_at DESC 
           LIMIT 5");
$recent_requests = $db->resultset();

// Get blood type statistics
$db->query("SELECT blood_type, COUNT(*) as count 
           FROM users 
           WHERE is_verified = 1 
           GROUP BY blood_type 
           ORDER BY count DESC");
$blood_type_stats = $db->resultset();

$pageTitle = "Admin Dashboard";
include '../includes/header.php';
?>

<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <h2 class="mb-4">
                <i class="fas fa-tachometer-alt me-2"></i>Admin Dashboard
                <span class="text-muted fs-6">Welcome, <?php echo $_SESSION['admin_name']; ?>!</span>
            </h2>
        </div>
    </div>
    
    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-2 mb-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title">Total Users</h6>
                            <h3><?php echo $total_users; ?></h3>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-users fa-2x"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-primary border-0">
                    <a href="manage_donors.php" class="text-white text-decoration-none">
                        <small>View All <i class="fas fa-arrow-right"></i></small>
                    </a>
                </div>
            </div>
        </div>
        
        <div class="col-md-2 mb-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title">Verified</h6>
                            <h3><?php echo $verified_users; ?></h3>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-user-check fa-2x"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-success border-0">
                    <a href="manage_donors.php?status=verified" class="text-white text-decoration-none">
                        <small>View All <i class="fas fa-arrow-right"></i></small>
                    </a>
                </div>
            </div>
        </div>
        
        <div class="col-md-2 mb-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title">Pending</h6>
                            <h3><?php echo $pending_users; ?></h3>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-clock fa-2x"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-warning border-0">
                    <a href="manage_donors.php?status=pending" class="text-white text-decoration-none">
                        <small>Verify Now <i class="fas fa-arrow-right"></i></small>
                    </a>
                </div>
            </div>
        </div>
        
        <div class="col-md-2 mb-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title">Blood Requests</h6>
                            <h3><?php echo $total_requests; ?></h3>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-hand-holding-heart fa-2x"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-info border-0">
                    <a href="manage_requests.php" class="text-white text-decoration-none">
                        <small>Manage <i class="fas fa-arrow-right"></i></small>
                    </a>
                </div>
            </div>
        </div>
        
        <div class="col-md-2 mb-3">
            <div class="card bg-danger text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title">Active Requests</h6>
                            <h3><?php echo $active_requests; ?></h3>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-exclamation-circle fa-2x"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-danger border-0">
                    <a href="manage_requests.php?status=active" class="text-white text-decoration-none">
                        <small>View Active <i class="fas fa-arrow-right"></i></small>
                    </a>
                </div>
            </div>
        </div>
        
        <div class="col-md-2 mb-3">
            <div class="card bg-dark text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title">Donations</h6>
                            <h3><?php echo $total_donations; ?></h3>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-award fa-2x"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-dark border-0">
                    <a href="manage_donations.php" class="text-white text-decoration-none">
                        <small>View All <i class="fas fa-arrow-right"></i></small>
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <!-- Recent User Registrations -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-user-plus me-2"></i>Recent Registrations</h5>
                </div>
                <div class="card-body">
                    <?php if (empty($recent_users)): ?>
                        <p class="text-muted text-center">No recent registrations</p>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Blood Type</th>
                                        <th>City</th>
                                        <th>Status</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($recent_users as $user): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($user['full_name']); ?></td>
                                        <td><span class="badge bg-danger"><?php echo $user['blood_type']; ?></span></td>
                                        <td><?php echo htmlspecialchars($user['city']); ?></td>
                                        <td>
                                            <span class="badge bg-<?php echo $user['is_verified'] ? 'success' : 'warning'; ?>">
                                                <?php echo $user['is_verified'] ? 'Verified' : 'Pending'; ?>
                                            </span>
                                        </td>
                                        <td><?php echo formatDate($user['created_at'], 'd M'); ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="text-center">
                            <a href="manage_donors.php" class="btn btn-sm btn-outline-primary">View All Users</a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <!-- Recent Blood Requests -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-hand-holding-heart me-2"></i>Recent Blood Requests</h5>
                </div>
                <div class="card-body">
                    <?php if (empty($recent_requests)): ?>
                        <p class="text-muted text-center">No recent requests</p>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Patient</th>
                                        <th>Blood Type</th>
                                        <th>City</th>
                                        <th>Urgency</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($recent_requests as $request): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($request['patient_name']); ?></td>
                                        <td><span class="badge bg-danger"><?php echo $request['blood_type']; ?></span></td>
                                        <td><?php echo htmlspecialchars($request['city']); ?></td>
                                        <td>
                                            <span class="badge bg-<?php echo $request['urgency'] == 'Emergency' ? 'danger' : ($request['urgency'] == 'High' ? 'warning' : 'info'); ?>">
                                                <?php echo $request['urgency']; ?>
                                            </span>
                                        </td>
                                        <td><?php echo formatDate($request['created_at'], 'd M'); ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="text-center">
                            <a href="manage_requests.php" class="btn btn-sm btn-outline-primary">View All Requests</a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Blood Type Distribution -->
    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-chart-pie me-2"></i>Blood Type Distribution</h5>
                </div>
                <div class="card-body">
                    <?php if (empty($blood_type_stats)): ?>
                        <p class="text-muted text-center">No donor data available</p>
                    <?php else: ?>
                        <?php foreach ($blood_type_stats as $stat): ?>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div>
                                    <span class="badge bg-danger me-2"><?php echo $stat['blood_type']; ?></span>
                                    <span><?php echo $stat['count']; ?> donors</span>
                                </div>
                                <div class="progress" style="width: 60%; height: 8px;">
                                    <div class="progress-bar bg-danger" style="width: <?php echo ($stat['count'] / $verified_users) * 100; ?>%"></div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <!-- Quick Actions -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-bolt me-2"></i>Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="manage_donors.php?status=pending" class="btn btn-warning">
                            <i class="fas fa-user-check me-2"></i>Verify Pending Donors (<?php echo $pending_users; ?>)
                        </a>
                        <a href="manage_requests.php?status=active" class="btn btn-danger">
                            <i class="fas fa-hand-holding-heart me-2"></i>View Active Requests (<?php echo $active_requests; ?>)
                        </a>
                        <a href="manage_donors.php" class="btn btn-primary">
                            <i class="fas fa-users me-2"></i>Manage All Donors
                        </a>
                        <a href="reports.php" class="btn btn-info">
                            <i class="fas fa-chart-bar me-2"></i>Generate Reports
                        </a>
                        <a href="settings.php" class="btn btn-secondary">
                            <i class="fas fa-cog me-2"></i>System Settings
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Quick Access Menu -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-th-large me-2"></i>Admin Menu</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 mb-2">
                            <a href="manage_donors.php" class="btn btn-outline-primary w-100">
                                <i class="fas fa-users d-block mb-2" style="font-size: 2rem;"></i>
                                Manage Donors
                            </a>
                        </div>
                        <div class="col-md-3 mb-2">
                            <a href="manage_requests.php" class="btn btn-outline-danger w-100">
                                <i class="fas fa-hand-holding-heart d-block mb-2" style="font-size: 2rem;"></i>
                                Blood Requests
                            </a>
                        </div>
                        <div class="col-md-3 mb-2">
                            <a href="manage_donations.php" class="btn btn-outline-success w-100">
                                <i class="fas fa-award d-block mb-2" style="font-size: 2rem;"></i>
                                Donations
                            </a>
                        </div>
                        <div class="col-md-3 mb-2">
                            <a href="reports.php" class="btn btn-outline-info w-100">
                                <i class="fas fa-chart-bar d-block mb-2" style="font-size: 2rem;"></i>
                                Reports
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>