<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detik Photography - Cashier Log Page</title>
    <link rel="stylesheet" href="insert-klien-2.css">
</head>
<body>
<div class="container">
    <div class="button">
        <div class="left-side">
            <img src="Logo Detik 2 - 1.png" alt="Detik Photography Logo">
            <button onclick="window.location.href='client-2.php'" class="input-pemesanan-btn">Tambah Klien</button>
            <button onclick="window.location.href='pagekasir.php'" class="input-pemesanan-btn">Input Pemesanan</button>
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

    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        $nama_klien= $_POST["nama_klien"];
        $alamat= $_POST["alamat"];
        $email= $_POST["email"];
        $no_wa= $_POST["no_wa"];

        $sql="insert into table_klien (nama_klien, alamat, email, no_wa) values
		('$nama_klien', '$alamat', '$email','$no_wa')";

        $hasil=mysqli_query($conn,$sql);

        if ($hasil) {
            header("Location:client-2.php");
        }
        else {
            echo "<div class='alert alert-danger'> Data Gagal disimpan.</div>";

        }

    }
    ?>
            <div class="form-container">
                <div class="header">
                    <h2>Tambah Klien</h2>
                    <button class="back-button" onclick="window.location.href='client-2.php'">Back</button>
                </div>
                
                <form id="form-produk" action="<?php echo $_SERVER["PHP_SELF"];?>" method="POST">

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
