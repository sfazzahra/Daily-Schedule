<?php
include 'koneksi.php';

// Fungsi untuk mengarahkan dengan pesan
function redirectWithMessage($message) {
    header("Location: taksdatadosen.php?message=$message");
    exit();
}

// Fungsi untuk memperbarui data
function updateTask($title, $datee, $note) {
    global $koneksi;
    $query = "UPDATE task SET datee = '$datee', note = '$note' WHERE title = '$title'";
    return mysqli_query($koneksi, $query);
}

// Mengambil data dari form
$title = $_POST['title'];
$datee = $_POST['datee'];
$note = $_POST['note'];

// Validasi input
if (isValidInput($title, $datee, $note)) {
    if (updateTask($title, $datee, $note)) {
        redirectWithMessage('success');
    } else {
        redirectWithMessage('error');
    }
} else {
    redirectWithMessage('empty');
}

// Fungsi untuk memvalidasi input
function isValidInput($title, $datee, $note) {
    return !empty($title) && !empty($datee) && !empty($note);
}
?>
