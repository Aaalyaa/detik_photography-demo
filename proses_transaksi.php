<?php
session_start();
require 'koneksi.php';

// Ambil data dari form
$id_invoice = $_POST['id_invoice'];
$id_karyawan = $_POST['id_karyawan'];
$id_klien = $_POST['id_klien'];
$id_venue = $_POST['id_venue'];
$id_paket = $_POST['id_paket'];
$no_payment = $_POST['no_payment'];
$tgl_cetak_invoice = $_POST['tgl_cetak_invoice'];
$tgl_pelaksanaan = $_POST['tgl_pelaksanaan'];
$subtotal = $_POST['subtotal'];
$down_payment = $_POST['down_payment'];
$sisa_pembayaran = $_POST['last_payment'];

// Validasi data kosong
if (empty($id_invoice) || empty($id_karyawan) || empty($id_klien) || empty($id_venue) || empty($no_payment)) {
    die('Error: Data tidak lengkap.');
}

// Validasi id_paket
if (empty($id_paket)) {
    die('Error: Paket tidak valid atau belum dipilih.');
}

// Validasi no_payment di table_payment
$checkPayment = $conn->query("SELECT * FROM table_payment WHERE no_payment = '$no_payment'");
if ($checkPayment->num_rows === 0) {
    die('Error: Metode pembayaran tidak valid.');
}

// Query INSERT
$sql = "INSERT INTO invoice (id_invoice, id_karyawan, id_klien, id_paket, id_venue, no_payment, tgl_cetak_invoice, tgl_pelaksanaan, subtotal, down_payment, sisa_pembayaran) 
        VALUES ('$id_invoice', '$id_karyawan', '$id_klien', '$id_paket', '$id_venue', '$no_payment', '$tgl_cetak_invoice', '$tgl_pelaksanaan', '$subtotal', '$down_payment', '$sisa_pembayaran')";

if ($conn->query($sql) === TRUE) {
    header("Location: invoice-1.php?id_invoice=$id_invoice");
} else {
    die("Error: " . $conn->error);
}
?>
