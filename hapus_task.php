<?php 
include 'koneksi.php';
$title = $_GET['title'];
$result = mysqli_query($koneksi, "DELETE FROM task WHERE title='$title'");
header("Location: taksdatadosen.php")
?>