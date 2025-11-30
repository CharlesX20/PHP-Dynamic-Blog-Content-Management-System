
</main>
<footer>
    <div class="footer-content">
        <div class="footer-logo">
            <img src="./images/mylogo.png" alt="logo" />
            CharlesGPT Tech-Tok
        </div>
        <div class="social-links">
            <a href="#"><i class="fab fa-facebook-f"></i></a>
            <a href="#"><i class="fab fa-twitter"></i></a>
            <a href="#"><i class="fab fa-instagram"></i></a>
            <a href="#"><i class="fab fa-linkedin-in"></i></a>
        </div>
        <p class="copyright">&copy; 2025 CharlesGPT Tech-Tok. All rights reserved.</p>
    </div>
</footer>

<!-- I ALREADY HAD A LOT OF DIRECTORIES SO I DECIDED TO ADD MY LITTLE JAVASCRIPT BELOW THE FOOTER SECTION -->
<!-- JavaScripts for the hamburger menu on smaller scripts and coolness on scroll-->
<script>
    const hamburger = document.querySelector('.hamburger');
    const navMenu = document.querySelector('nav ul');

    hamburger.addEventListener('click', () => {
        navMenu.classList.toggle('active');
        hamburger.querySelector('i').classList.toggle('fa-bars');
        hamburger.querySelector('i').classList.toggle('fa-times');
    });

    document.querySelectorAll('nav ul a').forEach(link => {
        link.addEventListener('click', () => {
            navMenu.classList.remove('active');
            hamburger.querySelector('i').classList.add('fa-bars');
            hamburger.querySelector('i').classList.remove('fa-times');
        });
    });

    const animateOnScroll = () => {
        const elements = document.querySelectorAll('.about-content, .service, .project');
        elements.forEach(el => {
            if (el.getBoundingClientRect().top < window.innerHeight * 0.8) {
                el.classList.add('animate');
            }
        });
    };
    window.addEventListener('scroll', animateOnScroll);
    window.addEventListener('load', animateOnScroll);

    //for removing our success message after 3 seconds
    document.addEventListener('DOMContentLoaded', () => {
        const successAlert = document.querySelector('.alert-success');
        if (successAlert) {
            // After 3 seconds, start fadeout
            setTimeout(() => {
                successAlert.classList.add('fade-out');
                // Then remove it from the DOM after the transition
                setTimeout(() => successAlert.remove(), 500);
            }, 3000);
        }
    });
</script>
</body>
</html>
