<?php /* app/controller/admin/settings.php */
requireLogin('admin');
// Global settings stored in a simple DB table (or config file approach)
// We use a settings table pattern; fallback to constants if not in DB
$settings = [];
$r = $conn->query("SHOW TABLES LIKE 'system_settings'");
if ($r->num_rows === 0) {
    $conn->query("CREATE TABLE system_settings (k VARCHAR(100) PRIMARY KEY, v TEXT)");
    $conn->query("INSERT INTO system_settings VALUES ('allow_self_register','1'),('default_fine_rate','5.00'),('default_max_days','14'),('default_max_books','5')");
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach (['allow_self_register','default_fine_rate','default_max_days','default_max_books'] as $key) {
        $val  = $_POST[$key] ?? '';
        $stmt = $conn->prepare("INSERT INTO system_settings (k,v) VALUES (?,?) ON DUPLICATE KEY UPDATE v=?");
        $stmt->bind_param('sss', $key, $val, $val);
        $stmt->execute(); $stmt->close();
    }
    setFlash('success', 'Settings saved.');
    redirect('index.php?page=admin_settings');
}
$result = $conn->query("SELECT * FROM system_settings");
while ($row = $result->fetch_assoc()) $settings[$row['k']] = $row['v'];
$pageTitle = 'Platform Settings';
require __DIR__ . '/../../view/shared/header.php';
require __DIR__ . '/../../view/admin/settings.php';
require __DIR__ . '/../../view/shared/footer.php';
