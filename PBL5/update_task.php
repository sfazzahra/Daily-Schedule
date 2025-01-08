<?php
include 'koneksi.php';

// Fungsi untuk mengirimkan respons 
function sendJsonResponse($success, $message = '') {
    echo json_encode(['success' => $success, 'message' => $message]);
    exit;
}

// Fungsi untuk memvalidasi input
function isValidInput($data) {
    return isset($data['id']) && isset($data['status']);
}

// Fungsi untuk memperbarui status task
function updateTaskStatus($id, $status, $koneksi) {
    $id = $koneksi->real_escape_string($id);
    $status = $koneksi->real_escape_string($status);
    $sql = "UPDATE task SET statuss = '$status' WHERE id = $id";
    return $koneksi->query($sql);
}

// Ambil data JSON dari request
$data = json_decode(file_get_contents("php://input"), true);

// Validasi input
if (!isValidInput($data)) {
    sendJsonResponse(false, 'Invalid input');
}

// Update status task
if (updateTaskStatus($data['id'], $data['status'], $koneksi)) {
    sendJsonResponse(true);
} else {
    sendJsonResponse(false, 'Failed to update status');
}

// Tutup koneksi
$koneksi->close();
?>
