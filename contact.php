<?php
require_once 'includes/config.php';

$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = sanitize($_POST['name']);
    $email = sanitize($_POST['email']);
    $subject = sanitize($_POST['subject']);
    $message = sanitize($_POST['message']);
    
    // Validation
    if (empty($name)) {
        $errors[] = "Name is required";
    }
    
    if (empty($email)) {
        $errors[] = "Email is required";
    } elseif (!isValidEmail($email)) {
        $errors[] = "Please enter a valid email";
    }
    
    if (empty($subject)) {
        $errors[] = "Subject is required";
    }
    
    if (empty($message)) {
        $errors[] = "Message is required";
    }
    
    if (empty($errors)) {
        $db->query("INSERT INTO contact_messages (name, email, subject, message) VALUES (:name, :email, :subject, :message)");
        $db->bind(':name', $name);
        $db->bind(':email', $email);
        $db->bind(':subject', $subject);
        $db->bind(':message', $message);
        
        if ($db->execute()) {
            $success = true;
            setFlashMessage('success', 'Thank you for your message! We will get back to you soon.');
        } else {
            $errors[] = "Failed to send message. Please try again.";
        }
    }
}

$pageTitle = "Contact Us";
include 'includes/header.php';
?>

<div class="container py-5">
    <div class="row">
        <div class="col-12">
            <h2 class="text-center mb-5">Contact Us</h2>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-danger text-white">
                    <h5 class="mb-0"><i class="fas fa-envelope me-2"></i>Send us a Message</h5>
                </div>
                <div class="card-body">
                    <?php if ($success): ?>
                        <div class="alert alert-success">
                            <h5><i class="fas fa-check-circle me-2"></i>Message Sent Successfully!</h5>
                            <p class="mb-0">Thank you for contacting us. We will review your message and get back to you as soon as possible.</p>
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
                                        <label for="name" class="form-label">Full Name *</label>
                                        <input type="text" class="form-control" id="name" name="name" 
                                               value="<?php echo isset($_POST['name']) ? $_POST['name'] : ''; ?>" required>
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
                            
                            <div class="mb-3">
                                <label for="subject" class="form-label">Subject *</label>
                                <input type="text" class="form-control" id="subject" name="subject" 
                                       value="<?php echo isset($_POST['subject']) ? $_POST['subject'] : ''; ?>" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="message" class="form-label">Message *</label>
                                <textarea class="form-control" id="message" name="message" rows="6" required><?php echo isset($_POST['message']) ? $_POST['message'] : ''; ?></textarea>
                            </div>
                            
                            <div class="d-grid">
                                <button type="submit" class="btn btn-danger">
                                    <i class="fas fa-paper-plane me-2"></i>Send Message
                                </button>
                            </div>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Contact Information</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <h6><i class="fas fa-envelope text-danger me-2"></i>Email</h6>
                        <p><?php echo ADMIN_EMAIL; ?></p>
                    </div>
                    
                    <div class="mb-3">
                        <h6><i class="fas fa-phone text-danger me-2"></i>Phone</h6>
                        <p>+91 1234567890</p>
                    </div>
                    
                    <div class="mb-3">
                        <h6><i class="fas fa-map-marker-alt text-danger me-2"></i>Address</h6>
                        <p>123 Health Street<br>Medical City, State 12345</p>
                    </div>
                    
                    <div class="mb-3">
                        <h6><i class="fas fa-clock text-danger me-2"></i>Response Time</h6>
                        <p>We typically respond within 24 hours during business days.</p>
                    </div>
                </div>
            </div>
            
            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-question-circle me-2"></i>Frequently Asked</h5>
                </div>
                <div class="card-body">
                    <div class="accordion" id="faqAccordion">
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="faq1">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse1">
                                    How do I register as a donor?
                                </button>
                            </h2>
                            <div id="collapse1" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    Click on the "Register" link in the navigation menu and fill out the donor registration form. Your account will be verified by our admin team.
                                </div>
                            </div>
                        </div>
                        
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="faq2">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse2">
                                    How often can I donate blood?
                                </button>
                            </h2>
                            <div id="collapse2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    You can donate whole blood every 56 days (8 weeks). Our system tracks your donation history to ensure you're eligible.
                                </div>
                            </div>
                        </div>
                        
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="faq3">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse3">
                                    Is my information secure?
                                </button>
                            </h2>
                            <div id="collapse3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    Yes, we use industry-standard security measures to protect your personal information. Only verified users can access donor contact information.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>