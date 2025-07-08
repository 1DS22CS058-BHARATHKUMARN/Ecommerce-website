<?php
include '../includes/db.php';

$email = 'admin@example.com';
$password = password_hash('admin123', PASSWORD_DEFAULT);
$role = 'admin';
$username = 'admin';

$stmt = $conn->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)");
$stmt->execute([$username, $email, $password, $role]);

echo "Admin created.";
?>
