<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detik Photography - Admin Page</title>
    <link rel="stylesheet" href="style-insert.css">
    <script src="" defer></script>
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

                if ($_SERVER["REQUEST_METHOD"] == "POST") {

                    $nama_klien = $_POST["nama_klien"];
                    $alamat = $_POST["alamat"];
                    $email = $_POST["email"];
                    $no_wa = $_POST["no_wa"];

                    $sql = "insert into table_klien (nama_klien, alamat, email, no_wa) values
		('$nama_klien', '$alamat', '$email','$no_wa')";

                    $hasil = mysqli_query($conn, $sql);

                    if ($hasil) {
                        header("Location:client.php");
                    } else {
                        echo "<div class='alert alert-danger'> Data Gagal disimpan.</div>";
                    }
                }
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
                <div class="form-container">
                    <div class="header">
                        <h1>Tambah Klien</h1>
                        <button class="back-button" onclick="window.location.href='client.php'">Back</button>
                    </div>

                    <form id="form-produk" action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="POST">

                        <label for="nama-klien">Nama Klien:</label>
                        <input type="text" id="nama_klien" name="nama_klien" placeholder="Nama Klien">

                        <label for="alamat">Alamat Klien:</label>
                        <input type="text" id="alamat" name="alamat" placeholder="Alamat">

                        <label for="email">Email Klien:</label>
                        <input type="text" id="email" name="email" placeholder="Email Klien">

                        <label for="no-wa">Nomor Whatsapp/Telepon Klien:</label>
                        <input type="text" id="no-wa" name="no_wa" placeholder="Nomor Telepon Klien">

                        <button type="submit" class="submit-button">Add Klien</button>
                    </form>
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