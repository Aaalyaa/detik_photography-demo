<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detik Photography - Admin Page</title>
    <link rel="stylesheet" href="dashboard.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
</head>

<body>
    <div class="container">
        <div class="sidebar">
            <button class="logout-btn" onclick="window.location.href='index.html'">Log Out</button>
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

            <div class="container-2">
                <div class="stats-section">
                    <h2>Basic Information</h2>

                    <?php
                    $queryTotalPemesanan = "SELECT COUNT(*) as total_pemesanan FROM invoice";
                    $queryTotalKlien = "SELECT COUNT(*) as total_klien FROM table_klien";
                    $queryTotalVenue = "SELECT COUNT(*) as total_venue FROM table_venue";
                    $queryTotalPaket = "SELECT COUNT(*) as total_paket FROM table_paket";

                    $resultPemesanan = $conn->query($queryTotalPemesanan);
                    $resultKlien = $conn->query($queryTotalKlien);
                    $resultVenue = $conn->query($queryTotalVenue);
                    $resultPaket = $conn->query($queryTotalPaket);

                    $totalPemesanan = $resultPemesanan->fetch_assoc()['total_pemesanan'];
                    $totalKlien = $resultKlien->fetch_assoc()['total_klien'];
                    $totalVenue = $resultVenue->fetch_assoc()['total_venue'];
                    $totalPaket = $resultPaket->fetch_assoc()['total_paket'];
                    ?>

                    <div class="stat-card">
                        <span class="icon">🛒</span>
                        <h3>Total pemesanan</h3>
                        <p><?php echo $totalPemesanan; ?> Penjualan</p>
                    </div>
                    <div class="stat-card">
                        <span class="icon">👥</span>
                        <h3>Total klien</h3>
                        <p><?php echo $totalKlien; ?> Klien</p>
                    </div>
                    <div class="stat-card">
                        <span class="icon">📍</span>
                        <h3>Total venue</h3>
                        <p><?php echo $totalVenue; ?> Venue</p>
                    </div>
                    <div class="stat-card">
                        <span class="icon">📦</span>
                        <h3>Total paket</h3>
                        <p><?php echo $totalPaket; ?> Paket</p>
                    </div>
                </div>

                <div class="stats-section-2">
                    <h2>Admin Information</h2>
                    <div class="admin">
                        <?php
                        $adminQuery = "SELECT * FROM table_karyawan WHERE username = '$username'";
                        $adminResult = mysqli_query($conn, $adminQuery);
                        $adminData = mysqli_fetch_assoc($adminResult);

                        $foto = !empty($adminData['foto']) ? 'data:image/jpeg;base64,' . base64_encode($adminData['foto']) : 'White User Icon.png';
                        ?>

                        <div class="profile-info">
                            <div class="profile-photo">
                                <img src="<?php echo $foto; ?>" alt="Admin Photo">
                            </div>

                            <h2>
                                <?php echo $adminData['full_nama']; ?>
                                <span class="status">(<?php echo $adminData['username']; ?>)</span>
                            </h2>
                            <p class="role">ID Admin: <?php echo $adminData['id_karyawan']; ?></p>
                            <div class="contact-info">
                                <p>Email: <?php echo $adminData['email']; ?></p>
                                <p>Phone: <?php echo $adminData['no_telp']; ?></p>
                                <p>Tanggal diterima:
                                    <?php echo date('d M, Y', strtotime($adminData['tgl_diterima'])); ?>
                                </p>
                            </div>
                        </div>

                        <div class="section">
                            <h3>Informasi Akun</h3>
                            <div class="info-row">
                                <div>
                                    <p>Username</p>
                                    <span><?php echo $adminData['username']; ?></span>
                                </div>
                                <div>
                                    <p>Password</p>
                                    <span><?php echo $adminData['password']; ?></span>
                                </div>
                            </div>
                        </div>

                        <div class="section">
                            <h3>Informasi Personal</h3>
                            <div class="info-row">
                                <div>
                                    <p>Birth date</p>
                                    <span><?php echo date('d/m/Y', strtotime($adminData['tgl_lahir'])); ?></span>
                                </div>
                                <div>
                                    <p>Address</p>
                                    <span><?php echo $adminData['alamat']; ?></span>
                                </div>
                            </div>
                        </div>

                        <div class="header-2">
                            <a href="edit-admin.php?id_karyawan=<?php echo $adminData['id_karyawan']; ?>" class="edit"
                                type="button">
                                <i class="fas fa-edit"></i> Edit Profile
                            </a>
                        </div>
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