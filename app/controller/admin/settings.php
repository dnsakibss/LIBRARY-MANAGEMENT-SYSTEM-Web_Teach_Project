<?php
/* app/controller/admin/settings.php */

// 1. Security Check: Protect the route so only logged-in admins can access it
requireLogin('admin');

// 2. Database Auto-Provisioning: Check if the settings table already exists
$checkTableQuery = "SHOW TABLES LIKE 'system_settings'";
$tableCheckResult = $conn->query($checkTableQuery);

if ($tableCheckResult->num_rows === 0) {
    // Step A: If the table doesn't exist, create it dynamically
    $createTableSql = "CREATE TABLE system_settings (
        k VARCHAR(100) PRIMARY KEY, 
        v TEXT
    )";
    $conn->query($createTableSql);
    
    // Step B: Insert the default core platform configurations into the table
    $insertDefaultsSql = "INSERT INTO system_settings (k, v) VALUES 
        ('allow_self_register', '1'),
        ('default_fine_rate', '5.00'),
        ('default_max_days', '14'),
        ('default_max_books', '5')";
    $conn->query($insertDefaultsSql);
}

// 3. Define the list of expected configurations we want to manage
$settingsKeys = [
    'allow_self_register',
    'default_fine_rate',
    'default_max_days',
    'default_max_books'
];

// 4. Handle Form Submission (When the admin clicks the save settings button)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Loop through each configuration key to capture and save the updated form value
    foreach ($settingsKeys as $key) {
        // Read input text value or default to empty string if missing
        $value = $_POST[$key] ?? '';
        
        // Use a parameterized UPSERT query (Insert or Update if the key already exists)
        $upsertSql = "INSERT INTO system_settings (k, v) VALUES (?, ?) 
                      ON DUPLICATE KEY UPDATE v = ?";
        
        $stmt = $conn->prepare($upsertSql);
        $stmt->bind_param('sss', $key, $value, $value);
        $stmt->execute(); 
        $stmt->close();
    }
    
    // Set a flash notification alert and reload the settings view
    setFlash('success', 'Settings saved successfully.');
    redirect('index.php?page=admin_settings');
}

// 5. Data Fetching: Load all current settings out of the database
$settings = [];
$fetchSettingsQuery = "SELECT * FROM system_settings";
$result = $conn->query($fetchSettingsQuery);

// Loop through each record row and build a simple associative array map ($settings['key'] = 'value')
while ($row = $result->fetch_assoc()) {
    $settingsKey = $row['k'];
    $settingsValue = $row['v'];
    $settings[$settingsKey] = $settingsValue;
}

// 6. Define layout title and load presentation view layout files
$pageTitle = 'Platform Settings';

require __DIR__ . '/../../view/shared/header.php';
require __DIR__ . '/../../view/admin/settings.php';
require __DIR__ . '/../../view/shared/footer.php';