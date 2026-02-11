<?php
include "navbar.php";
include "db.php";

// Tambah ke keranjang
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['produk_id'])) {
    $produk_id = $_POST['produk_id'];

    if (!isset($_SESSION['keranjang'])) {
        $_SESSION['keranjang'] = [];
    }

    if (isset($_SESSION['keranjang'][$produk_id])) {
        $_SESSION['keranjang'][$produk_id] += 1;
    } else {
        $_SESSION['keranjang'][$produk_id] = 1;
    }
}

// Tambah produk (admin)
if (isset($_POST['tambah_produk']) && isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
    $nama = $_POST['nama'];
    $harga = $_POST['harga'];
    $gambar = '';

    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] == 0) {
        $nama_file = basename($_FILES["gambar"]["name"]);
        $target = "gambar/" . $nama_file;
        move_uploaded_file($_FILES["gambar"]["tmp_name"], $target);
        $gambar = $nama_file;
    }

    $conn->query("INSERT INTO produk (nama, harga, gambar) VALUES ('$nama', '$harga', '$gambar')");
}

// Edit produk (admin)
if (isset($_POST['edit_produk']) && isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
    $id = $_POST['id'];
    $nama = $_POST['nama'];
    $harga = $_POST['harga'];

    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] == 0) {
        $nama_file = basename($_FILES["gambar"]["name"]);
        $target = "gambar/" . $nama_file;
        move_uploaded_file($_FILES["gambar"]["tmp_name"], $target);
        $gambar = $nama_file;
        $conn->query("UPDATE produk SET nama='$nama', harga='$harga', gambar='$gambar' WHERE id='$id'");
    } else {
        $conn->query("UPDATE produk SET nama='$nama', harga='$harga' WHERE id='$id'");
    }
}

// Hapus produk (admin)
if (isset($_POST['hapus_produk']) && isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
    $id = $_POST['id'];

    // Ambil gambar
    $stmt = $conn->prepare("SELECT gambar FROM produk WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->bind_result($gambar);
    $stmt->fetch();
    $stmt->close();

    if ($gambar && file_exists("gambar/$gambar")) {
        unlink("gambar/$gambar");
    }

    // Hapus dari database
    $stmt = $conn->prepare("DELETE FROM produk WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
}

// Ambil data produk
$cari = $_GET['cari'] ?? '';
if ($cari !== '') {
    $stmt = $conn->prepare("SELECT * FROM produk WHERE nama LIKE ?");
    $like = "%$cari%";
    $stmt->bind_param("s", $like);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $result = $conn->query("SELECT * FROM produk");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Toko Online</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100 text-gray-800">

<div class="max-w-6xl mx-auto px-4 py-6">
    <h2 class="text-3xl font-bold mb-6">🛍️ Daftar Produk</h2>

    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <!-- Keranjang -->
        <?php
        $jumlah_keranjang = 0;
        if (isset($_SESSION['keranjang'])) {
            $jumlah_keranjang = array_sum($_SESSION['keranjang']);
        }
        ?>
        <a href="keranjang.php" class="inline-block bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">
            🛒 Lihat Keranjang (<?= $jumlah_keranjang ?>)
        </a>

        <!-- Cari -->
        <form method="GET" class="flex gap-2">
            <input type="text" name="cari" placeholder="Cari produk..." value="<?= htmlspecialchars($cari) ?>"
                class="border border-gray-300 rounded px-4 py-2 w-64">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">
                🔍 Cari
            </button>
        </form>
    </div>

    <!-- Tambah Produk (Admin) -->
    <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
        <div class="bg-white p-6 rounded shadow mb-10">
            <h3 class="text-xl font-semibold mb-4">➕ Tambah Produk</h3>
            <form method="POST" enctype="multipart/form-data" class="space-y-4">
                <input type="text" name="nama" placeholder="Nama Produk" required
                    class="w-full border border-gray-300 rounded px-3 py-2">
                <input type="number" name="harga" placeholder="Harga" required
                    class="w-full border border-gray-300 rounded px-3 py-2">
                <input type="file" name="gambar" accept="image/*" class="w-full">
                <button type="submit" name="tambah_produk"
                    class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition">
                    Tambah Produk
                </button>
            </form>
        </div>
    <?php endif; ?>

    <!-- Produk -->
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
        <?php while($row = $result->fetch_assoc()): ?>
        <div class="bg-white rounded shadow p-4 flex flex-col justify-between">
            <?php if ($row['gambar']): ?>
                <img src="gambar/<?= $row['gambar'] ?>" alt="<?= $row['nama'] ?>" class="w-full h-40 object-cover rounded mb-3">
            <?php endif; ?>

            <div class="flex-1">
                <h4 class="text-lg font-bold mb-1"><?= $row['nama'] ?></h4>
                <p class="text-gray-600 mb-4">Harga: <span class="font-semibold">Rp <?= number_format($row['harga']) ?></span></p>
            </div>

            <!-- Tambah ke Keranjang -->
            <form method="POST">
                <input type="hidden" name="produk_id" value="<?= $row['id'] ?>">
                <button type="submit" class="bg-blue-600 text-white px-3 py-2 rounded hover:bg-blue-700 transition w-full">
                    + Tambah ke Keranjang
                </button>
            </form>

            <!-- Admin: Edit & Hapus -->
            <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                <form method="POST" enctype="multipart/form-data" class="mt-4 space-y-2">
                    <input type="hidden" name="id" value="<?= $row['id'] ?>">
                    <input type="text" name="nama" value="<?= $row['nama'] ?>" required
                        class="w-full border border-gray-300 rounded px-2 py-1">
                    <input type="number" name="harga" value="<?= $row['harga'] ?>" required
                        class="w-full border border-gray-300 rounded px-2 py-1">
                    <input type="file" name="gambar" accept="image/*">
                    <button type="submit" name="edit_produk"
                        class="bg-yellow-500 text-white px-3 py-2 rounded hover:bg-yellow-600 transition w-full">
                        Simpan Perubahan
                    </button>
                </form>

                <!-- Tombol Hapus -->
                <form method="POST" onsubmit="return confirm('Yakin ingin menghapus produk ini?');">
                    <input type="hidden" name="id" value="<?= $row['id'] ?>">
                    <button type="submit" name="hapus_produk"
                        class="bg-red-600 text-white px-3 py-2 rounded hover:bg-red-700 transition w-full mt-2">
                        🗑️ Hapus Produk
                    </button>
                </form>
            <?php endif; ?>
        </div>
        <?php endwhile; ?>
    </div>

</div>
</body>
</html>
