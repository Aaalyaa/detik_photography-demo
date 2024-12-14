<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detik Photography - Admin Page</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="style-edit.css">
    <script src="edit.js" defer></script>
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

                if (isset($_GET['no_payment'])) {
                    $id = $_GET["no_payment"];

                    $sql = "select * from table_payment where no_payment = '$id'";
                    $hasil = mysqli_query($conn, $sql);
                    $data = mysqli_fetch_assoc($hasil);
                }

                if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update'])) {

                    $no_payment = $_POST["no_payment"];
                    $metode_pembayaran = $_POST["metode_pembayaran"];
                    $nama_akun = $_POST["nama_akun"];

                    $sql = "update table_payment set
        no_payment='$no_payment',
        metode_pembayaran='$metode_pembayaran',
        nama_akun='$nama_akun'
        where no_payment= '$no_payment'";

                    $hasil = mysqli_query($conn, $sql);

                    if ($hasil) {
                        header("Location:payment.php");
                    } else {
                        echo "<div class='alert alert-danger'> Data Gagal disimpan.</div>";

                    }

                }

                if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete'])) {
                    $id = $_POST["no_payment"];

                    $sql = "DELETE FROM table_payment WHERE no_payment = '$id'";
                    $hasil = mysqli_query($conn, $sql);

                    if ($hasil) {
                        header("Location: payment.php");
                        exit;
                    } else {
                        echo "<div class='alert alert-danger'> Data Ini Gagal dihapus.</div>";
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
                <h1>Edit Metode Pembayaran</h1>
                <form id="editForm" action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="POST">
                    <div class="form-group">
                        <label for="no-payment">ID Metode Lama:</label>
                        <input type="text" id="no-payment" name="no_payment" value="<?php echo $data['no_payment']; ?>"
                            readonly />
                    </div>

                    <div class="form-group">
                        <label for="metode-pembayaran">Metode Pembayaran:</label>
                        <input type="text" id="metode-pembayaran" name="metode_pembayaran"
                            value="<?php echo $data['metode_pembayaran']; ?>" required />
                    </div>
                    <div class="form-group">
                        <label for="nama-akun">Nomor atau Nama Akun:</label>
                        <input type="text" id="nama-akun " name="nama_akun" value="<?php echo $data['nama_akun']; ?>"
                            required />
                    </div>

                    <div class="buttons">
                        <button type="submit" name="update" class="btn btn-edit">
                            <i class="fas fa-pencil-alt"></i> Edit Data
                        </button>
                        <button type="button" class="btn btn-back" onclick="window.location.href='payment.php'">
                            <i class="fas fa-arrow-left"></i> Back
                        </button>
                        </button>
                        <button type="submit" name="delete" class="btn btn-delete"
                            onclick="return confirm('Yakin ingin menghapus produk ini?')">
                            <i class="fas fa-trash"></i> Delete Data
                        </button>
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