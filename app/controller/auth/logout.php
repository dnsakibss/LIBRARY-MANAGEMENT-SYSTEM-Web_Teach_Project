<?php
// app/controller/auth/logout.php
sessionStart();
session_destroy();
redirect('index.php?page=login');
