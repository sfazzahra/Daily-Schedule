<?php
// Include koneksi database
include 'koneksi.php';

// Fungsi untuk menyimpan data task
function saveTask($title, $datee, $statuss, $note) {
    global $koneksi;

    // Query untuk menyimpan task
    $query = "INSERT INTO task (title, datee, note, statuss) VALUES('$title', '$datee', '$note', '$statuss')";
    return mysqli_query($koneksi, $query);
}

// Fungsi untuk mengarahkan ke halaman dengan pesan
function redirectWithMessage($message, $url) {
    echo "<script>
            alert('$message');
            window.location.href = '$url';
          </script>";
}

// Ambil data dari form
$title = $_POST['title'];
$datee = $_POST['datee'];
$statuss = $_POST['statuss'];
$note = $_POST['note'];

// Proses input
if (saveTask($title, $datee, $statuss, $note)) {
    redirectWithMessage('Data Berhasil Disimpan', 'taksdatadosen.php');
} else {
    redirectWithMessage('Gagal Menyimpan Data', 'taksdatadosen.php');
}
?>
