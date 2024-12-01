<style>
.footer {
    background-color: rgba(255, 255, 255, 0.9);
    padding: 1rem 0;
    box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.05);
    margin-top: 3rem;
}

.footer p {
    margin-bottom: 0.5rem !important;
    color: #333;
}

.footer a {
    color: #e31837;
    text-decoration: none;
}

.footer a:hover {
    color: #c41530;
    text-decoration: underline;
}
</style>

<footer class="footer mt-auto py-3">
    <div class="container text-center">
        <p class="mb-1">📞 09762680571 / 09673075816</p>
        <p class="mb-1">📍 Camella Sorrento, Panapaan 4, Bacoor Cavite</p>
        <p class="mb-1">✉️ szhothaven@gmail.com</p>
        <a href="<?php echo FACEBOOK_URL; ?>" class="text-decoration-none" target="_blank">
            <i class="fab fa-facebook"></i> Follow us on Facebook
        </a>
    </div>
</footer>
    
    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Initialize Bootstrap components -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize navbar toggler
            var navbarToggler = document.querySelector('.navbar-toggler');
            var navbarCollapse = document.querySelector('.navbar-collapse');
            
            if (navbarToggler && navbarCollapse) {
                var bsCollapse = new bootstrap.Collapse(navbarCollapse, {
                    toggle: false
                });
                
                navbarToggler.addEventListener('click', function(e) {
                    e.preventDefault();
                    bsCollapse.toggle();
                });

                // Close menu when clicking outside
                document.addEventListener('click', function(e) {
                    if (!navbarToggler.contains(e.target) && !navbarCollapse.contains(e.target)) {
                        if (navbarCollapse.classList.contains('show')) {
                            bsCollapse.hide();
                        }
                    }
                });

                // Close menu when clicking nav links
                var navLinks = document.querySelectorAll('.navbar-nav .nav-link');
                navLinks.forEach(function(link) {
                    link.addEventListener('click', function() {
                        if (navbarCollapse.classList.contains('show')) {
                            bsCollapse.hide();
                        }
                    });
                });
            }
        });
    </script>

    <!-- Additional scripts if any -->
    <?php if (isset($extra_scripts)) echo $extra_scripts; ?>
</body>
</html>