<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wise Printing - Digital Printing System</title>
    <meta name="csrf-token" content="<?= csrf_hash() ?>">
    <meta name="csrf-header" content="<?= config('Security')->headerName ?>">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="/css/style.css">

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="icon" type="image/svg+xml" href="/favicon.svg">
</head>

<body class="d-flex flex-column min-vh-100">

    <?= $this->include('layout/navbar') ?>

    <div class="container-fluid flex-grow-1 p-0">
        <?= $this->renderSection('content') ?>
    </div>

    <footer class="bg-light text-center py-3 mt-auto border-top">
        <div class="container">
            <small class="text-muted">&copy; <?= date('Y') ?> Wise Printing. Version 1.01 | <i class="fas fa-code"></i>
                Developed by <a href="#" class="text-decoration-none text-muted fw-bold">iroysaid</a></small>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>