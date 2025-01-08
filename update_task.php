<?php
include 'koneksi.php';

// Ambil data JSON dari request
$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['id']) || !isset($data['status'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid input']);
    exit;
}

// Update status di database
$id = $koneksi->real_escape_string($data['id']);
$status = $koneksi->real_escape_string($data['status']);
$sql = "UPDATE task SET statuss = '$status' WHERE id = $id";

if ($koneksi->query($sql) === TRUE) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to update status']);
}

$koneksi->close();
?>
