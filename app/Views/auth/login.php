<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Wise Printing</title>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/css/style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    <style>
        body {
            background-color: var(--bs-light);
            height: 100vh;
            display: flex;
            align-items: center;
            font-family: 'Inter', sans-serif;
        }
        .login-card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
            overflow: hidden;
        }
        .login-brand {
            background-color: var(--bs-primary);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
        }
    </style>
</head>
<body>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card login-card">
                    <div class="row g-0">
                        <!-- Left: Brand/Logo Area -->
                        <div class="col-md-5 login-brand p-5 d-flex flex-column justify-content-center text-center">
                            <i class="fas fa-print fa-4x mb-4"></i>
                            <h2 class="fw-bold mb-2">Wise Printing</h2>
                            <p class="opacity-75">Sistem Manajemen Percetakan Digital Terintegrasi</p>
                        </div>
                        
                        <!-- Right: Login Form -->
                        <div class="col-md-7 bg-white p-5">
                            <div class="text-center mb-4">
                                <h4 class="fw-bold text-dark">Welcome Back</h4>
                                <p class="text-muted">Please sign in to continue</p>
                            </div>

                            <?php if(session()->getFlashdata('error')): ?>
                                <div class="alert alert-danger py-2 small rounded-3">
                                    <i class="fas fa-exclamation-circle me-1"></i> <?= session()->getFlashdata('error'); ?>
                                </div>
                            <?php endif; ?>

                            <form action="/auth/process" method="post">
                                <?= csrf_field() ?>
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" id="username" name="username" placeholder="Username" required>
                                    <label for="username">Username</label>
                                </div>
                                <div class="form-floating mb-4">
                                    <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                                    <label for="password">Password</label>
                                </div>
                                
                                <button type="submit" class="btn btn-primary w-100 fw-bold py-3 mb-3">
                                    SIGN IN <i class="fas fa-arrow-right ms-2"></i>
                                </button>
                                
                                <div class="text-center">
                                    <a href="/" class="text-decoration-none small text-muted">Back to Landing Page</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>
