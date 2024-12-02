<?php
require_once 'includes/config.php';
$page_title = "Home";
require_once 'includes/header.php';
?>

<div class="hero-section">
    <div class="steam-particles">
        <!-- JS will add particles here -->
    </div>
    <div class="hero-content">
        <h1 class="hero-title animate__animated animate__fadeInUp">Welcome to S&Z Hot Pot Haven</h1>
        <p class="hero-subtitle animate__animated animate__fadeInUp animate__delay-1s">Premium Hotpot Experience in Bacoor, Cavite</p>
        <a href="#menu" class="hero-button animate__animated animate__fadeInUp animate__delay-2s">View Our Products</a>
    </div>
</div>

<style>
.hero-section {
    background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)), 
                url('assets/images/hero-bg.jpg');
    background-size: cover;
    background-position: center;
    background-attachment: fixed;
    height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    text-align: center;
    position: relative;
    overflow: hidden;
}

/* Red accent overlay */
.hero-section::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: radial-gradient(
        circle at center,
        rgba(227, 24, 55, 0.2) 0%,
        rgba(227, 24, 55, 0.1) 30%,
        rgba(0, 0, 0, 0.2) 100%
    );
    z-index: 1;
}

/* Animated steam particles */
.hero-section .steam-particles {
    position: absolute;
    width: 100%;
    height: 100%;
    top: 0;
    left: 0;
    pointer-events: none;
    z-index: 2;
}

.steam-particle {
    position: absolute;
    background: rgba(255, 255, 255, 0.05);
    border-radius: 50%;
    animation: float 8s infinite;
}

@keyframes float {
    0% {
        transform: translateY(100vh) scale(0);
        opacity: 0;
    }
    50% {
        opacity: 0.5;
    }
    100% {
        transform: translateY(-100px) scale(1);
        opacity: 0;
    }
}

.hero-content {
    position: relative;
    z-index: 2;
    max-width: 800px;
    padding: 40px;
}

.hero-title {
    font-size: 3.5rem;
    color: #ffffff;
    margin-bottom: 20px;
    font-weight: bold;
    text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
}

.hero-subtitle {
    font-size: 1.6rem;
    color: #ffffff;
    margin-bottom: 30px;
    line-height: 1.6;
    text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.3);
}

.hero-button {
    display: inline-block;
    padding: 15px 40px;
    background-color: #e31837;
    color: white;
    text-decoration: none;
    border-radius: 30px;
    font-size: 1.2rem;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(227, 24, 55, 0.3);
    border: 2px solid transparent;
}

.hero-button:hover {
    background-color: transparent;
    border-color: #e31837;
    color: white;
    transform: translateY(-2px);
    text-decoration: none;
}

@media (max-width: 768px) {
    .hero-title {
        font-size: 2.5rem;
    }
    
    .hero-subtitle {
        font-size: 1.3rem;
    }
    
    .hero-content {
        padding: 20px;
    }

    .hero-section {
        background-attachment: scroll;
        background-position: center;
    }
}

@media (max-width: 480px) {
    .hero-title {
        font-size: 2rem;
    }
    
    .hero-subtitle {
        font-size: 1.1rem;
    }
    
    .hero-button {
        padding: 12px 30px;
        font-size: 1.1rem;
    }
}

.search-container {
    max-width: 500px;
    margin: 0 auto 2rem;
    position: relative;
}

.search-input {
    width: 100%;
    padding: 12px 45px;
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(227, 24, 55, 0.1);
    border-radius: 50px;
    font-size: 16px;
    color: #333;
    transition: all 0.3s ease;
    backdrop-filter: blur(5px);
}

.search-input:focus {
    outline: none;
    border-color: #e31837;
    box-shadow: 0 0 15px rgba(227, 24, 55, 0.15);
    background: rgba(255, 255, 255, 0.1);
}

.search-icon {
    position: absolute;
    left: 20px;
    top: 50%;
    transform: translateY(-50%);
    color: #999;
    transition: all 0.3s ease;
    pointer-events: none;
}

.search-container:focus-within .search-icon {
    color: #e31837;
    transform: translateY(-50%) scale(1.1);
}

/* Add a subtle glow animation */
@keyframes searchGlow {
    0% {
        box-shadow: 0 0 5px rgba(227, 24, 55, 0.1);
    }
    50% {
        box-shadow: 0 0 15px rgba(227, 24, 55, 0.2);
    }
    100% {
        box-shadow: 0 0 5px rgba(227, 24, 55, 0.1);
    }
}

