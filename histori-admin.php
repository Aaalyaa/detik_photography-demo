<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detik Photography - Admin Page</title>
    <link rel="stylesheet" href="histori-admin.css">
    <script src=defer></script>
</head>

<body>
    <div class="container">
        <!-- Sidebar -->
        <div class="sidebar">
            <button class="logout-btn" onclick="window.location.href='index.html'">
                Log Out
            </button>
            <div class="profile-section">
            <?php
                session_start();
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

                <i class="fas fa-user"></i>
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

            <div class="container-2">
                <div class="header-2">
                    <div>
                        <form method="GET" action="histori-admin.php">
                            <input type="date" name="filter_date"
                                value="<?php echo isset($_GET['filter_date']) ? $_GET['filter_date'] : ''; ?>">
                            <button type="submit">Filter</button>
                            <?php if (isset($_GET['filter_date']) && !empty($_GET['filter_date'])) { ?>
                                <a href="histori-admin.php" class="reset-btn">Reset</a>
                            <?php } ?>
                        </form>
                    </div>
                </div>

                <table>
                    <thead>
                        <tr>
                            <th>Tgl Transaksi</th>
                            <th>ID Invoice</th>
                            <th>Nama Kasir</th>
                            <th>Nama Klien</th>
                            <th>Lokasi Venue</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <?php
                    include "koneksi.php";

                    $filter_date = isset($_GET['filter_date']) ? $_GET['filter_date'] : '';

                    $query = "SELECT invoice.id_invoice, invoice.tgl_cetak_invoice, table_karyawan.full_nama, table_klien.nama_klien, table_venue.lokasi 
                        FROM (((invoice
                        INNER JOIN table_karyawan ON invoice.id_karyawan = table_karyawan.id_karyawan)
                        INNER JOIN table_klien ON invoice.id_klien = table_klien.id_klien)
                        INNER JOIN table_venue ON invoice.id_venue = table_venue.id_venue)";

                    if (!empty($filter_date)) {
                        $query .= " WHERE DATE(invoice.tgl_cetak_invoice) = '$filter_date'";
                    }

                    $query .= " ORDER BY id_invoice DESC";
                    $result = $conn->query($query);

                    while ($data = $result->fetch_assoc()) {
                        ?>
                        <tbody>
                            <tr>
                                <td><?php echo $data["tgl_cetak_invoice"]; ?></td>
                                <td><?php echo $data["id_invoice"]; ?></td>
                                <td><?php echo $data["full_nama"]; ?></td>
                                <td><?php echo $data["nama_klien"]; ?></td>
                                <td><?php echo $data["lokasi"]; ?></td>
                                <td><a href="invoice-admin.php?id_invoice=<?php echo htmlspecialchars($data['id_invoice']); ?>"
                                        class="action-button" role="button">See details</a></td>
                            </tr>
                        </tbody>
                        <?php
                    }
                    ?>
                </table>

                <?php if ($result->num_rows === 0) { ?>
                    <div style="text-align: center; margin-top: 20px;">
                        <p>Data tidak ditemukan.</p>
                    </div>
                <?php } ?>
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