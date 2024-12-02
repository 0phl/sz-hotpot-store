<div class="position-sticky pt-3 sidebar-sticky">
    <!-- Main navigation -->
    <ul class="nav flex-column nav-main">
        <li class="nav-item">
            <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'manage_items.php' ? 'active' : ''; ?>" href="manage_items.php">
                <i class="bx bxs-food-menu"></i> Manage Menu
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="../index.php" target="_blank">
                <i class='bx bx-home'></i> View Homepage
            </a>
        </li>
    </ul>

    <!-- Bottom navigation -->
    <ul class="nav flex-column nav-bottom">
        <li class="nav-item">
            <a class="nav-link text-danger" href="logout.php">
                <i class='bx bx-log-out'></i> Sign Out
            </a>
        </li>
    </ul>
</div>