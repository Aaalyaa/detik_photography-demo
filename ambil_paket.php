<?php
require 'koneksi.php';

$data = json_decode(file_get_contents("php://input"), true);
$nama_paket = $data['nama_paket'];

$result = $conn->query("SELECT * FROM table_paket WHERE nama_paket = '$nama_paket'");
$row = $result->fetch_assoc();
echo json_encode($row);
?>
