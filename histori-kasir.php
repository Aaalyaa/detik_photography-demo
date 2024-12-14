<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detik Photography - Cashier Log Page</title>
    <link rel="stylesheet" href="histori-kasir.css">
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
                <div class="header">
                    <div>
                        <form method="GET" action="histori-kasir.php">
                            <input type="date" name="filter_date"
                                value="<?php echo isset($_GET['filter_date']) ? $_GET['filter_date'] : ''; ?>">
                            <button type="submit">Filter</button>
                            <?php if (isset($_GET['filter_date']) && !empty($_GET['filter_date'])) { ?>
                                <a href="histori-kasir.php" class="reset-btn">Reset</a>
                            <?php } ?>
                        </form>
                    </div>
                </div>

                <table>
                    <thead>
                        <tr>
                            <th>Tgl Transaksi</th>
                            <th>ID Invoice</th>
                            <th>Nama Kasir</th>
                            <th>Nama Klien</th>
                            <th>Lokasi Venue</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <?php
                    include "koneksi.php";

                    $filter_date = isset($_GET['filter_date']) ? $_GET['filter_date'] : '';

                    $query = "SELECT invoice.id_invoice, invoice.tgl_cetak_invoice, table_karyawan.full_nama, table_klien.nama_klien, table_venue.lokasi 
                        FROM (((invoice
                        INNER JOIN table_karyawan ON invoice.id_karyawan = table_karyawan.id_karyawan)
                        INNER JOIN table_klien ON invoice.id_klien = table_klien.id_klien)
                        INNER JOIN table_venue ON invoice.id_venue = table_venue.id_venue)";

                    if (!empty($filter_date)) {
                        $query .= " WHERE DATE(invoice.tgl_cetak_invoice) = '$filter_date'";
                    }

                    $query .= " ORDER BY id_invoice DESC";
                    $result = $conn->query($query);

                    while ($data = $result->fetch_assoc()) {
                        ?>
                        <tbody>
                            <tr>
                                <td><?php echo $data["tgl_cetak_invoice"]; ?></td>
                                <td><?php echo $data["id_invoice"]; ?></td>
                                <td><?php echo $data["full_nama"]; ?></td>
                                <td><?php echo $data["nama_klien"]; ?></td>
                                <td><?php echo $data["lokasi"]; ?></td>
                                <td><a href="invoice-kasir.php?id_invoice=<?php echo htmlspecialchars($data['id_invoice']); ?>"
                                        class="action-button" role="button">See details</a></td>
                            </tr>
                        </tbody>
                        <?php
                    }
                    ?>
                </table>

                <?php if ($result->num_rows === 0) { ?>
                    <div style="text-align: center; margin-top: 20px;">
                        <p>Data tidak ditemukan.</p>
                    </div>
                <?php } ?>
            </div>
    </div>
</body>

</html>