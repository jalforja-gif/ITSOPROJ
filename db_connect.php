<?php
// DATABASE CREDENTIALS from Railway environment variables
$servername = getenv('DB_HOST');      // Example: containers-us-west-123.railway.app
$username   = getenv('DB_USER');      // Example: railway_user
$password   = getenv('DB_PASS');      // Example: your_password
$database   = getenv('DB_NAME');      // Example: ip_monitoring
$port       = getenv('DB_PORT');      // Example: 3306

// Connect to MySQL
$conn = new mysqli($servername, $username, $password, $database, $port);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// ==================== AUTO-CREATE SUPERADMIN ====================
$checkBackup = "SELECT * FROM users WHERE username = 'superadmin' LIMIT 1";
$result = $conn->query($checkBackup);

if ($result && $result->num_rows == 0) {
    $defaultUsername = 'superadmin';
    $defaultPassword = password_hash('superadmin2025', PASSWORD_DEFAULT);
    $defaultStatus   = 'Approved'; // auto-approved

    // Insert backup account (auto-approved)
    $insert = $conn->prepare("INSERT INTO users (username, password, status) VALUES (?, ?, ?)");
    $insert->bind_param("sss", $defaultUsername, $defaultPassword, $defaultStatus);
    $insert->execute();
}

// ==================== DEPARTMENT NAMES ====================
$departmentNames = [
    'CCS'   => 'College of Computer Studies',
    'CFND'  => 'College of Food Nutrition and Dietetics',
    'CIT'   => 'College of Industrial Technology',
    'CTE'   => 'College of Teacher Education',
    'CA'    => 'College of Agriculture',
    'CAS'   => 'College of Arts and Sciences',
    'CBAA'  => 'College of Business Administration and Accountancy',
    'COE'   => 'College of Engineering',
    'CCJE'  => 'College of Criminal Justice Education',
    'COF'   => 'College of Fisheries',
    'CHMT'  => 'College of Hospitality Management and Tourism',
    'CNAH'  => 'College of Nursing and Allied Health',
];
?>