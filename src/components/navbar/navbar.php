<div class="navbar-back">
    <div class="navbar-container">
        <div class="navbar">
            <a href="?page=home" class="logo">TechCare</a>
            <div class="links">
                <a href="?page=home">Home</a>
                <a href="?page=about">About</a>
                <a href="?page=services">Services</a>
                <a href="?page=contact">Contact</a>
                <a href="?page=dashboard">Dashboard</a>
                <?php if (!isset($_SESSION['LoginId'])): ?>
                    <a href="?page=login">
                        <button class="button">Login</button>
                    </a>
                <?php else: ?>

                <?php endif; ?>
            </div>
            <button class="mobile-button">
                <span class="menu-line"></span>
                <span class="menu-line"></span>
                <span class="menu-line"></span>
            </button>
<!--            <div class="mobile-links">-->
<!--                <a href="" class="mobile-link">Home</a>-->
<!--                <a href="" class="mobile-link">About</a>-->
<!--                <a href="" class="mobile-link">Services</a>-->
<!--                <a href="" class="mobile-link">Contact</a>-->
<!--                <a href="?page=dashboard" class="mobile-link">Dashboard</a>-->
<!--                --><?php //if (!isset($_SESSION['user_id'])): ?>
<!--                    <a href="?page=login">-->
<!--                        <button class="button">Login</button>-->
<!--                    </a>-->
<!--                --><?php //else: ?>
<!---->
<!--                --><?php //endif; ?>
<!--            </div>-->
        </div>
    </div>
</div>