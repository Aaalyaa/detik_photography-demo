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
    <title>Detik Photography - Admin Page</title>
    <link rel="stylesheet" href="invoice-admin.css">
    <script src=defer></script>
</head>

<body>
    <div class="container">
        <div class="sidebar">
            <button class="logout-btn" onclick="window.location.href='index.html'">
                Log Out
            </button>
            <div class="profile-section">
                <?php
                require "koneksi.php";

                $username = $_SESSION['username'];

                $adminQuery = "SELECT * FROM table_karyawan WHERE username = '$username'";
                $adminResult = mysqli_query($conn, $adminQuery);
                $adminData = mysqli_fetch_assoc($adminResult);

                $foto = !empty($adminData['foto']) ? 'data:image/jpeg;base64,' . base64_encode($adminData['foto']) : 'White User Icon.png';
                ?>

                <div class="profile-pic">
                    <img src="<?php echo $foto; ?>" alt="Admin Photo">
                </div>

                <?php
                require "koneksi.php";
                $username = $_SESSION['username'];
                $result = $conn->query("SELECT id_karyawan, full_nama FROM table_karyawan WHERE username = '$username'");
                $row = $result->fetch_assoc();
                $id_karyawan = $row['id_karyawan'];
                $full_nama = $row['full_nama'];
                ?>

                <p class="admin-text">Admin:</p>
                <input type="text" class="admin-name" value="<?php echo $full_nama; ?>" readonly>
            </div>
            <nav class="sidebar-menu">
                <ul>
                    <li><a href="dashboard.php">Dashboard</a></li>
                    <li><a href="produk.php">Lihat Paket</a></li>
                    <li><a href="venue.php">Lihat Venue</a></li>
                    <li><a href="payment.php">Lihat Metode Pembayaran</a></li>
                    <li><a href="client.php">Profil Client</a></li>
                    <li><a href="cashier.php">Profil Kasir</a></li>
                    <li><a href="histori-admin.php">Data Penjualan</a></li>
                </ul>
            </nav>
        </div>

        <div class="main-content">
            <div class="header">
                <img src="Logo Detik 2 - 1.png" alt="Detik Photography Logo" class="logo">
                <div class="today">
                    <p>Date: <span class="date-box">yyyy/mm/dd</span></p>
                    <p>Time: <span class="time-box">hh.mm.ss</span></p>
                </div>
            </div>

            <div class="invoice-container">
                <header class="invoice-header">
                    <h1>Detik Photography</h1>
                    <h2>Invoice</h2>
                    <div class="cashier-name">Nama Kasir: <?php echo htmlspecialchars($invoice['full_nama']); ?></div>
                </header>
                <section class="invoice-info">
                    <p>No. Invoice: <span><?php echo htmlspecialchars($invoice['id_invoice']); ?></span></p>
                    <p>Nama Klien: <span><?php echo htmlspecialchars($invoice['nama_klien']); ?></span></p>
                    <p>Tanggal Cetak Invoice:
                        <span><?php echo htmlspecialchars($invoice['tgl_cetak_invoice']); ?></span>
                    </p>
                    <p>Tanggal Pelaksanaan: <span><?php echo htmlspecialchars($invoice['tgl_pelaksanaan']); ?></span>
                    </p>
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
                    <p>Metode Pembayaran: <span><?php echo htmlspecialchars($invoice['metode_pembayaran']); ?></span>
                    </p>
                </section>
                <section class="action-buttons">
                    <button onclick="window.location.href='histori-admin.php'">Back</button>
                </section>
            </div>
        </div>
    </div>
</body>
</html>

<script>
    function updateDateTime() {
        const dateBox = document.querySelector('.date-box');
        const timeBox = document.querySelector('.time-box');

        // Set zona waktu ke WIB (GMT+7)
        const now = new Date();
        const options = { timeZone: 'Asia/Jakarta' };
        const wibDate = new Intl.DateTimeFormat('en-CA', options).format(now);
        const wibTime = now.toLocaleTimeString('en-GB', { timeZone: 'Asia/Jakarta', hour12: false });

        // Format sesuai yyyy/mm/dd dan hh.mm.ss
        const formattedDate = wibDate.replace(/-/g, '/');
        const formattedTime = wibTime.replace(/:/g, '.');

        // Update DOM
        if (dateBox) dateBox.textContent = formattedDate;
        if (timeBox) timeBox.textContent = formattedTime;
    }

    // Update setiap detik
    setInterval(updateDateTime, 1000);
    // Jalankan sekali saat halaman dimuat
    updateDateTime();
</script>