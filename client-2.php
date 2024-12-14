<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detik Photography - Cashier Log Page</title>
    <link rel="stylesheet" href="client-2.css">
</head>

<body>
    <div class="container">
        <div class="button">
            <div class="left-side">
                <img src="Logo Detik 2 - 1.png" alt="Detik Photography Logo">
                <button onclick="window.location.href='client-2.php'" class="input-pemesanan-btn">Tambah Klien</button>
                <button onclick="window.location.href='pagekasir.php'" class="input-pemesanan-btn">Input
                    Pemesanan</button>
                <button onclick="window.location.href='histori-kasir.php'" class="lihat-transaksi-btn">Histori
                    Transaksi</button>
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
            <div class="header-2">
                <h2>Data Client</h2>
                <div class="search-container">
                    <form method="GET" action="client-2.php">
                        <input type="text" name="search" placeholder="Cari nama/kode" required>
                        <button type="submit" class="search-btn">Search</button>
                    </form>
                </div>
                <a href="insert-klien-2.php" class="add-product-btn" role="button">Tambah Client</a>
            </div>

            <div id="table-container">
                <table border="1">
                    <thead>
                        <tr>
                            <th>ID Klien</th>
                            <th>Nama Klien</th>
                            <th>Alamat</th>
                            <th>Email</th>
                            <th>Nomor Wa</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <?php
                    include "koneksi.php";

                    $search = isset($_GET['search']) ? $_GET['search'] : '';
                    $query = "SELECT * FROM table_klien";

                    if (!empty($search)) {
                        $query .= " WHERE id_klien LIKE '%$search%' OR nama_klien LIKE '%$search%'";
                    }

                    $result = $conn->query($query);

                    if ($result->num_rows > 0) {
                        while ($data = $result->fetch_assoc()) {
                            ?>
                            <tbody>
                                <tr>
                                    <td><?php echo $data["id_klien"]; ?></td>
                                    <td><?php echo $data["nama_klien"]; ?></td>
                                    <td><?php echo $data["alamat"]; ?></td>
                                    <td><?php echo $data["email"]; ?></td>
                                    <td><?php echo $data["no_wa"]; ?></td>
                                    <td>
                                        <a href="edit-klien-2.php?id_klien=<?php echo $data['id_klien']; ?>" class="edit-btn"
                                            role="button">Edit</a>
                                </tr>
                            </tbody>
                            <?php
                        }
                    } else {
                        echo "<tbody><tr><td colspan='5' style='text-align:center;'>Data ini tidak tersedia</td></tr></tbody>";
                    }
                    ?>
                </table>

                <?php
                if (!empty($search)) {
                    echo "<div style='text-align:center; margin-top: 20px;'>
                                <a href='client-2.php' class='back-btn'>Kembali ke Daftar Lengkap</a>
                              </div>";
                }
                ?>
            </div>
        </div>
</body>

</html>