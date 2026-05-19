<?php
// app/controller/auth/logout.php
//this is the logout.php file in the auth controller, it will destroy the session and redirect to login page
sessionStart();
session_destroy();
redirect('index.php?page=login');
