<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detik Photography - Admin Page</title>
    <link rel="stylesheet" href="style-produk.css">
    <script src=defer></script>
</head>

<body>
    <div class="container">
        <div class="sidebar">
            <button class="logout-btn" onclick="window.location.href='index.html'">
                Log Out
            </button>
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
                <div class="header-2">
                    <h2>Detail Client</h2>
                    <div class="search-container">
                        <form method="GET" action="client.php">
                            <input type="text" name="search" placeholder="Cari nama/kode" required>
                            <button type="submit" class="search-btn">Search</button>
                        </form>
                    </div>
                    <a href="insert-klien.php" class="add-product-btn" role="button">Tambah Client</a>
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
                                            <a href="edit-klien.php?id_klien=<?php echo $data['id_klien']; ?>" class="edit-btn"
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
                                <a href='client.php' class='back-btn'>Kembali ke Daftar Lengkap</a>
                              </div>";
                    }
                    ?>
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