<?php
/* app/controller/admin/branches.php */

// 1. Security Check: Protect the route so only logged-in admins can access it
requireLogin('admin');

// 2. Include the central models database file
require_once __DIR__ . '/../../model/Models.php';

// 3. Initialize the Branch Model object using the global connection variable ($conn)
$branchModel = new BranchModel($conn);

// 4. Data Fetching: Retrieve all recorded library branches from the database
$branches = $branchModel->getAll();

// 5. Define the page metadata header title
$pageTitle = 'All Branches';

// 6. Build the user interface by requiring the view layout templates
require __DIR__ . '/../../view/shared/header.php';
require __DIR__ . '/../../view/admin/branches.php';
require __DIR__ . '/../../view/shared/footer.php';