.search-input:focus {
    animation: searchGlow 2s infinite;
}

/* Add a subtle placeholder animation */
.search-input::placeholder {
    color: #999;
    transition: all 0.3s ease;
}

.search-input:focus::placeholder {
    opacity: 0.7;
    transform: translateX(10px);
}

/* Add a subtle border animation */
@keyframes borderGlow {
    0% {
        border-color: rgba(227, 24, 55, 0.1);
    }
    50% {
        border-color: rgba(227, 24, 55, 0.3);
    }
    100% {
        border-color: rgba(227, 24, 55, 0.1);
    }
}

.search-input:focus {
    animation: borderGlow 2s infinite, searchGlow 2s infinite;
}

.scroll-to-top {
    position: fixed;
    bottom: 30px;
    right: 30px;
    width: 40px;
    height: 40px;
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(5px);
    border: 1px solid rgba(227, 24, 55, 0.2);
    border-radius: 12px;
    display: flex;
    justify-content: center;
    align-items: center;
    cursor: pointer;
    opacity: 0;
    visibility: hidden;
    transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    z-index: 1000;
    animation: floating 3s ease-in-out infinite;
}

.scroll-to-top::before {
    content: '';
    position: absolute;
    width: 12px;
    height: 12px;
    border-left: 2px solid #e31837;
    border-top: 2px solid #e31837;
    transform: translateY(2px) rotate(45deg);
    transition: transform 0.3s ease;
}

.scroll-to-top:hover {
    background: rgba(255, 255, 255, 0.2);
    border-color: rgba(227, 24, 55, 0.4);
}

.scroll-to-top:hover::before {
    transform: translateY(-2px) rotate(45deg);
}

.scroll-to-top.visible {
    opacity: 1;
    visibility: visible;
}

@keyframes floating {
    0% {
        transform: translateY(0px);
    }
    50% {
        transform: translateY(-8px);
    }
    100% {
        transform: translateY(0px);
    }
}

/* Add smooth transition when appearing/disappearing */
.scroll-to-top:not(.visible) {
    transform: translateY(20px);
    opacity: 0;
}
</style>

<!-- Add this in the head section -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

<div class="container my-5">
    <h2 class="text-center mb-4" id="menu">Our Menu</h2>
    <div class="search-container">
        <i class="fas fa-search search-icon"></i>
        <input type="text" id="menuSearch" class="search-input" placeholder="Search menu items...">
    </div>
    <?php require_once 'includes/menu_items.php'; ?>
</div>

<button class="scroll-to-top" id="scrollToTop" title="Go to top"></button>

<?php 
// Add extra scripts that will be included in footer
$extra_scripts = '
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="assets/js/main.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    // Steam particles animation
    const particlesContainer = document.querySelector(".steam-particles");
    const particleCount = 50;

    for (let i = 0; i <particleCount; i++) {
        const particle = document.createElement("div");
        particle.className = "steam-particle";
        const size = Math.random() * 15 + 5;
        particle.style.width = `${size}px`;
        particle.style.height = `${size}px`;
        particle.style.left = `${Math.random() * 100}%`;
        particle.style.animationDelay = `${Math.random() * 8}s`;
        particlesContainer.appendChild(particle);
    }

    // Search functionality
    const searchInput = document.getElementById("menuSearch");
    searchInput.addEventListener("input", function() {
        const searchTerm = this.value.toLowerCase();
        const menuItems = document.querySelectorAll(".menu-card");
        
        menuItems.forEach(item => {
            const itemName = item.querySelector(".menu-title").textContent.toLowerCase();
            const itemDesc = item.querySelector(".menu-description").textContent.toLowerCase();
            
            if (itemName.includes(searchTerm) || itemDesc.includes(searchTerm)) {
                item.style.display = "";
            } else {
                item.style.display = "none";
            }
        });
    });

    // Scroll to top functionality
    const scrollToTopButton = document.getElementById("scrollToTop");
    
    // Show/hide button based on scroll position
    window.addEventListener("scroll", function() {
        if (window.pageYOffset > 300) {
            scrollToTopButton.classList.add("visible");
        } else {
            scrollToTopButton.classList.remove("visible");
        }
    });

    // Smooth scroll to top when button is clicked
    scrollToTopButton.addEventListener("click", function() {
        window.scrollTo({
            top: 0,
            behavior: "smooth"
        });
    });
});
</script>
';
require_once 'includes/footer.php'; 
?>