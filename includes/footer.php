    </main>

    <!-- Footer -->
    <footer class="footer-section">
        <div class="footer-content">
            <div class="container">
                <div class="row">
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="footer-widget">
                            <div class="footer-logo mb-3">
                                <h5><i class="fas fa-heartbeat text-danger me-2"></i>Blood Bank</h5>
                            </div>
                            <p class="footer-text">Connecting life savers with those in need. Join our community of blood donors and help save lives every day.</p>
                            <div class="footer-stats">
                                <div class="stat-item">
                                    <span class="stat-number">1000+</span>
                                    <span class="stat-label">Lives Saved</span>
                                </div>
                                <div class="stat-item">
                                    <span class="stat-number">24/7</span>
                                    <span class="stat-label">Available</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-6 mb-4">
                        <div class="footer-widget">
                            <h6 class="footer-widget-title">Quick Links</h6>
                            <ul class="footer-links">
                                <li><a href="<?php echo SITE_URL; ?>"><i class="fas fa-chevron-right"></i> Home</a></li>
                                <li><a href="<?php echo SITE_URL; ?>search_donors.php"><i class="fas fa-chevron-right"></i> Find Donors</a></li>
                                <li><a href="<?php echo SITE_URL; ?>request_blood.php"><i class="fas fa-chevron-right"></i> Request Blood</a></li>
                                <li><a href="<?php echo SITE_URL; ?>register.php"><i class="fas fa-chevron-right"></i> Become a Donor</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 mb-4">
                        <div class="footer-widget">
                            <h6 class="footer-widget-title">Contact Info</h6>
                            <ul class="footer-contact">
                                <li>
                                    <i class="fas fa-envelope"></i>
                                    <span><?php echo ADMIN_EMAIL; ?></span>
                                </li>
                                <li>
                                    <i class="fas fa-phone"></i>
                                    <span>+91 1234567890</span>
                                </li>
                                <li>
                                    <i class="fas fa-map-marker-alt"></i>
                                    <span>123 Health Street, Medical City</span>
                                </li>
                                <li>
                                    <i class="fas fa-clock"></i>
                                    <span>24/7 Emergency Service</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 mb-4">
                        <div class="footer-widget">
                            <h6 class="footer-widget-title">Stay Connected</h6>
                            <p class="footer-text-small">Follow us for updates and health tips</p>
                            <div class="social-links">
                                <a href="#" class="social-link facebook"><i class="fab fa-facebook-f"></i></a>
                                <a href="#" class="social-link twitter"><i class="fab fa-twitter"></i></a>
                                <a href="#" class="social-link instagram"><i class="fab fa-instagram"></i></a>
                                <a href="#" class="social-link linkedin"><i class="fab fa-linkedin-in"></i></a>
                            </div>
                            <div class="emergency-contact">
                                <h6 class="emergency-title">Emergency Hotline</h6>
                                <a href="tel:+911234567890" class="emergency-number">
                                    <i class="fas fa-phone-alt"></i> +91 123-456-7890
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <p class="copyright">&copy; <?php echo date('Y'); ?> Blood Donation Management System. All rights reserved.</p>
                    </div>
                    <div class="col-md-6">
                        <div class="footer-bottom-links">
                            <a href="#">Privacy Policy</a>
                            <a href="#">Terms of Service</a>
                            <a href="#">Support</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Custom JS -->
    <script src="<?php echo SITE_URL; ?>assets/js/main.js"></script>
</body>
</html>