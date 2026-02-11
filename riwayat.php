<?php
include "navbar.php";
include "db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$is_admin = ($_SESSION['role'] ?? '') === 'admin';
?>

<div class="max-w-5xl mx-auto px-4 py-8">
    <h2 class="text-3xl font-bold mb-6">📄 Riwayat Transaksi</h2>

    <?php
    if ($is_admin) {
       $sql = "SELECT transaksi.*, produk.nama AS nama_produk
        FROM transaksi 
        JOIN produk ON transaksi.produk_id = produk.id
        ORDER BY transaksi.waktu DESC";

        $result = $conn->query($sql);
    } else {
       $stmt = $conn->prepare("SELECT transaksi.*, produk.nama AS nama_produk
                        FROM transaksi 
                        JOIN produk ON transaksi.produk_id = produk.id
                        WHERE transaksi.user_id = ?
                        ORDER BY transaksi.waktu DESC");

        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
    }

    $grouped = [];
    while ($row = $result->fetch_assoc()) {
        $waktu = $row['waktu'];
        if (!isset($grouped[$waktu])) {
            $grouped[$waktu] = [
                'produk' => [],
                'status_transaksi' => $row['status_transaksi'],
                'status_pengiriman' => $row['status_pengiriman'],
                'nama_penerima' => $row['nama_penerima'],
                'alamat' => $row['alamat'],
                'no_hp' => $row['no_hp'],
                'metode_pembayaran' => $row['metode_pembayaran'],
                'no_rekening' => $row['no_rekening'],
            ];
        }
        $grouped[$waktu]['produk'][] = [
            'nama' => $row['nama_produk'],
            'jumlah' => $row['jumlah'],
        ];
    }

    foreach ($grouped as $waktu => $data) {
        echo "<div class='bg-white rounded shadow p-6 mb-6'>";
        echo "<h4 class='text-xl font-semibold mb-2'>🕒 Waktu Pembelian: <span class='text-gray-700'>$waktu</span></h4>";
        
        echo "<ul class='list-disc ml-5 mb-4'>";
        foreach ($data['produk'] as $produk) {
            echo "<li><span class='font-medium'>{$produk['nama']}</span> (Jumlah: {$produk['jumlah']})</li>";
        }
        echo "</ul>";

        echo "<div class='mb-2'><strong>Status Transaksi:</strong> <span class='text-blue-600'>{$data['status_transaksi']}</span></div>";
        echo "<div class='mb-2'><strong>Status Pengiriman:</strong> <span class='text-green-600'>{$data['status_pengiriman']}</span></div>";

        if (!empty($data['nama_penerima'])) {
            echo "<div class='mt-4'>";
            echo "<p><strong>Nama Penerima:</strong> " . htmlspecialchars($data['nama_penerima']) . "</p>";
            echo "<p><strong>Alamat:</strong> " . nl2br(htmlspecialchars($data['alamat'])) . "</p>";
            echo "<p><strong>No HP:</strong> " . htmlspecialchars($data['no_hp']) . "</p>";
            echo "</div>";
        }

        echo "<div class='mt-4'>";
        echo "<p><strong>Metode Pembayaran:</strong> " . htmlspecialchars($data['metode_pembayaran']) . "</p>";
        if (!empty($data['no_rekening'])) {
            echo "<p><strong>No Rekening / E-Wallet:</strong> " . htmlspecialchars($data['no_rekening']) . "</p>";
        }
        echo "</div>";

        echo "</div>";
    }
    ?>
</div>
