<?php

// Load CodeIgniter
require __DIR__ . '/app/Config/Paths.php';
$paths = new Config\Paths();
require $paths->systemDirectory . '/Boot.php';
exit(CodeIgniter\Boot::bootWeb($paths));

// This part won't stay, but we will use a controller method instead easier.
// Actually, let's just make a temporary controller file.
