<?php
include "navbar.php";
include "db.php";
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Beranda - Toko Nexus</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-800 font-sans">

  <!-- Hero Section -->
  <section class="bg-gradient-to-r from-blue-600 to-indigo-700 text-white py-20 px-4 text-center">
    <div class="max-w-4xl mx-auto">
      <h1 class="text-5xl font-bold mb-4">Selamat Datang di Toko Nexus</h1>
      <p class="text-xl mb-6">Temukan HP, Laptop, dan Komponen PC Terbaik untuk Kebutuhan Anda.</p>
      <a href="produk.php" class="bg-white text-blue-700 font-semibold px-6 py-3 rounded shadow hover:bg-gray-200 transition">
        Lihat Produk Elektronik
      </a>
    </div>
  </section>

  <!-- Tentang Kami -->
  <section class="py-16 bg-white px-6">
    <div class="max-w-4xl mx-auto text-center">
      <h2 class="text-3xl font-bold mb-4">Tentang Kami</h2>
      <p class="text-gray-700 mb-4">
        Kami adalah penyedia perangkat elektronik terpercaya, mulai dari smartphone terbaru, laptop tangguh, hingga komponen PC lengkap.
        Kepuasan pelanggan dan pelayanan berkualitas adalah prioritas utama kami.
      </p>
      <p class="text-gray-700">
        Dengan harga bersaing dan pengiriman cepat ke seluruh Indonesia, kami hadir untuk memenuhi kebutuhan teknologi Anda.
      </p>
    </div>
  </section>

  <!-- Kategori Produk -->
  <section class="bg-gray-100 py-16 px-6">
    <div class="max-w-6xl mx-auto">
      <h2 class="text-3xl font-bold text-center mb-10">Kategori Produk</h2>
      <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-8">
        <div class="bg-white p-6 rounded shadow hover:shadow-lg transition">
          <img src="https://m-cdn.phonearena.com/images/articles/425103-image/S25-Ultra-design.jpg" alt="Smartphone" class="w-full h-48 object-cover rounded mb-4">
          <h3 class="text-xl font-semibold mb-2">Smartphone</h3>
          <p class="text-gray-600">HP Android dan iPhone terbaru dengan teknologi mutakhir.</p>
        </div>
        <div class="bg-white p-6 rounded shadow hover:shadow-lg transition">
          <img src="https://www.excaliberpc.com/images/resources/787810/28769cb453604e729369ed8864bd1824.png" alt="Laptop" class="w-full h-48 object-cover rounded mb-4">
          <h3 class="text-xl font-semibold mb-2">Laptop</h3>
          <p class="text-gray-600">Laptop untuk kebutuhan kerja, kuliah, dan gaming dengan spesifikasi tinggi.</p>
        </div>
        <div class="bg-white p-6 rounded shadow hover:shadow-lg transition">
          <img src="https://www.euston96.com/wp-content/uploads/2019/09/RAM.jpg" alt="Komponen PC" class="w-full h-48 object-cover rounded mb-4">
          <h3 class="text-xl font-semibold mb-2">Komponen PC</h3>
          <p class="text-gray-600">Motherboard, CPU, RAM, GPU, dan aksesoris lainnya untuk rakit PC impianmu.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- Testimoni Pelanggan -->
  <section class="bg-white py-16 px-6">
    <div class="max-w-4xl mx-auto text-center">
      <h2 class="text-3xl font-bold mb-10">Apa Kata Pelanggan Kami</h2>
      <div class="space-y-8">
        <div class="bg-blue-50 p-6 rounded shadow">
          <p class="italic text-gray-700">"Untuk websitenya bagus, barangnya murah!"</p>
          <p class="mt-4 font-semibold text-blue-700">– Sumber, Gianyar</p>
        </div>
        <div class="bg-blue-50 p-6 rounded shadow">
          <p class="italic text-gray-700">"Toko elektronik langganan. Komponen PC-nya lengkap dan murah."</p>
          <p class="mt-4 font-semibold text-blue-700">– Lisa, Yogyakarta</p>
        </div>
        <div class="bg-blue-50 p-6 rounded shadow">
          <p class="italic text-gray-700">"Bintang enam!!!."</p>
          <p class="mt-4 font-semibold text-blue-700">– Wahyu, Jawa</p>
        </div>
      </div>
    </div>
  </section>

  <!-- CTA (Call to Action) -->
  <section class="bg-blue-600 text-white py-20 text-center px-4">
    <div class="max-w-3xl mx-auto">
      <h2 class="text-4xl font-bold mb-4">Butuh Perangkat Elektronik Hari Ini?</h2>
      <p class="text-lg mb-6">Temukan semua yang Anda cari di toko kami. Stok selalu update!</p>
      <a href="produk.php" class="bg-white text-blue-700 px-6 py-3 rounded font-semibold hover:bg-gray-200 transition">
        Belanja Sekarang
      </a>
    </div>
  </section>

  <!-- Footer -->
  <footer class="bg-gray-900 text-white text-center py-6 mt-12">
    <p>&copy; <?= date('Y') ?> Toko Elektronik. Powered by Teknologi, untuk Masa Depan.</p>
  </footer>

</body>
</html>
