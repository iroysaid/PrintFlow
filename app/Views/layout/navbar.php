<?php $session = session(); ?>
<nav class="navbar navbar-expand-lg navbar-corporate sticky-top">
    <div class="container-fluid">
        <a class="navbar-brand fw-bold" href="/">
            <i class="fas fa-print me-2"></i>Wise Printing
        </a>
        <button class="navbar-toggler border-0 text-white" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon filter-invert"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <?php if($session->get('logged_in')): ?>
                    <?php if($session->get('role') == 'admin' || $session->get('role') == 'production'): ?>
                        <li class="nav-item">
                            <a class="nav-link <?= uri_string() == 'admin/dashboard' ? 'active' : '' ?>" href="/admin/dashboard">Dashboard</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= uri_string() == 'admin/promos' ? 'active' : '' ?>" href="/admin/promos">Content & Promos</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= uri_string() == 'admin/products' ? 'active' : '' ?>" href="/admin/products">Inventory</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= uri_string() == 'admin/transactions' ? 'active' : '' ?>" href="/admin/transactions">Orders</a>
                        </li>
                    <?php endif; ?>
                    
                    <?php if($session->get('role') == 'cashier' || $session->get('role') == 'admin'): ?>
                        <li class="nav-item">
                            <a class="nav-link <?= uri_string() == 'pos' ? 'active' : '' ?>" href="/pos">POS</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= uri_string() == 'pos/history' ? 'active' : '' ?>" href="/pos/history">History</a>
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
                    <li class="nav-item">
                        <a class="nav-link btn btn-outline-light px-4 ms-2" href="/login">Login</a>
                    </li>
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
