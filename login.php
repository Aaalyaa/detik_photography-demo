<?php
session_start();
require "koneksi.php";

$username = $_POST["username"];
$password = $_POST["password"];

$query = mysqli_query($conn, "SELECT * FROM table_karyawan WHERE username='$username' AND password='$password'");
$cek = mysqli_num_rows($query);

if($cek > 0){
    $data = mysqli_fetch_assoc($query);
    if($data['role']=="admin"){
        $_SESSION['username'] = $username;
        $_SESSION['peran'] = "admin";
        header("location:dashboard.php");
    // Cek jika user login sebagai user
    }else if($data['role']=="kasir"){
        $_SESSION['username'] = $username;
        $_SESSION['peran'] = "kasir";
        header("location:pagekasir.php");
    }
} else {
    echo "<br/> Username atau password salah";
}
?>