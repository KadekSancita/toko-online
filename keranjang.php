<?php
include "navbar.php";
include "db.php";
?>
<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
<body class="bg-gray-100 text-gray-800">
<div class="max-w-5xl mx-auto px-4 py-8">

    <h2 class="text-3xl font-bold mb-6">🛒 Keranjang Belanja</h2>
    <a href="produk.php" class="inline-block mb-6 text-blue-600 hover:underline">← Kembali ke Toko</a>

    <?php
    if (!empty($_SESSION['keranjang'])) {
        $total = 0;
        ?>

        <div class="overflow-x-auto bg-white rounded shadow">
            <table class="min-w-full text-sm text-left">
                <thead class="bg-gray-200 text-gray-700">
                    <tr>
                        <th class="px-6 py-3">Produk</th>
                        <th class="px-6 py-3">Jumlah</th>
                        <th class="px-6 py-3">Harga</th>
                        <th class="px-6 py-3">Subtotal</th>
                        <th class="px-6 py-3">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                foreach ($_SESSION['keranjang'] as $id => $qty) {
                    $data = $conn->query("SELECT * FROM produk WHERE id=$id")->fetch_assoc();
                    $sub = $qty * $data['harga'];
                    $total += $sub;
                    ?>
                    <tr class="border-t">
                        <td class="px-6 py-4"><?= htmlspecialchars($data['nama']) ?></td>
                        <td class="px-6 py-4"><?= $qty ?></td>
                        <td class="px-6 py-4">Rp <?= number_format($data['harga']) ?></td>
                        <td class="px-6 py-4">Rp <?= number_format($sub) ?></td>
                        <td class="px-6 py-4">
                            <a href="hapus.php?id=<?= $id ?>" onclick="return confirm('Yakin ingin hapus produk ini?')"
                               class="text-red-600 hover:underline">Hapus</a>
                        </td>
                    </tr>
                <?php } ?>
                    <tr class="bg-gray-100 font-semibold border-t">
                        <td colspan="3" class="px-6 py-4 text-right">Total:</td>
                        <td colspan="2" class="px-6 py-4 text-green-700">Rp <?= number_format($total) ?></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            <a href="checkout.php" class="bg-green-600 text-white px-6 py-2 rounded hover:bg-green-700 transition">
                Checkout
            </a>
        </div>

    <?php } else { ?>
        <div class="bg-white p-6 rounded shadow text-center">
            <p class="text-gray-600">Keranjang masih kosong.</p>
        </div>
    <?php } ?>

</div>
</body>