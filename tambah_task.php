<?php
include 'koneksi.php';
$title = $_POST['title'];
$datee = $_POST['datee'];
$statuss = $_POST['statuss'];
$note = $_POST['note'];
$input = mysqli_query($koneksi, "INSERT INTO task (title, datee, note, statuss) 
VALUES('$title', '$datee', '$note', '$statuss')") or die (mysqli_error($koneksi));

if($input){
    echo "<script>
    alert('Data Berhasil Disimpan');
    window.location.href = 'taksdatadosen.php';
    </script>";
} else {
    echo "<script>
    alert('Gagal Menyimpan Data');
    window.location.href = 'taksdatadosen.php;
    </script>";
}
?>