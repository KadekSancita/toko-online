<?php
session_start();
include "db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Ambil role user
$stmt = $conn->prepare("SELECT role FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user_data = $result->fetch_assoc();
$role = $user_data['role'] ?? 'user';
$stmt->close();

// Cegah admin akses halaman ini
if ($role === 'admin') {
    echo "<div class='max-w-xl mx-auto mt-10 p-6 bg-white shadow-md rounded-lg text-red-600 font-semibold text-center'>
        Admin tidak diperbolehkan melakukan checkout.
    </div>";
    exit;
}

// Data nomor rekening penjual per metode pembayaran
$nomor_rekening_penjual = [
    'Bank Transfer' => '1234567890 (Bank BRI - a/n Toko Nexus)',
    'E-Wallet' => '081234567890 (OVO - a/n Toko Nexus)',
    // COD tidak pakai nomor rekening
];

// Ambil alamat pengiriman user
$alamat_user = null;
$stmt = $conn->prepare("SELECT * FROM pengiriman WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
    $alamat_user = $result->fetch_assoc();
}
$stmt->close();

// Simpan alamat baru jika belum ada
if (!$alamat_user && isset($_POST['simpan_alamat'])) {
    $nama = $_POST['nama_penerima'];
    $alamat = $_POST['alamat'];
    $no_hp = $_POST['no_hp'];

    $stmt = $conn->prepare("INSERT INTO pengiriman (user_id, nama_penerima, alamat, no_hp) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isss", $user_id, $nama, $alamat, $no_hp);
    $stmt->execute();

    header("Location: checkout.php");
    exit;
}

$pesan_error = '';

if (
    $_SERVER['REQUEST_METHOD'] === 'POST' &&
    isset($_POST['konfirmasi_checkout']) &&
    isset($_SESSION['keranjang']) &&
    !empty($_SESSION['keranjang']) &&
    isset($_POST['metode_pembayaran'])
) {
    $metode = $_POST['metode_pembayaran'];
    $no_rekening = $_POST['no_rekening'] ?? '';

    if (($metode === 'Bank Transfer' || $metode === 'E-Wallet') && empty(trim($no_rekening))) {
        $pesan_error = "Nomor rekening/e-wallet penjual tidak tersedia. Silakan hubungi admin.";
    } else {
        $stmt = $conn->prepare("SELECT nama_penerima, alamat, no_hp FROM pengiriman WHERE user_id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $alamat_data = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        if ($alamat_data) {
            $nama_penerima = $alamat_data['nama_penerima'];
            $alamat = $alamat_data['alamat'];
            $no_hp = $alamat_data['no_hp'];

            foreach ($_SESSION['keranjang'] as $id => $qty) {
                $stmt = $conn->prepare("INSERT INTO transaksi 
                    (produk_id, jumlah, status_transaksi, status_pengiriman, user_id, metode_pembayaran, no_rekening, nama_penerima, alamat, no_hp) 
                    VALUES (?, ?, 'Diproses', 'Belum Dikirim', ?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("iiisssss", $id, $qty, $user_id, $metode, $no_rekening, $nama_penerima, $alamat, $no_hp);
                $stmt->execute();
            }

            unset($_SESSION['keranjang']);
            echo "<!DOCTYPE html>
<html lang='id'>
<head>
    <meta charset='UTF-8'>
    <title>Checkout Berhasil</title>
    <link href='https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css' rel='stylesheet'>
</head>
<body>
<div class='max-w-md mx-auto mt-10 p-6 bg-white shadow-md rounded-xl text-center'>
    <h2 class='text-2xl font-semibold mb-4 text-green-600'>Terima kasih! Pesanan Anda sedang diproses.</h2>
    <a href='riwayat.php' class='inline-block px-4 py-2 bg-green-600 text-white rounded mr-2'>Lihat Riwayat</a>
</div>
</body>
</html>";
            exit;
        } else {
            $pesan_error = "Alamat pengiriman tidak ditemukan.";
        }
    }
}
?>

<!-- Tailwind CDN -->
<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

<div class="max-w-xl mx-auto mt-10 p-6 bg-white shadow-md rounded-lg">
    <?php if (!$alamat_user): ?>
        <h2 class="text-xl font-semibold mb-4">Isi Alamat Pengiriman</h2>
        <form method="POST" class="space-y-4">
            <div>
                <label class="block mb-1 font-medium">Nama Penerima:</label>
                <input type="text" name="nama_penerima" required class="w-full border border-gray-300 rounded px-3 py-2">
            </div>

            <div>
                <label class="block mb-1 font-medium">Alamat:</label>
                <textarea name="alamat" required class="w-full border border-gray-300 rounded px-3 py-2"></textarea>
            </div>

            <div>
                <label class="block mb-1 font-medium">No HP:</label>
                <input type="text" name="no_hp" required class="w-full border border-gray-300 rounded px-3 py-2">
            </div>

            <button type="submit" name="simpan_alamat" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Simpan Alamat</button>
        </form>
    <?php else: ?>
        <h2 class="text-xl font-semibold mb-4">Konfirmasi Alamat Pengiriman</h2>
        <div class="mb-4">
            <p><strong>Nama:</strong> <?= htmlspecialchars($alamat_user['nama_penerima']) ?></p>
            <p><strong>Alamat:</strong> <?= nl2br(htmlspecialchars($alamat_user['alamat'])) ?></p>
            <p><strong>No HP:</strong> <?= htmlspecialchars($alamat_user['no_hp']) ?></p>
        </div>

        <?php if ($pesan_error): ?>
            <p class="text-red-600 mb-4"><?= htmlspecialchars($pesan_error) ?></p>
        <?php endif; ?>

        <form method="POST" onsubmit="return validateForm()" class="space-y-4">
            <div>
                <label class="block mb-1 font-medium">Metode Pembayaran:</label>
                <select name="metode_pembayaran" id="metode_pembayaran" onchange="updateNomorRekening()" required class="w-full border border-gray-300 rounded px-3 py-2">
                    <option value="">-- Pilih Metode --</option>
                    <option value="Bank Transfer">Bank Transfer</option>
                    <option value="COD">Cash on Delivery (COD)</option>
                    <option value="E-Wallet">E-Wallet</option>
                </select>
            </div>

            <div id="rekening_div" class="hidden">
                <label class="block mb-1 font-medium">Nomor Rekening / E-Wallet Penjual:</label>
                <input type="text" name="no_rekening" id="no_rekening" readonly class="w-full border border-gray-300 rounded px-3 py-2 bg-gray-100 cursor-not-allowed">
            </div>

            <div class="flex gap-4 items-center">
                <button type="submit" name="konfirmasi_checkout" value="1" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Lanjutkan Checkout</button>
                <a href="ubah_alamat.php" class="text-blue-600 underline">Ubah Alamat</a>
            </div>
        </form>
    <?php endif; ?>
</div>

<script>
const nomorRekeningPenjual = {
    "Bank Transfer": <?= json_encode($nomor_rekening_penjual['Bank Transfer']) ?>,
    "E-Wallet": <?= json_encode($nomor_rekening_penjual['E-Wallet']) ?>,
    "COD": ""
};

function updateNomorRekening() {
    const metode = document.getElementById("metode_pembayaran").value;
    const rekeningDiv = document.getElementById("rekening_div");
    const noRekeningInput = document.getElementById("no_rekening");

    if (metode === "Bank Transfer" || metode === "E-Wallet") {
        rekeningDiv.classList.remove("hidden");
        noRekeningInput.value = nomorRekeningPenjual[metode] || "";
    } else {
        rekeningDiv.classList.add("hidden");
        noRekeningInput.value = "";
    }
}

function validateForm() {
    const metode = document.getElementById("metode_pembayaran").value;
    if (!metode) {
        alert("Pilih metode pembayaran!");
        return false;
    }
    return true;
}

document.addEventListener("DOMContentLoaded", () => {
    updateNomorRekening();
});
</script>
