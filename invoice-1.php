<?php
session_start();
require 'koneksi.php';

if (!isset($_GET['id_invoice'])) {
    die('Error: ID invoice tidak ditemukan.');
}
$id_invoice = $_GET['id_invoice'];

$sql = "SELECT 
            i.id_invoice,
            n.full_nama,
            i.tgl_cetak_invoice,
            i.tgl_pelaksanaan,
            k.nama_klien,
            v.lokasi AS venue,
            v.biaya AS biaya_venue,
            p.nama_paket,
            p.deskripsi_paket,
            p.harga AS harga_paket,
            i.subtotal,
            i.down_payment,
            i.sisa_pembayaran,
            pm.metode_pembayaran
        FROM invoice i
        JOIN table_karyawan n ON i.id_karyawan = n.id_karyawan
        JOIN table_klien k ON i.id_klien = k.id_klien
        JOIN table_venue v ON i.id_venue = v.id_venue
        JOIN table_paket p ON i.id_paket = p.id_paket
        JOIN table_payment pm ON i.no_payment = pm.no_payment
        WHERE i.id_invoice = '$id_invoice'";

$result = $conn->query($sql);

if ($result->num_rows === 0) {
    die('Error: Invoice tidak ditemukan.');
}

$invoice = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detik Photography - Invoice</title>
    <link rel="stylesheet" href="invoice-kasir.css">
</head>

<body>
    <div class="container">
        <div class="button">
            <div class="left-side">
                <img src="Logo Detik 2 - 1.png" alt="Detik Photography Logo">
                <button onclick="window.location.href='client-2.php'" class="input-pemesanan-btn">Tambah Klien</button>
                <button onclick="window.location.href='pagekasir.php'" class="input-pemesanan-btn">Input
                    Pemesanan</button>
                <button onclick="window.location.href='histori-kasir.php'" class="lihat-transaksi-btn">Histori
                    Transaksi</button>
            </div>

            <div class="right-buttons">
                <span>Kasir:</span><span id="nama-kasir"><?php echo htmlspecialchars($invoice['full_nama']); ?></span>
                <button onclick="window.location.href='index.html'">Log Out</button>
            </div>
        </div>

        <div class="invoice-container">
            <header class="invoice-header">
                <h1>Detik Photography</h1>
                <h2>Invoice</h2>
                <p>Nama Kasir: <?php echo htmlspecialchars($invoice['full_nama']); ?></p>
            </header>
            <section class="invoice-info">
                <p>No. Invoice: <span><?php echo htmlspecialchars(string: $invoice['id_invoice']); ?></span></p>
                <p>Nama Klien: <span><?php echo htmlspecialchars($invoice['nama_klien']); ?></span></p>
                <p>Tanggal Cetak Invoice: <span><?php echo htmlspecialchars($invoice['tgl_cetak_invoice']); ?></span>
                </p>
                <p>Tanggal Pelaksanaan: <span><?php echo htmlspecialchars($invoice['tgl_pelaksanaan']); ?></span></p>
                <p>Venue: <span><?php echo htmlspecialchars($invoice['venue']); ?></span></p>
            </section>
            <section class="invoice-table">
                <table>
                    <thead>
                        <tr>
                            <th>Nama Paket</th>
                            <th>Deskripsi</th>
                            <th>Harga</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><?php echo htmlspecialchars($invoice['nama_paket']); ?></td>
                            <td><?php echo nl2br($invoice["deskripsi_paket"]); ?></td>
                            <td><?php echo number_format($invoice['harga_paket'], 2, ',', '.'); ?></td>
                        </tr>
                    </tbody>
                </table>
            </section>
            <section class="invoice-summary">
                <table>
                    <tbody>
                        <tr>
                            <td>Biaya Venue</td>
                            <td><?php echo number_format($invoice['biaya_venue'], 2, ',', '.'); ?></td>
                        </tr>
                        <tr>
                            <td>Subtotal</td>
                            <td><?php echo number_format($invoice['subtotal'], 2, ',', '.'); ?></td>
                        </tr>
                        <tr>
                            <td>Down Payment (50%)</td>
                            <td><?php echo number_format($invoice['down_payment'], 2, ',', '.'); ?></td>
                        </tr>
                        <tr>
                            <td><b>Sisa Pembayaran</b></td>
                            <td><?php echo number_format($invoice['sisa_pembayaran'], 2, ',', '.'); ?></td>
                        </tr>
                    </tbody>
                </table>
            </section>
            <section class="payment-method">
                <p>Metode Pembayaran: <span><?php echo htmlspecialchars($invoice['metode_pembayaran']); ?></span></p>
            </section>
            <section class="action-buttons">
                <button onclick="window.location.href='pagekasir.php'" class="new">New Transaction</button>
            </section>
        </div>
    </div>
</body>

</html>