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
        <!-- Sidebar -->
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

                if (isset($_GET['id_klien'])) {
                    $id = $_GET["id_klien"];

                    $sql = "select * from table_klien where id_klien = '$id'";
                    $hasil = mysqli_query($conn, $sql);
                    $data = mysqli_fetch_assoc($hasil);
                }

                if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update'])) {

                    $id_klien = $_POST["id_klien"];
                    $nama_klien = $_POST["nama_klien"];
                    $alamat = $_POST["alamat"];
                    $email = $_POST["email"];
                    $no_wa = $_POST["no_wa"];

                    $sql = "update table_klien set
        id_klien='$id_klien',
        nama_klien='$nama_klien',
        alamat='$alamat',
        email='$email', 
        no_wa='$no_wa'
        where id_klien= '$id_klien'";

                    $hasil = mysqli_query($conn, $sql);

                    if ($hasil) {
                        header("Location:client.php");
                    } else {
                        echo "<div class='alert alert-danger'> Data Gagal disimpan.</div>";
                    }
                }

                if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete'])) {
                    $id = $_POST["id_klien"];

                    $sql = "DELETE FROM table_klien WHERE id_klien = '$id'";
                    $hasil = mysqli_query($conn, $sql);

                    if ($hasil) {
                        header("Location:client.php");
                        exit;
                    } else {
                        echo "<div class='alert alert-danger'> Data Gagal dihapus.</div>";
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
                <h1>Edit Data Client</h1>
                <form id="editForm" action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="POST">

                    <div class="form-group">
                        <label for="id-klien">ID Client:</label>
                        <input type="text" id="id_klien" name="id_klien" value="<?php echo $data['id_klien']; ?>"
                            readonly />
                    </div>
                    <div class="form-group">
                        <label for="nama-klien">Nama Client:</label>
                        <input type="text" id="nama_klien" name="nama_klien" value="<?php echo $data['nama_klien']; ?>"
                            required />
                    </div>
                    <div class="form-group">
                        <label for="alamat">Alamat:</label>
                        <input type="text" id="alamat" name="alamat" value="<?php echo $data['alamat']; ?>" required />
                    </div>
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="text" id="email " name="email" value="<?php echo $data['email']; ?>" required />
                    </div>
                    <div class="form-group">
                        <label for="no-wa">Nomor Whatsapp/Telepon:</label>
                        <input type="text" id="no_wa " name="no_wa" value="<?php echo $data['no_wa']; ?>" required />
                    </div>

                    <div class="buttons">
                        <button type="submit" name="update" class="btn btn-edit">
                            <i class="fas fa-pencil-alt"></i> Edit Data
                        </button>
                        <button type="button" class="btn btn-back" onclick="window.location.href='client.php'">
                            <i class="fas fa-arrow-left"></i> Back
                        </button>
                        </button>
                        <button type="submit" name="delete" class="btn btn-delete"
                            onclick="return confirm('Yakin ingin menghapus data ini?')">
                            <i class="fas fa-trash"></i> Delete Data
                        </button>
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