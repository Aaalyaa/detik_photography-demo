<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detik Photography - Cashier Log Page</title>
    <link rel="stylesheet" href="edit-klien-2.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>

<body>
    <div class="container">
        <div class="button">
            <div class="left-side">
                <img src="Logo Detik 2 - 1.png" alt="Detik Photography Logo">
                <button onclick="window.location.href='client-2.php'" class="input-pemesanan-btn">Tambah Klien</button>
                <button onclick="window.location.href='pagekasir.php'" class="input-pemesanan-btn">Input
                    Pemesanan</button>
                <button onclick="lihatTransaksi()" class="lihat-transaksi-btn">Histori Transaksi</button>
            </div>

            <div class="right-buttons">
                <?php
                session_start();
                require "koneksi.php";
                $username = $_SESSION['username'];
                $result = $conn->query("SELECT id_karyawan, full_nama FROM table_karyawan WHERE username = '$username'");
                $row = $result->fetch_assoc();
                $id_karyawan = $row['id_karyawan'];
                $full_nama = $row['full_nama'];
                ?>

                <span>Kasir:</span><span id="nama-kasir"><?php echo htmlspecialchars($full_nama); ?></span>
                <button onclick="window.location.href='index.html'">
                    Log Out
                </button>
            </div>
        </div>

        <div class="container-2">
            <?php
            include "koneksi.php";

            if (isset($_GET['id_klien'])) {
                $id = $_GET["id_klien"];

                $sql = "select * from table_klien where id_klien = '$id'";
                $hasil = mysqli_query($conn, $sql);
                $data = mysqli_fetch_assoc($hasil);
            }

            if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update'])) {

                $id_lama = $_POST["id_lama"];
                $id_klien = $_POST["id_klien"];
                $nama_klien = $_POST["nama_klien"];
                $alamat = $_POST["alamat"];
                $email = $_POST["email"];
                $no_wa = $_POST["no_wa"];

                $sql = "update table_klien set
        nama_klien='$nama_klien',
        alamat='$alamat',
        email='$email', 
        no_wa='$no_wa'
        where id_klien= '$id_klien'";

                $hasil = mysqli_query($conn, $sql);

                if ($hasil) {
                    header("Location:client-2.php");
                } else {
                    echo "<div class='alert alert-danger'> Data Gagal disimpan.</div>";

                }

            }

            if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete'])) {
                $id = $_POST["id_klien"];

                $sql = "DELETE FROM table_klien WHERE id_klien = '$id'";
                $hasil = mysqli_query($conn, $sql);

                if ($hasil) {
                    header("Location:client-2.php");
                    exit;
                } else {
                    echo "<div class='alert alert-danger'> Data Gagal dihapus.</div>";
                }
            }

            ?>

            <h1>Edit Data Client</h1>
            <form id="editForm" action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="POST">
                <div class="form-group">
                    <label for="id-lama">ID Client:</label>
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
                    <button type="button" class="btn btn-back" onclick="window.location.href='client-2.php'">
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