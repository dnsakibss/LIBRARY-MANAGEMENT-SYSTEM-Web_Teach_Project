<?php
// app/controller/auth/unauthorized.php
// this is the unauthorized.php file in the auth controller,
// it will show a message to the user that they do not have
// permission to access the page they are trying to access

$pageTitle = 'Access Denied';

require __DIR__ . '/../../view/shared/header.php';
?>

<div class="container d-flex justify-content-center align-items-center" style="min-height: 80vh;">
    <div class="card shadow border-0 text-center p-5" style="max-width: 500px; width: 100%;">
        
        <div class="mb-4">
            <i class="bi bi-shield-lock-fill text-danger" style="font-size: 5rem;"></i>
        </div>

        <h1 class="fw-bold text-danger">403</h1>

        <h2 class="mt-2">Access Denied</h2>

        <p class="text-muted mt-3">
            You do not have permission to access this page.
            Please contact the administrator if you believe this is a mistake.
        </p>

        <div class="mt-4">
            <a href="<?= BASE_URL ?>" class="btn btn-primary px-4">
                <i class="bi bi-house-door-fill me-1"></i>
                Go to Dashboard
            </a>
        </div>

    </div>
</div>

<?php require __DIR__ . '/../../view/shared/footer.php'; ?>