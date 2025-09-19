<?php
require_once 'includes/config.php';

$pageTitle = "About Us";
include 'includes/header.php';
?>

<div class="container py-5">
    <div class="row">
        <div class="col-12">
            <h2 class="text-center mb-5">About Blood Donation Management System</h2>
        </div>
    </div>
    
    <div class="row mb-5">
        <div class="col-md-6">
            <h3>Our Mission</h3>
            <p class="lead">To create a transparent and accessible platform that connects blood donors with those in need, making the process of finding and donating blood as efficient and life-saving as possible.</p>
            
            <h4>What We Do</h4>
            <ul>
                <li>Connect verified blood donors with patients in need</li>
                <li>Provide a secure platform for blood requests</li>
                <li>Maintain a database of available donors by location and blood type</li>
                <li>Facilitate direct communication between donors and requesters</li>
                <li>Track donation history and eligibility</li>
            </ul>
        </div>
        <div class="col-md-6">
            <div class="card bg-light">
                <div class="card-body">
                    <h5>Why Blood Donation Matters</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><i class="fas fa-heart text-danger me-2"></i>Every 2 seconds, someone needs blood</li>
                        <li class="mb-2"><i class="fas fa-users text-danger me-2"></i>1 donation can save up to 3 lives</li>
                        <li class="mb-2"><i class="fas fa-clock text-danger me-2"></i>Blood has a limited shelf life</li>
                        <li class="mb-2"><i class="fas fa-hospital text-danger me-2"></i>Hospitals need constant supply</li>
                        <li class="mb-2"><i class="fas fa-ambulance text-danger me-2"></i>Emergency situations require immediate access</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row mb-5">
        <div class="col-12">
            <h3 class="text-center mb-4">How Our System Works</h3>
        </div>
        <div class="col-md-3 text-center mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <i class="fas fa-user-plus fa-3x text-danger mb-3"></i>
                    <h5>Register</h5>
                    <p>Donors register with their details and get verified by our admin team for authenticity and eligibility.</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 text-center mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <i class="fas fa-hand-holding-heart fa-3x text-danger mb-3"></i>
                    <h5>Request</h5>
                    <p>Patients or hospitals can post blood requests with specific requirements and urgency levels.</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 text-center mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <i class="fas fa-search fa-3x text-danger mb-3"></i>
                    <h5>Match</h5>
                    <p>Our system matches donors with requests based on blood type, location, and availability.</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 text-center mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <i class="fas fa-phone fa-3x text-danger mb-3"></i>
                    <h5>Connect</h5>
                    <p>Direct communication between donors and requesters to coordinate donation details.</p>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row mb-5">
        <div class="col-md-8 mx-auto">
            <div class="card">
                <div class="card-header bg-danger text-white">
                    <h5 class="mb-0 text-center">Blood Donation Eligibility Criteria</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6>General Requirements:</h6>
                            <ul>
                                <li>Age: 18-65 years</li>
                                <li>Weight: Minimum 50 kg</li>
                                <li>Good general health</li>
                                <li>Normal blood pressure</li>
                                <li>Normal pulse rate</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h6>Donation Frequency:</h6>
                            <ul>
                                <li>Whole blood: Every 56 days</li>
                                <li>Plasma: Every 28 days</li>
                                <li>Platelets: Every 7 days</li>
                                <li>Double red cells: Every 112 days</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-12 text-center">
            <h3>Join Our Life-Saving Community</h3>
            <p class="lead">Every donation counts. Every donor matters. Every life saved is priceless.</p>
            <div class="mt-4">
                <a href="register.php" class="btn btn-danger btn-lg me-3">
                    <i class="fas fa-heart me-2"></i>Become a Donor
                </a>
                <a href="request_blood.php" class="btn btn-outline-danger btn-lg">
                    <i class="fas fa-hand-holding-heart me-2"></i>Request Blood
                </a>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>