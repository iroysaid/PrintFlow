<?php $session = session(); ?>
<nav class="navbar navbar-expand-lg navbar-corporate sticky-top">
    <div class="container-fluid">
        <a class="navbar-brand fw-bold" href="/">
            <img src="/images/logo.svg" alt="Wise Printing" height="30" class="me-2">
        </a>
        <button class="navbar-toggler border-0 text-white" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon filter-invert"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <?php if($session->get('logged_in')): ?>
                    
                    <!-- 1. Dashboard -->
                    <!-- 1. Dashboard -->
                    <?php if($session->get('role') == 'admin'): ?>
                        <li class="nav-item">
                            <a class="nav-link <?= uri_string() == 'admin/dashboard' ? 'active' : '' ?>" href="/admin/dashboard">Dashboard</a>
                        </li>
                    <?php endif; ?>

                    <?php if($session->get('role') == 'production'): ?>
                        <li class="nav-item">
                            <a class="nav-link <?= uri_string() == 'production/dashboard' ? 'active' : '' ?>" href="/production/dashboard">Dashboard</a>
                        </li>
                    <?php endif; ?>

                    <!-- 1b. Production Board (For Admin visibility) -->
                    <?php if($session->get('role') == 'admin'): ?>
                        <li class="nav-item">
                            <a class="nav-link <?= uri_string() == 'production/dashboard' ? 'active' : '' ?>" href="/production/dashboard">Production</a>
                        </li>
                    <?php endif; ?>

                    <!-- 2. POS (Renamed to Order) -->
                    <?php if($session->get('role') == 'cashier' || $session->get('role') == 'admin'): ?>
                        <li class="nav-item">
                            <a class="nav-link <?= uri_string() == 'pos' ? 'active' : '' ?>" href="/pos">Order</a>
                        </li>
                    <?php endif; ?>

                    <!-- 3. Orders (Removed as per request) -->

                    <!-- 4. History -->
                    <?php if($session->get('role') == 'cashier' || $session->get('role') == 'admin'): ?>
                        <li class="nav-item">
                            <a class="nav-link <?= uri_string() == 'pos/history' ? 'active' : '' ?>" href="/pos/history">History</a>
                        </li>
                    <?php endif; ?>

                    <!-- 5. Inventory (Products) -->
                    <?php if($session->get('role') == 'admin' || $session->get('role') == 'production'): ?>
                        <li class="nav-item">
                            <a class="nav-link <?= uri_string() == 'admin/products' ? 'active' : '' ?>" href="/admin/products">Inventory</a>
                        </li>
                    <?php endif; ?>

                    <!-- 6. Content & Promos -->
                    <?php if($session->get('role') == 'admin' || $session->get('role') == 'production'): ?>
                        <li class="nav-item">
                            <a class="nav-link <?= uri_string() == 'admin/promos' ? 'active' : '' ?>" href="/admin/promos">Content & Promos</a>
                        </li>
                    <?php endif; ?>

                    <!-- 7. Users (Admin Only) -->
                    <?php if($session->get('role') == 'admin'): ?>
                        <li class="nav-item">
                            <a class="nav-link <?= uri_string() == 'admin/users' ? 'active' : '' ?>" href="/admin/users">Users</a>
                        </li>
                    <?php endif; ?>

                <?php endif; ?>
            </ul>
            <ul class="navbar-nav">
                <?php if($session->get('logged_in')): ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user-circle me-1"></i> <?= $session->get('fullname') ?> (<?= ucfirst($session->get('role')) ?>)
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                            <li><a class="dropdown-item" href="/logout"><i class="fas fa-sign-out-alt me-2 text-danger"></i>Logout</a></li>
                        </ul>
                    </li>
                <?php else: ?>
                    <!-- Login hidden for security -->
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<style>
/* Hotfix for toggler icon in dark navbar if needed */
.filter-invert {
    filter: invert(1) grayscale(100%) brightness(200%);
}
</style>
