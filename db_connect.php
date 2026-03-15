<?php
$host = "dpg-d6r1lonkijhs73befb9g-a";
$port = 5432;
$dbname = "ip_monitoring";
$user = "ip_monitoring_user";
$pass = "VMDPlNKuDMyvMPmllLGbXc9bBRXuMP1L"; // ilagay password mo dito

try {
    $pdo = new PDO("pgsql:host=$host;port=$port;dbname=$dbname", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// AUTO-CREATE ACCOUNT (superadmin)
$stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username LIMIT 1");
$stmt->execute(['username' => 'superadmin']);
$result = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$result) {
    $defaultUsername = 'superadmin';
    $defaultPassword = password_hash('superadmin2025', PASSWORD_DEFAULT);
    $defaultStatus = 'Approved';

    $insert = $pdo->prepare("INSERT INTO users (username, password, status) VALUES (:username, :password, :status)");
    $insert->execute([
        'username' => $defaultUsername,
        'password' => $defaultPassword,
        'status'   => $defaultStatus
    ]);
}

// Department names array stays the same
$departmentNames = [
    'CCS' => 'College of Computer Studies',
    'CFND' => 'College of Food Nutrition and Dietetics',
    'CIT' => 'College of Industrial Technology',
    'CTE' => 'College of Teacher Education',
    'CA' => 'College of Agriculture',
    'CAS' => 'College of Arts and Sciences',
    'CBAA' => 'College of Business Administration and Accountancy',
    'COE' => 'College of Engineering',
    'CCJE' => 'College of Criminal Justice Education',
    'COF' => 'College of Fisheries',
    'CHMT' => 'College of Hospitality Management and Tourism',
    'CNAH' => 'College of Nursing and Allied Health',
];
?>
