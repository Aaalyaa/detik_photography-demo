<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detik Photography - Cashier Log Page</title>
    <link rel="stylesheet" href="kasir.css">
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

                <span>Kasir:</span><span id="nama-kasir"><?php echo $full_nama; ?></span>
                <button onclick="window.location.href='index.html'">
                    Log Out
                </button>
            </div>
        </div>

        <form action="proses_transaksi.php" method="POST">
            <div class="header">
                <div>
                    <?php
                    require 'koneksi.php';

                    $stmt = $conn->prepare("CALL GenerateInvoiceID(@new_id)");
                    $stmt->execute();

                    $result = $conn->query("SELECT @new_id AS new_id");
                    $row = $result->fetch_assoc();
                    $id_invoice = $row['new_id'];
                    ?>
                    <span>ID Invoice:</span><input type="text" id="id-invoice" name="id_invoice"
                        value="<?php echo htmlspecialchars($id_invoice); ?>" readonly>
                </div>
                <div>
                    <?php
                    require "koneksi.php";
                    $username = $_SESSION['username'];

                    $result = $conn->query("SELECT id_karyawan, full_nama FROM table_karyawan WHERE username = '$username'");
                    $row = $result->fetch_assoc();
                    $id_karyawan = $row['id_karyawan'];
                    $full_nama = $row['full_nama'];
                    ?>
                    <span>Nama Kasir:</span><input type="text" id="kasir" name="nama_kasir"
                        value="<?php echo $full_nama; ?>" readonly>
                </div>
                <div><span>Tanggal Transaksi:</span><input type="date" id="tgl-cetak" name="tgl_cetak_invoice" readonly>
                </div>
                <div><span>Tanggal Pelaksanaan:</span><input type="date" id="pelaksanaan" name="tgl_pelaksanaan"></div>

                <input type="hidden" name="id_karyawan" value="<?php echo $id_karyawan; ?>">
            </div>

            <div class="client-info">
                <div>
                    <span>Nama Klien:</span>
                    <input type="text" placeholder="Nama Klien" id="nama-klien" name="nama_klien" list="list-klien"
                        oninput="setIdKlien()">
                    <input type="hidden" id="id_klien_hidden" name="id_klien"></datalist>
                    <datalist id="list-klien">
                        <?php
                        include 'koneksi.php';
                        $result = $conn->query("SELECT id_klien, nama_klien FROM table_klien");
                        while ($row = $result->fetch_assoc()) {
                            echo "<option value='{$row['nama_klien']}' data-id='{$row['id_klien']}'></option>";
                        }
                        ?>
                    </datalist><br>
                </div>
                <span>Lokasi Venue: </span>
                <select id="id-venue" name="id_venue" onchange="isiDataVenue()">
                    <option value="" selected>Pilih venue yang tersedia</option>
                    <?php
                    $result = $conn->query("SELECT id_venue, lokasi, biaya FROM table_venue");
                    while ($row = $result->fetch_assoc()) {
                        echo "<option value='{$row['id_venue']}' data-biaya='{$row['biaya']}'>{$row['lokasi']}</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="actions">
                <input type="text" placeholder="Nama Paket" id="nama-paket" list="list-paket" oninput="setIdPaket()">
                <input type="hidden" id="id-paket-hidden" name="id_paket">
                <datalist id="list-paket">
                    <?php
                    include 'koneksi.php';
                    $result = $conn->query("SELECT id_paket, nama_paket FROM table_paket");
                    while ($row = $result->fetch_assoc()) {
                        echo "<option value='{$row['nama_paket']}' data-id='{$row['id_paket']}'></option>";
                    }
                    ?>
                </datalist>
                <button type="button" onclick="tambahPaket()" class="save">Save</button>
                <br>
            </div>

            <table class="items-table">
                <thead>
                    <tr>
                        <th>Kode Paket</th>
                        <th>Nama Paket</th>
                        <th>Deskripsi Paket</th>
                        <th>Harga</th>
                        <th>Option</th>
                    </tr>
                </thead>
                <tbody id="items-list">

                </tbody>
            </table>

            <div class="summary">
                <span>Biaya Transport & Akomodasi: <input type="text" id="biaya" readonly></span>
                <span>Subtotal: <input type="text" id="subtotal" name="subtotal" readonly></span>
                <span>Down Payment: <input type="text" id="dp-percentage" name="down_payment"
                        oninput="updateLastPayment()"></span>
                <span>Last Payment: <input type="text" id="last-payment" name="last_payment" readonly></span>
                <span>Payment Method:
                    <select name="no_payment" id="payment-method" required>
                        <option value="" selected>Pilih Metode Pembayaran</option>
                        <?php
                        $result = $conn->query("SELECT no_payment, metode_pembayaran FROM table_payment");
                        while ($row = $result->fetch_assoc()) {
                            echo "<option value='{$row['no_payment']}'>{$row['metode_pembayaran']}</option>";
                        }
                        ?>
                    </select>
                </span>
            </div>

            <div class="final-button">
                <button type="submit" class="save">Save All</button>
                <button type="button" onclick="resetAll()" class="delete-all">Reset All</button>
            </div>
        </form>
    </div>

    <script>
        window.onload = function () {
            const today = new Date().toISOString().split('T')[0];
            document.getElementById('tgl-cetak').value = today;

            const twoWeeksLater = new Date();
            twoWeeksLater.setDate(twoWeeksLater.getDate() + 14);
            const minDate = twoWeeksLater.toISOString().split('T')[0];
            document.getElementById('pelaksanaan').setAttribute('min', minDate);
        };

        function setIdKlien() {
            const namaKlien = document.getElementById('nama-klien').value;
            const options = document.getElementById('list-klien').options;
            for (let option of options) {
                if (option.value === namaKlien) {
                    document.getElementById('id_klien_hidden').value = option.getAttribute('data-id');
                    break;
                }
            }
        }

        function isiDataVenue() {
            const venueSelect = document.getElementById('id-venue');
            const selectedOption = venueSelect.options[venueSelect.selectedIndex];
            const biaya = selectedOption.getAttribute('data-biaya');
            document.getElementById('biaya').value = biaya || 0;

            hitungSubtotal();
        }

        function setIdPaket() {
            const namaPaket = document.getElementById('nama-paket').value;
            const options = document.getElementById('list-paket').options;
            for (let option of options) {
                if (option.value === namaPaket) {
                    document.getElementById('id-paket-hidden').value = option.getAttribute('data-id');
                    break;
                }
            }
        }

        function tambahPaket() {
            const namaPaket = document.getElementById('nama-paket').value;
            if (!namaPaket) {
                alert('Silakan pilih paket terlebih dahulu.');
                return;
            }

            fetch('ambil_paket.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ nama_paket: namaPaket })
            })
                .then(response => response.json())
                .then(data => {
                    const itemsList = document.getElementById('items-list');
                    const row = document.createElement('tr');
                    const formattedDescription = data.deskripsi_paket
                        .split(/\r?\n/)
                        .map(line => `<div>${line}</div>`)
                        .join('');
                    row.innerHTML = `
            <td>${data.id_paket}</td>
            <td>${data.nama_paket}</td>
            <td>${formattedDescription}</td>
            <td>${data.harga}</td>
            <td><button onclick="hapusPaket(this)">Hapus</button></td>
        `;
                    itemsList.appendChild(row);

                    document.getElementById('nama-paket').readOnly = true;
                    document.getElementById('nama-paket').value = '';

                    document.getElementById('subtotal').dataset.hargaPaket = data.harga;
                    hitungSubtotal();
                });
        }

        function hapusPaket(button) {
            button.parentElement.parentElement.remove();
            document.getElementById('nama-paket').readOnly = false;
            document.getElementById('subtotal').dataset.hargaPaket = 0;
            hitungSubtotal();
        }

        function updateLastPayment() {
            const subtotal = parseFloat(document.getElementById('subtotal').value || 0);
            const dp = parseFloat(document.getElementById('dp-percentage').value || 0);
            const sisaPembayaran = subtotal - dp;

            if (dp > subtotal) {
                alert("DP tidak boleh lebih besar dari subtotal.");
                document.getElementById('dp-percentage').value = 0;
                document.getElementById('last-payment').value = subtotal;
                return;
            }

            document.getElementById('last-payment').value = sisaPembayaran.toFixed(2);
        }

        function hitungSubtotal() {
            const biayaVenue = parseFloat(document.getElementById('biaya').value || 0);
            const hargaPaket = parseFloat(document.getElementById('subtotal').dataset.hargaPaket || 0);

            const subtotal = biayaVenue + hargaPaket;
            document.getElementById('subtotal').value = subtotal.toFixed(2);

            const dp = parseFloat(document.getElementById('dp-percentage').value || 0);
            const sisaPembayaran = subtotal - dp;

            document.getElementById('last-payment').value = sisaPembayaran.toFixed(2);
        }

        function resetAll() {
            const tglCetak = document.getElementById('tgl-cetak').value;

            document.querySelector('form').reset();
            document.getElementById('tgl-cetak').value = tglCetak;
            document.getElementById('items-list').innerHTML = '';
            document.getElementById('nama-paket').readOnly = false;
            document.getElementById('subtotal').value = '';
            document.getElementById('last-payment').value = '';
            document.getElementById('subtotal').dataset.hargaPaket = 0;
        }
    </script>

</body>

</html>