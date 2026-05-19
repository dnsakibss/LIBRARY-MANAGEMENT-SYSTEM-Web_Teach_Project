<?php
/* app/controller/admin/settings.php */

requireLogin('admin');

// Block: Automatic layout validation and configuration table initialization
$checkTableQuery = "SHOW TABLES LIKE 'system_settings'";
$tableCheckResult = $conn->query($checkTableQuery);

if ($tableCheckResult->num_rows === 0) {
    $createTableSql = "CREATE TABLE system_settings (k VARCHAR(100) PRIMARY KEY, v TEXT)";
    $conn->query($createTableSql);
    
    $insertDefaultsSql = "INSERT INTO system_settings (k, v) VALUES 
        ('allow_self_register', '1'),
        ('default_fine_rate', '5.00'),
        ('default_max_days', '14'),
        ('default_max_books', '5')";
    $conn->query($insertDefaultsSql);
}

$settingsKeys = [
    'allow_self_register',
    'default_fine_rate',
    'default_max_days',
    'default_max_books'
];

// Block: Cycle through and record configuration variables into the settings repository
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($settingsKeys as $key) {
        $value = $_POST[$key] ?? '';
        
        $upsertSql = "INSERT INTO system_settings (k, v) VALUES (?, ?) 
                      ON DUPLICATE KEY UPDATE v = ?";
        
        $stmt = $conn->prepare($upsertSql);
        $stmt->bind_param('sss', $key, $value, $value);
        $stmt->execute(); 
        $stmt->close();
    }
    
    setFlash('success', 'Settings saved.');
    redirect('index.php?page=admin_settings');
}

$settings = [];
$fetchSettingsQuery = "SELECT * FROM system_settings";
$result = $conn->query($fetchSettingsQuery);

while ($row = $result->fetch_assoc()) {
    $settingsKey = $row['k'];
    $settingsValue = $row['v'];
    $settings[$settingsKey] = $settingsValue;
}

$pageTitle = 'Platform Settings';

require __DIR__ . '/../../view/shared/header.php';
require __DIR__ . '/../../view/admin/settings.php';
require __DIR__ . '/../../view/shared/footer.php';