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

                if (isset($_GET['id_karyawan'])) {
                    $id = $_GET["id_karyawan"];

                    $sql = "SELECT * FROM table_karyawan WHERE id_karyawan = '$id'";
                    $hasil = mysqli_query($conn, $sql);
                    $data = mysqli_fetch_assoc($hasil);
                }

                if ($_SERVER["REQUEST_METHOD"] == "POST") {
                    if (isset($_POST['update'])) {
                        $id_karyawan = $_POST["id_karyawan"];
                        $full_nama = $_POST["full_nama"];
                        $tgl_diterima = $_POST["tgl_diterima"];
                        $alamat = $_POST["alamat"];
                        $tgl_lahir = $_POST["tgl_lahir"];
                        $email = $_POST["email"];
                        $no_telp = $_POST["no_telp"];
                        $username = $_POST["username"];
                        $password = $_POST["password"];

                        if (!empty($_FILES['foto']['tmp_name'])) {
                            $foto = addslashes(file_get_contents($_FILES['foto']['tmp_name']));
                            $sql = "UPDATE `table_karyawan` SET `full_nama`='$full_nama', `username`='$username', `password`='$password', `email`='$email', `no_telp`='$no_telp', `alamat`='$alamat', `tgl_lahir`='$tgl_lahir', `tgl_diterima`='$tgl_diterima', `foto`='$foto' WHERE `id_karyawan` = '$id_karyawan'";
                        } else {
                            $sql = "UPDATE `table_karyawan` SET `full_nama`='$full_nama', `username`='$username', `password`='$password', `email`='$email', `no_telp`='$no_telp', `alamat`='$alamat', `tgl_lahir`='$tgl_lahir', `tgl_diterima`='$tgl_diterima' WHERE `id_karyawan` = '$id_karyawan'";
                        }

                        $hasil = mysqli_query($conn, $sql);

                        if ($hasil) {
                            header("Location:cashier.php");
                        } else {
                            echo "<div class='alert alert-danger'> Data Gagal disimpan.</div>";
                        }
                    }

                    if (isset($_POST['delete'])) {
                        $id = $_POST["id_karyawan"];

                        $sql = "DELETE FROM table_karyawan WHERE id_karyawan = '$id'";
                        $hasil = mysqli_query($conn, $sql);

                        if ($hasil) {
                            header("Location: cashier.php");
                            exit;
                        } else {
                            echo "<div class='alert alert-danger'> Data Gagal dihapus.</div>";
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
                <h1>Edit Data Kasir</h1>
                <form id="editForm"
                    action="<?php echo $_SERVER["PHP_SELF"] . "?id_karyawan=" . $data['id_karyawan']; ?>" method="POST"
                    enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="id-karyawan">ID Kasir:</label>
                        <input type="text" id="id-karyawan" name="id_karyawan"
                            value="<?php echo $data['id_karyawan']; ?>" readonly />
                    </div>

                    <div class="form-group">
                        <label for="full-nama">Nama Kasir:</label>
                        <input type="text" id="full-nama" name="full_nama" value="<?php echo $data['full_nama']; ?>"
                            required />
                    </div>
                    <div class="form-group">
                        <label for="tgl-diterima">Tanggal Diterima Kerja:</label>
                        <input type="date" id="tgl-diterima" name="tgl_diterima"
                            value="<?php echo $data['tgl_diterima']; ?>" required />
                    </div>

                    <div class="form-group">
                        <label for="foto">Foto Kasir:</label>
                        <div class="current-photo">
                            <?php if (!empty($data['foto'])): ?>
                                <img src="data:image/jpeg;base64,<?php echo base64_encode($data['foto']); ?>"
                                    alt="Foto Kasir" style="width:150px;height:150px;object-fit:cover;">
                            <?php else: ?>
                                <p>Foto belum tersedia</p>
                            <?php endif; ?>
                        </div>
                        <input type="file" id="foto" name="foto" accept="image/*" onchange="previewFoto(event)">
                        <div id="preview-container">
                            <img id="preview-foto" style="width:150px;height:150px;object-fit:cover;display:none;">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="alamat">Alamat Kasir:</label>
                        <input type="text" id="alamat" name="alamat" value="<?php echo $data['alamat']; ?>" required />
                    </div>

                    <div class="form-group">
                        <label for="tgl-lahir">Tanggal Lahir:</label>
                        <input type="date" id="tgl-lahir" name="tgl_lahir" value="<?php echo $data['tgl_lahir']; ?>"
                            required />
                    </div>

                    <div class="form-group">
                        <label for="email">Email Kasir:</label>
                        <input type="email" id="email" name="email" value="<?php echo $data['email']; ?>" required />
                    </div>

                    <div class="form-group">
                        <label for="no-telp">Nomor Telepon Kasir:</label>
                        <input type="number" id="no-telp" name="no_telp" value="<?php echo $data['no_telp']; ?>"
                            required />
                    </div>

                    <div class="form-group">
                        <label for="username">Username Kasir:</label>
                        <input type="text" id="username" name="username" value="<?php echo $data['username']; ?>"
                            required />
                    </div>

                    <div class="form-group">
                        <label for="password">Password Kasir:</label>
                        <input type="text" id="password" name="password" value="<?php echo $data['password']; ?>"
                            required />
                    </div>

                    <div class="buttons">
                        <button type="submit" name="update" class="btn btn-edit">
                            <i class="fas fa-pencil-alt"></i> Edit Data
                        </button>
                        <button type="button" class="btn btn-back"
                            onclick="window.location.href='detail-kasir.php?id_karyawan=<?php echo $data['id_karyawan']; ?>'">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </button>
                        <button type="submit" name="delete" class="btn btn-delete"
                            onclick="return confirm('Yakin ingin menghapus data kasir ini?')">
                            <i class="fas fa-trash"></i> Hapus Data Kasir
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>

</html>

<script>
    function previewFoto(event) {
        const previewContainer = document.getElementById("preview-container");
        const previewFoto = document.getElementById("preview-foto");
        const file = event.target.files[0];

        if (file) {
            const reader = new FileReader();
            reader.onload = function (e) {
                previewFoto.src = e.target.result;
                previewFoto.style.display = "block";
            };
            reader.readAsDataURL(file);
        }
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