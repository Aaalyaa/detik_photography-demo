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
                    $full_nama = $_POST["full_nama"];
                    $tgl_diterima = $_POST["tgl_diterima"];
                    $alamat = $_POST["alamat"];
                    $tgl_lahir = $_POST["tgl_lahir"];
                    $email = $_POST["email"];
                    $no_telp = $_POST["no_telp"];
                    $username = $_POST["username"];
                    $password_unconfirmed = $_POST["password-unconfirmed"];
                    $password = $_POST["password"];

                    if (empty($full_nama) || empty($tgl_diterima) || empty($alamat) || empty($email) || empty($no_telp) || empty($username) || empty($password)) {
                        echo "<script>alert('Semua field wajib diisi!');</script>";
                    } else {
                        if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
                            $foto = addslashes(file_get_contents($_FILES['foto']['tmp_name']));
                        } else {
                            $foto = null;
                        }

                        $sql = "INSERT INTO table_karyawan (full_nama, tgl_diterima, alamat, tgl_lahir, email, no_telp, username, password, foto, role) 
                VALUES ('$full_nama', '$tgl_diterima', '$alamat', '$tgl_lahir', '$email', '$no_telp', '$username', '$password', '$foto', 'kasir')";
                        $hasil = mysqli_query($conn, $sql);

                        if ($hasil) {
                            header("Location:cashier.php");
                            exit;
                        } else {
                            echo "<script>alert('Data gagal disimpan.');</script>";
                        }
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
                        <h1>Tambah Kasir</h1>
                        <button class="back-button" onclick="window.location.href='cashier.php'">Back</button>
                    </div>

                    <form id="form-kasir" action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="POST"
                        enctype="multipart/form-data">
                        <label for="full-nama">Nama Kasir:</label>
                        <input type="text" id="full-nama" name="full_nama" placeholder="Nama Kasir" required>

                        <label for="tgl-diterima">Tanggal Diterima Kerja:</label>
                        <input type="date" id="tgl-diterima" name="tgl_diterima" required>

                        <label for="alamat">Alamat Kasir:</label>
                        <input type="text" id="alamat" name="alamat" placeholder="Alamat" required>

                        <label for="tgl-lahir">Tanggal Lahir:</label>
                        <input type="date" id="tgl-lahir" name="tgl_lahir" required>

                        <label for="email">Email Kasir:</label>
                        <input type="email" id="email" name="email" placeholder="Email Kasir" required>

                        <label for="no-telp">Nomor Telepon Kasir:</label>
                        <input type="number" id="no-telp" name="no_telp" placeholder="Nomor Telepon" required>

                        <label for="username">Username Kasir:</label>
                        <input type="text" id="username" name="username" placeholder="Username" required>

                        <label for="password-uncofirmed">Password Kasir (Unconfirmed):</label>
                        <input type="text" id="password-unconfirmed" name="password-unconfirmed"
                            placeholder="Enter Password" required>

                        <label for="password-confirmed">Password Kasir (Confirmed):</label>
                        <input type="password" id="password-confirmed" name="password" placeholder="Confirm Password"
                            required>

                        <label for="foto">Foto Kasir:</label>
                        <input type="file" id="foto" name="foto" accept="image/*" required>

                        <button type="submit" class="submit-button">Tambahkan Kasir</button>
                    </form>

                </div>
            </div>
</body>

</html>

<script>
    window.onload = function () {
        const today = new Date().toISOString().split('T')[0];
        document.getElementById('tgl-diterima').value = today;
    };

    function validatePasswords() {
        const passwordUnconfirmed = document.getElementById("password-unconfirmed").value;
        const passwordConfirmed = document.getElementById("password-confirmed").value;

        if (passwordUnconfirmed !== passwordConfirmed) {
            alert("Password tidak cocok! Silakan periksa kembali.");
            return false;
        }
        return true;
    }
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