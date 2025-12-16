<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

$username = $_SESSION['username'];


$show_welcome_modal = false;
if (isset($_SESSION['login_success'])) {
    $show_welcome_modal = true;
 
    unset($_SESSION['login_success']); 
}

// Commit 5: Data Produk 
$produk = [
    ['kode' => 'K001', 'nama' => 'Teh Pucuk', 'harga' => 5000],
    ['kode' => 'K002', 'nama' => 'Sukro', 'harga' => 1000],
    ['kode' => 'K003', 'nama' => 'Sprite', 'harga' => 4000],
    ['kode' => 'K004', 'nama' => 'Coca-Cola', 'harga' => 5000],
    ['kode' => 'K005', 'nama' => 'Chitose', 'harga' => 3000]
];

// Commit 6: Logika Penjualan Random
$daftar_pembelian = [];
$grand_total = 0;

$produk_untuk_random = $produk;
$jumlah_item_dibeli = rand(3, 5); 

for ($i = 0; $i < $jumlah_item_dibeli; $i++) {

    if (empty($produk_untuk_random)) {
        break;
    }
    

    $index_produk = array_rand($produk_untuk_random);
    $barang_terpilih = $produk_untuk_random[$index_produk];
    

    $jumlah = rand(1, 5);
    

    $total_per_item = $barang_terpilih['harga'] * $jumlah;
    
    
    $grand_total += $total_per_item;
    

    $daftar_pembelian[] = [
        'kode' => $barang_terpilih['kode'],
        'nama' => $barang_terpilih['nama'],
        'harga' => $barang_terpilih['harga'],
        'jumlah' => $jumlah,
        'total' => $total_per_item
    ];


    unset($produk_untuk_random[$index_produk]); 
}


function format_rupiah($angka) {
    return 'Rp ' . number_format($angka, 0, ',', '.');
}


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
  
    <?php if ($show_welcome_modal): ?>
    <div id="welcomeModal" class="modal-overlay">
        <div class="modal-card">
            <h2 class="modal-title">Selamat Datang, <?php echo htmlspecialchars($username); ?>!</h2>
            <p class="modal-role">Role: Admin</p>
            <p class="modal-message">Anda berhasil login ke sistem.</p>
   
            <button id="closeModalBtn" class="btn btn-close-modal" style="display: none;">Lanjutkan</button>
        </div>
    </div>
    <script>
      
        const modal = document.getElementById('welcomeModal');
        const logoutBtn = document.querySelector('.btn-logout-modal');
        const dashboardContent = document.querySelector('.dashboard-container');

    
        if (dashboardContent) {
            dashboardContent.style.display = 'none';
        }

      
        setTimeout(() => {
            if (modal) {
                modal.classList.add('hide'); 
            }
         
            setTimeout(() => {
                if (modal) {
                    modal.style.display = 'none';
                }
                if (dashboardContent) {
                    dashboardContent.style.display = 'block';
                }
            }, 500); 
        }, 2500); 
    </script>
    <?php endif; ?>


    <div class="dashboard-container">
        <header class="dashboard-header">
            <div class="logo">
                POLGAN MART
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
                        <!-- Commit 7: Tampilkan detail pembelian -->
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
                        <!-- Commit 8: Cetak Total Belanja -->
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
                        Kasir   : <?php echo htmlspecialchars($kasir); ?>

                        -----------------------------------------
            
                        <?php 
                       
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
   
        </main>
    </div>

</body>
</html>