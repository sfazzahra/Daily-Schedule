<?php
include 'koneksi.php';

// Mengambil data dari form
$title = $_POST['title'];
$datee = $_POST['datee'];
$note = $_POST['note'];

// Validasi agar data tidak kosong
if (!empty($title) && !empty($datee) && !empty($note)) {
    // Update data ke tabel 'task'
    $query = "UPDATE task SET datee = '$datee', note = '$note' WHERE title = '$title'";
    $result = mysqli_query($koneksi, $query);

    // Cek apakah query berhasil
    if ($result) {
        header("Location: taksdatadosen.php?message=success");
        exit();
    } else {
        // Jika terjadi kesalahan
        header("Location: taksdatadosen.php?message=error");
        exit();
    }
} else {
    // Jika data kosong
    header("Location: taksdatadosen.php?message=empty");
    exit();
}
?>
