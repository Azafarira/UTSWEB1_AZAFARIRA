<?php
// Halaman ini berfungsi sebagai kalkulator penjualan satu item yang mandiri (single input).

// =========================
// DATA BARANG
// =========================
$items = [
    'BRG001' => ['nama' => 'Sabun Mandi', 'harga' => 15000],
    'BRG002' => ['nama' => 'Sikat Gigi',  'harga' => 8000],
    'BRG003' => ['nama' => 'Pasta Gigi',  'harga' => 12000],
    'BRG004' => ['nama' => 'Shampoo',     'harga' => 20000],
    'BRG005' => ['nama' => 'Handuk',      'harga' => 35000],
];

// Variabel inisialisasi
$kode = $nama = '';
$harga = 0;
$jumlah = 1;
$lineTotal = $grandtotal = $diskon = $totalbayar = 0;
$d = "0%";

// =========================
// PROSES SAAT FORM SUBMIT
// =========================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $kode   = $_POST['kode']   ?? '';
    $jumlah = (int)($_POST['jumlah'] ?? 1);

    // jika kode ada di array, ambil nama & harga
    if ($kode !== '' && isset($items[$kode])) {
        $nama  = $items[$kode]['nama'];
        $harga = $items[$kode]['harga'];
    }

    // hitung total per baris
    $lineTotal  = $harga * $jumlah;
    $grandtotal = $lineTotal;

    // hitung diskon berdasarkan grandtotal
    // Kriteria Diskon: <50k = 5%, <=100k = 10%, >100k = 15%
    if ($grandtotal == 0) {
        $d = "0%";
        $diskon = 0;
    } elseif ($grandtotal < 50000) {
        $d = "5%";
        $diskon = 0.05 * $grandtotal;
    } elseif ($grandtotal <= 100000) {
        $d = "10%";
        $diskon = 0.10 * $grandtotal;
    } else {
        $d = "15%";
        $diskon = 0.15 * $grandtotal;
    }

    $totalbayar = $grandtotal - $diskon;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>POLGAN MART - Single Input</title>
    <!-- Tailwind CSS CDN for modern styling -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- CSS Eksternal untuk tema hijau -->
    <link rel="stylesheet" href="style.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap');
        body {
            font-family: 'Inter', sans-serif;
            /* Background color kini diatur di style.css */
        }
        .container {
            max-width: 600px;
        }
        .card-form {
            background-color: white;
            padding: 1.5rem;
            border-radius: 0.75rem; /* rounded-xl */
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -2px rgba(0, 0, 0, 0.1); /* shadow-lg */
        }
        .table-custom {
            border-radius: 0.75rem;
            overflow: hidden;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }
        .table-custom th, .table-custom td {
            padding: 0.75rem 1rem;
            border-color: #e5e7eb; /* gray-200 */
        }
        /* Menggunakan kelas Tailwind, namun custom CSS dipertahankan */
        .table-custom thead {
            background-color: #10b981; /* emerald-500 */
            color: white;
        }
        .table-custom tfoot tr:last-child {
            background-color: #d1fae5; /* emerald-100 */
            font-size: 1.125rem; /* text-lg */
        }
    </style>
</head>
<body class="p-4">

