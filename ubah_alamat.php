<?php
session_start();
include "db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Update data jika form disubmit
if (isset($_POST['simpan_perubahan'])) {
    $nama = $_POST['nama_penerima'];
    $alamat = $_POST['alamat'];
    $no_hp = $_POST['no_hp'];

    $stmt = $conn->prepare("UPDATE pengiriman SET nama_penerima = ?, alamat = ?, no_hp = ? WHERE user_id = ?");
    $stmt->bind_param("sssi", $nama, $alamat, $no_hp, $user_id);
    $stmt->execute();

    header("Location: checkout.php");
    exit;
}

// Ambil data alamat saat ini
$stmt = $conn->prepare("SELECT * FROM pengiriman WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$alamat_user = $result->fetch_assoc();
?>

<!-- Tailwind CDN -->
<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

<div class="max-w-xl mx-auto mt-10 p-6 bg-white shadow-md rounded-lg">
    <h2 class="text-xl font-semibold mb-6 text-gray-800">Ubah Alamat Pengiriman</h2>

    <form method="POST" class="space-y-4">
        <div>
            <label class="block mb-1 font-medium">Nama Penerima:</label>
            <input type="text" name="nama_penerima" value="<?= htmlspecialchars($alamat_user['nama_penerima']) ?>" required class="w-full border border-gray-300 rounded px-3 py-2">
        </div>

        <div>
            <label class="block mb-1 font-medium">Alamat:</label>
            <textarea name="alamat" required class="w-full border border-gray-300 rounded px-3 py-2"><?= htmlspecialchars($alamat_user['alamat']) ?></textarea>
        </div>

        <div>
            <label class="block mb-1 font-medium">No HP:</label>
            <input type="text" name="no_hp" value="<?= htmlspecialchars($alamat_user['no_hp']) ?>" required class="w-full border border-gray-300 rounded px-3 py-2">
        </div>

        <div class="flex items-center gap-4">
            <button type="submit" name="simpan_perubahan" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Simpan Perubahan</button>
            <a href="checkout.php" class="text-blue-600 underline">Kembali ke Checkout</a>
        </div>
    </form>
</div>
