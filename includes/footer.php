    </main>

    <!-- Footer -->
    <footer class="bg-dark text-light py-4 mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <h5><i class="fas fa-tint me-2"></i>Blood Bank</h5>
                    <p>Connecting life savers with those in need. Join our community of blood donors and help save lives.</p>
                </div>
                <div class="col-md-4">
                    <h6>Quick Links</h6>
                    <ul class="list-unstyled">
                        <li><a href="<?php echo SITE_URL; ?>" class="text-light">Home</a></li>
                        <li><a href="<?php echo SITE_URL; ?>search_donors.php" class="text-light">Find Donors</a></li>
                        <li><a href="<?php echo SITE_URL; ?>request_blood.php" class="text-light">Request Blood</a></li>
                        <li><a href="<?php echo SITE_URL; ?>register.php" class="text-light">Become a Donor</a></li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h6>Contact Information</h6>
                    <ul class="list-unstyled">
                        <li><i class="fas fa-envelope me-2"></i><?php echo ADMIN_EMAIL; ?></li>
                        <li><i class="fas fa-phone me-2"></i>+91 1234567890</li>
                        <li><i class="fas fa-map-marker-alt me-2"></i>123 Health Street, Medical City</li>
                    </ul>
                </div>
            </div>
            <hr class="my-3">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <p class="mb-0">&copy; <?php echo date('Y'); ?> Blood Donation Management System. All rights reserved.</p>
                </div>
                <div class="col-md-6 text-end">
                    <a href="#" class="text-light me-3"><i class="fab fa-facebook"></i></a>
                    <a href="#" class="text-light me-3"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="text-light me-3"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="text-light"><i class="fab fa-linkedin"></i></a>
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