<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detik Photography - Admin Page</title>
    <link rel="stylesheet" href="style-kasir.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
    <style>
        
    </style>
</head>

<body>
    <?php
    include "koneksi.php";

    $id_karyawan = isset($_GET['id_karyawan']) ? $_GET['id_karyawan'] : '';

    $query = "SELECT * FROM table_karyawan WHERE id_karyawan = '$id_karyawan'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        $kasir = mysqli_fetch_assoc($result);
    } else {
        echo "<p>Data kasir tidak ditemukan.</p>";
        exit; 
    }
    ?>

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
                    <p>Time: <span class="time-box">hh:mm:ss</span></p>
                </div>
            </div>

            <div class="container-2">
                <div class="header-2">
                    <a href="cashier.php" class="back" type="button">Back</a>
                    <a href="edit-kasir.php?id_karyawan=<?php echo $kasir['id_karyawan']; ?>" class="edit" type="button">
                        <i class="fas fa-edit"></i> Edit Profile
                    </a>
                </div>

                <div class="profile-info">
                    <div class="profile-picture">
                        <?php
                        if (!empty($kasir['foto'])) {
                            echo '<img src="data:image/jpeg;base64,' . base64_encode($kasir['foto']) . '" alt="Foto Kasir">';
                        } else {
                            echo '<img src="default-profile.png" alt="Foto Default">';
                        }
                        ?>
                    </div>

                    <h2>
                        <?php echo $kasir['full_nama']; ?>
                        <span class="status">(<?php echo $kasir['username']; ?>)</span>
                    </h2>
                    <p class="role">ID Kasir: <?php echo $kasir['id_karyawan']; ?></p>
                    <div class="contact-info">
                        <p>Email: <?php echo $kasir['email']; ?></p>
                        <p>Phone: <?php echo $kasir['no_telp']; ?></p>
                        <p>Tanggal diterima: <?php echo date('d M, Y', strtotime($kasir['tgl_diterima'])); ?></p>
                    </div>
                </div>

                <div class="section">
                    <h3>Informasi Akun</h3>
                    <div class="info-row">
                        <div>
                            <p>Username</p>
                            <span><?php echo $kasir['username']; ?></span>
                        </div>
                        <div>
                            <p>Password</p>
                            <span><?php echo $kasir['password']; ?></span>
                        </div>
                    </div>
                </div>

                <div class="section">
                    <h3>Informasi Personal</h3>
                    <div class="info-row">
                        <div>
                            <p>Birth date</p>
                            <span><?php echo date('d/m/Y', strtotime($kasir['tgl_lahir'])); ?></span>
                        </div>
                        <div>
                            <p>Address</p>
                            <span><?php echo $kasir['alamat']; ?></span>
                        </div>
                    </div>
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