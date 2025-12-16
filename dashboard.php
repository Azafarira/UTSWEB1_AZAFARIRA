<?php
// Mulai session
session_start();

// Cek apakah user sudah login
// Jika belum, arahkan kembali ke halaman login (index.php)
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

// Ambil username dari session
$username = $_SESSION['username'];

// >>> TAMBAHAN: Cek dan ambil status login sukses
$show_welcome_modal = false;
if (isset($_SESSION['login_success'])) {
    $show_welcome_modal = true;
    // Hapus penanda session agar tidak muncul lagi saat refresh
    unset($_SESSION['login_success']); 
}

// --- Commit 5: Data Produk (Array Multidimensi yang Jelas) ---
// Membuat 5 produk menggunakan array multidimensi, di mana setiap elemen adalah array asosiatif
$produk = [
    ['kode' => 'K001', 'nama' => 'Teh Pucuk', 'harga' => 5000],
    ['kode' => 'K002', 'nama' => 'Sukro', 'harga' => 1000],
    ['kode' => 'K003', 'nama' => 'Sprite', 'harga' => 4000],
    ['kode' => 'K004', 'nama' => 'Coca-Cola', 'harga' => 5000],
    ['kode' => 'K005', 'nama' => 'Chitose', 'harga' => 3000]
];

// --- Commit 6: Logika Penjualan Random ---
$daftar_pembelian = [];
$grand_total = 0;
// Gunakan copy dari $produk untuk logika acak agar array aslinya tetap utuh jika diperlukan
$produk_untuk_random = $produk;
$jumlah_item_dibeli = rand(3, 5); // Tentukan berapa jenis barang yang dibeli (misal 3-5)

// Gunakan perulangan for untuk memilih barang dan jumlah secara acak
for ($i = 0; $i < $jumlah_item_dibeli; $i++) {
    // Pastikan masih ada produk untuk dipilih
    if (empty($produk_untuk_random)) {
        break;
    }
    
    // Pilih barang secara acak dari array $produk_untuk_random
    $index_produk = array_rand($produk_untuk_random);
    $barang_terpilih = $produk_untuk_random[$index_produk];
    
    // Tentukan jumlah pembelian secara acak (misal 1-5)
    $jumlah = rand(1, 5);
    
    // Hitung total harga per item
    $total_per_item = $barang_terpilih['harga'] * $jumlah;
    
    // Tambahkan ke grand total
    $grand_total += $total_per_item;
    
    // Masukkan ke array daftar pembelian (juga array multidimensi)
    $daftar_pembelian[] = [
        'kode' => $barang_terpilih['kode'],
        'nama' => $barang_terpilih['nama'],
        'harga' => $barang_terpilih['harga'],
        'jumlah' => $jumlah,
        'total' => $total_per_item
    ];

    // Hapus produk yg sudah dipilih agar tidak duplikat
    unset($produk_untuk_random[$index_produk]); 
}

// Fungsi untuk format Rupiah
function format_rupiah($angka) {
    return 'Rp ' . number_format($angka, 0, ',', '.');
}

// Data untuk struk
$tanggal_transaksi = date("d.m.Y H:i:s");
$kasir = $username;

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - POLGAN MART</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <!-- Modal Selamat Datang -->
    <?php if ($show_welcome_modal): ?>
    <div id="welcomeModal" class="modal-overlay">
        <div class="modal-card">
            <h2 class="modal-title">Selamat Datang, <?php echo htmlspecialchars($username); ?>!</h2>
            <p class="modal-role">Role: Admin</p>
            <p class="modal-message">Anda berhasil login ke sistem.</p>
            <a href="logout.php" class="btn btn-logout-modal">Logout</a>
            <!-- Tombol untuk menutup modal, akan disembunyikan oleh skrip -->
            <button id="closeModalBtn" class="btn btn-close-modal" style="display: none;">Lanjutkan</button>
        </div>
    </div>
    <script>
        // Tampilkan modal secara otomatis.
        const modal = document.getElementById('welcomeModal');
        const logoutBtn = document.querySelector('.btn-logout-modal');
        const dashboardContent = document.querySelector('.dashboard-container');

        // Sembunyikan konten dashboard sampai modal ditutup
        if (dashboardContent) {
            dashboardContent.style.display = 'none';
        }

        // Timer untuk menyembunyikan modal setelah 2.5 detik
        setTimeout(() => {
            if (modal) {
                modal.classList.add('hide'); // Tambahkan kelas untuk efek transisi fade out
            }
            // Setelah transisi selesai, sembunyikan sepenuhnya dan tampilkan dashboard
            setTimeout(() => {
                if (modal) {
                    modal.style.display = 'none';
                }
                if (dashboardContent) {
                    dashboardContent.style.display = 'block';
                }
            }, 500); // Sesuaikan dengan durasi transisi di CSS (0.5s)
        }, 2500); // Modal tampil selama 2.5 detik (2500ms)
    </script>
    <?php endif; ?>
    <!-- Akhir Modal -->

    <div class="dashboard-container">
        <header class="dashboard-header">
            <div class="logo">
                <span>PM</span>
                --POLGAN MART--
            </div>
            <div class="user-info">
                <span>Selamat datang, <strong><?php echo htmlspecialchars($username); ?></strong>!</span>
                <a href="logout.php" class="btn btn-logout">Logout</a>
            </div>
        </header>
        
        <main class="dashboard-content">
            <h2>Daftar Pembelian</h2>
            
            <div class="sales-table-container">
                <table class="sales-table">
                    <thead>
                        <tr>
                            <th>Kode</th>
                            <th>Nama Barang</th>
                            <th>Harga</th>
                            <th>Jumlah</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Tampilkan detail pembelian dari array multidimensi -->
                        <?php foreach ($daftar_pembelian as $item): ?>
                            <tr>
                                <td><?php echo $item['kode']; ?></td>
                                <td><?php echo $item['nama']; ?></td>
                                <td><?php echo format_rupiah($item['harga']); ?></td>
                                <td><?php echo $item['jumlah']; ?></td>
                                <td><?php echo format_rupiah($item['total']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <!-- Cetak Total Belanja -->
                        <tr>
                            <td colspan="4" class="total-label">Total Belanja</td>
                            <td class="total-value"><?php echo format_rupiah($grand_total); ?></td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <!-- Struk Pembelian -->
            <div class="purchase-receipt-container">
                <div class="receipt-box">
                    <pre class="receipt-content">
                    
            ====== STRUK PEMBELIAN ======

Tanggal : <?php echo $tanggal_transaksi; ?>
<br>
Kasir   : <?php echo htmlspecialchars($kasir); ?>
<br>

-----------------------------------------      
<?php 
                        // Loop untuk mencetak detail item di struk
                        foreach ($daftar_pembelian as $item) {
                            $line = sprintf(
                                "%s (%d x %s) = %s\n",
                                $item['nama'],
                                $item['jumlah'],
                                format_rupiah($item['harga']),
                                format_rupiah($item['total'])
                            );
                            echo $line;
                        }
                        ?>
-----------------------------------------
Total Belanja : <?php echo format_rupiah($grand_total); ?>


    Terima Kasih Telah Berbelanja di POLGAN MART!

                    </pre>
                </div>
            </div>
            <!-- Akhir Struk Pembelian -->
        </main>
    </div>

</body>
</html>