<?php
include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_user = mysqli_real_escape_string($koneksi, $_POST['id_user']);
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $password = $_POST['password'];
    $role = mysqli_real_escape_string($koneksi, $_POST['role']);

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $query = "INSERT INTO user (id, username, password, role) VALUES ('$id_user', '$username', '$hashed_password', '$role')";

    if (mysqli_query($koneksi, $query)) {
        echo "<script>alert('Registrasi berhasil!'); window.location='login.php';</script>";
    } else {
        echo "script>alert('Registrasi gagal: " . mysqli_error($koneksi) . "'); window.history.back();</script>";    
    }
}
?>