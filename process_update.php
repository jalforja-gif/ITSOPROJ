<?php
include 'db_connect.php';
include 'includes/utils.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ip_id = $_POST['ip_id'];
    $date_submitted_to_ipophil = !empty($_POST['date_submitted_to_ipophil']) ? $_POST['date_submitted_to_ipophil'] : NULL;
    $classification = $_POST['classification'];

    $expiration_date = calculateExpiration($classification, $date_submitted_to_ipophil);

    $fields = ['endorsement_letter', 'application_form', 'application_fee', 'issued_certificate', 'project_file'];
    $updates = [];

    foreach ($fields as $field) {
        if (isset($_FILES[$field]) && $_FILES[$field]['error'] === UPLOAD_ERR_OK) {
            $filename = basename($_FILES[$field]['name']);
            move_uploaded_file($_FILES[$field]['tmp_name'], 'uploads/' . $filename);
            $updates[] = "$field = '$filename'";
        }
    }

    // Add date and expiration if provided
    if ($date_submitted_to_ipophil !== NULL) {
        $updates[] = "date_submitted_to_ipophil = '$date_submitted_to_ipophil'";
    }
    if ($expiration_date !== NULL) {
        $updates[] = "expiration_date = '$expiration_date'";
    }

    // --- STATUS RULE ---
    $stmt_check = $conn->prepare("SELECT status FROM intellectual_properties WHERE ip_id = ?");
    $stmt_check->bind_param("i", $ip_id);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();
    $row = $result_check->fetch_assoc();
    $current_status = $row['status'] ?? '';

    if (strtolower($current_status) === 'for revision') {
        $updates[] = "status = 'Pending'";
        $updates[] = "remarks = ''";
    }

    if (!empty($updates)) {
        $update_sql = "UPDATE intellectual_properties SET " . implode(', ', $updates) . " WHERE ip_id = ?";
        $stmt = $conn->prepare($update_sql);
        $stmt->bind_param("i", $ip_id);
        if ($stmt->execute()) {
            header("Location: index.php?page=coord_content");
        } else {
            echo "Error updating the record: " . $stmt->error;
        }
    } else {
    echo "<script>
        alert('No files uploaded or updates made.');
        window.history.back();
    </script>";
    exit();
}

} else {
    echo "Invalid request.";
}
?>
