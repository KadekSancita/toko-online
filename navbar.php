<?php
session_start();
$keranjang_total = isset($_SESSION['keranjang']) ? array_sum($_SESSION['keranjang']) : 0;
?>

<!-- Tambahkan link Tailwind CSS di head HTML kamu -->
<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

<nav class="bg-gray-800 text-white">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex items-center justify-between h-16">
      <div class="flex items-center">
        <span class="text-xl font-bold">Nexus</span>
        <div class="hidden md:block ml-10 space-x-4">
          <a href="index.php" class="hover:text-yellow-300">Home</a>
          <a href="produk.php" class="hover:text-yellow-300">Produk</a>
          <a href="keranjang.php" class="hover:text-yellow-300">Keranjang</a>
          <a href="riwayat.php" class="hover:text-yellow-300">Riwayat</a>
        </div>
      </div>

      <div class="hidden md:block">
        <div class="ml-4 flex items-center space-x-4">
          <?php if (isset($_SESSION['user'])): ?>
            <a href="logout.php" onclick="return confirmLogout();" class="hover:text-red-400">Logout</a>
          <?php else: ?>
            <a href="login.php" class="hover:text-green-400">Login</a>
          <?php endif; ?>
        </div>
      </div>

      <!-- Menu mobile button -->
      <div class="-mr-2 flex md:hidden">
        <button id="mobile-menu-button" class="text-gray-300 hover:text-white focus:outline-none">
          <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M4 6h16M4 12h16M4 18h16" />
          </svg>
        </button>
      </div>
    </div>
  </div>

  <!-- Mobile Menu -->
  <div id="mobile-menu" class="md:hidden hidden px-2 pt-2 pb-3 space-y-1">
    <a href="index.php" class="block hover:text-yellow-300">Home</a>
    <a href="produk.php" class="block hover:text-yellow-300">Produk</a>
    <a href="keranjang.php" class="block hover:text-yellow-300">Keranjang</a>
    <a href="riwayat.php" class="block hover:text-yellow-300">Riwayat</a>
    <?php if (isset($_SESSION['user'])): ?>
      <a href="logout.php" onclick="return confirmLogout();" class="block hover:text-red-400">Logout</a>
    <?php else: ?>
      <a href="login.php" class="block hover:text-green-400">Login</a>
    <?php endif; ?>
  </div>
</nav>

<script>
  // Toggle mobile menu
  document.getElementById('mobile-menu-button').addEventListener('click', function () {
    const menu = document.getElementById('mobile-menu');
    menu.classList.toggle('hidden');
  });

  // Konfirmasi logout
  function confirmLogout() {
    return confirm("Apakah Anda yakin ingin logout?");
  }
</script>
