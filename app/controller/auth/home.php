<?php
// app/controller/auth/home.php
require_once __DIR__ . '/../../../config/app.php';
sessionStart();
if (!empty($_SESSION['user_id'])) {
    redirect(dashboardForRole($_SESSION['role']));
}
redirect('index.php?page=login');
