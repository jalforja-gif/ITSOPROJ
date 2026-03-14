<?php
if (session_status() === PHP_SESSION_NONE) session_start();
include __DIR__ . '/../db_connect.php';

// Ensure user is logged in
if (!isset($_SESSION['user_id'], $_SESSION['username'], $_SESSION['role'])) {
    session_unset();
    session_destroy();
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch user from DB
$stmt = $conn->prepare("SELECT role, status FROM users WHERE id = ?");
$stmt->bind_param('i', $user_id);
$stmt->execute();
$res = $stmt->get_result();
$user = $res->fetch_assoc();
$stmt->close();

// If user deleted or not approved
if (!$user || $user['status'] !== 'approved') {
    session_unset();
    session_destroy();
    header("Location: login.php?error=account_not_approved");
    exit;
}

// Optional: role-based access check
if (isset($allowed_roles) && !in_array($user['role'], $allowed_roles)) {
    echo "<script>
        alert('Unauthorized access!');
        window.location.href = 'login.php';
    </script>";
    exit;
}

// Update session role in case it changed
$_SESSION['role'] = $user['role'];
?>