<div class="container mx-auto mt-8">
    <h1 class="text-3xl font-bold text-gray-800 mb-6 text-center">POLGAN MART - SINGLE TRANSACTION</h1>

    <div class="card-form mb-8">
        <h2 class="text-xl font-semibold text-gray-700 mb-4">Input Barang</h2>

        <form method="post" class="space-y-4">

            <!-- Kode Barang (SELECT) -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Kode Barang</label>
                <select name="kode" class="w-full p-2 border border-gray-300 rounded-lg focus:ring-emerald-500 focus:border-emerald-500 transition duration-150" required onchange="this.form.submit()">
                    <option value="">Pilih Kode Barang</option>
                    <?php foreach ($items as $k => $v): ?>
                        <option value="<?php echo $k; ?>"
                            <?php echo ($kode === $k) ? 'selected' : ''; ?>>
                            <?php echo $k . ' - ' . $v['nama']; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Nama Barang -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Barang</label>
                <input type="text" name="nama" class="w-full p-2 border border-gray-300 bg-gray-100 rounded-lg"
                        value="<?php echo htmlspecialchars($nama); ?>" readonly>
            </div>

            <!-- Harga -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Harga (Rp)</label>
                <input type="number" name="harga" class="w-full p-2 border border-gray-300 bg-gray-100 rounded-lg" min="0"
                        value="<?php echo $harga; ?>" readonly>
            </div>

            <!-- Jumlah -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah</label>
                <input type="number" name="jumlah" class="w-full p-2 border border-gray-300 rounded-lg focus:ring-emerald-500 focus:border-emerald-500 transition duration-150" min="1"
                        value="<?php echo $jumlah; ?>">
            </div>

            <!-- Tombol Tambahkan & Batal -->
            <div class="flex gap-3 pt-2">
                <button type="submit" class="w-full sm:w-auto px-4 py-2 bg-emerald-600 text-white font-semibold rounded-lg hover:bg-emerald-700 transition duration-200 shadow-md">
                    Hitung Total
                </button>
                <button type="reset" class="w-full sm:w-auto px-4 py-2 bg-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-400 transition duration-200">
                    Batal
                </button>
            </div>

        </form>
    </div>

    <?php if ($_SERVER['REQUEST_METHOD'] === 'POST' && $kode !== ''): ?>
        <h2 class="text-xl font-semibold text-gray-700 mb-4">Ringkasan Pembelian</h2>

        <div class="table-custom border border-gray-200 rounded-xl">
            <table class="w-full text-sm text-left text-gray-600">
                <thead class="text-xs uppercase">
                <tr>
                    <th scope="col" class="py-3 px-6 rounded-tl-xl">Kode</th>
                    <th scope="col" class="py-3 px-6">Nama Barang</th>
                    <th scope="col" class="py-3 px-6 text-right">Harga (Rp)</th>
                    <th scope="col" class="py-3 px-6 text-center">Jumlah</th>
                    <th scope="col" class="py-3 px-6 text-right rounded-tr-xl">Total Baris (Rp)</th>
                </tr>
                </thead>
                <tbody>
                <tr class="bg-white border-b hover:bg-gray-50">
                    <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap"><?php echo htmlspecialchars($kode); ?></td>
                    <td class="px-6 py-4"><?php echo htmlspecialchars($nama); ?></td>
                    <td class="px-6 py-4 text-right"><?php echo number_format($harga, 0, ',', '.'); ?></td>
                    <td class="px-6 py-4 text-center"><?php echo $jumlah; ?></td>
                    <td class="px-6 py-4 text-right font-semibold"><?php echo number_format($lineTotal, 0, ',', '.'); ?></td>
                </tr>
                </tbody>
                <tfoot class="bg-gray-50 border-t">
                <tr>
                    <td colspan="4" class="px-6 py-2 text-right font-medium text-gray-700"><strong>Subtotal</strong></td>
                    <td class="px-6 py-2 text-right font-bold text-gray-900"><?php echo number_format($grandtotal, 0, ',', '.'); ?></td>
                </tr>
                <tr>
                    <td colspan="4" class="px-6 py-2 text-right font-medium text-gray-700"><strong>Diskon (<?php echo $d; ?>)</strong></td>
                    <td class="px-6 py-2 text-right font-bold text-red-600"><?php echo number_format($diskon, 0, ',', '.'); ?></td>
                </tr>
                <tr>
                    <td colspan="4" class="px-6 py-3 text-right font-bold text-lg text-emerald-800"><strong>Total Bayar</strong></td>
                    <td class="px-6 py-3 text-right font-extrabold text-lg text-emerald-800"><?php echo number_format($totalbayar, 0, ',', '.'); ?></td>
                </tr>
                </tfoot>
            </table>
        </div>
    <?php elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && $kode === ''): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg relative" role="alert">
            <strong class="font-bold">Error!</strong>
            <span class="block sm:inline">Harap pilih Kode Barang untuk melakukan perhitungan.</span>
        </div>
    <?php endif; ?>
</div>

</body>
</html>