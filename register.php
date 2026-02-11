<?php
session_start();
include 'db.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $password_confirm = $_POST['password_confirm'];

    if ($password !== $password_confirm) {
        $error = "Password dan konfirmasi password tidak sama!";
    } else {
        $cek = mysqli_query($conn, "SELECT * FROM users WHERE username='$username' OR email='$email'");
        if (mysqli_num_rows($cek) > 0) {
            $error = "Username atau email sudah terdaftar!";
        } else {
            $password_hash = password_hash($password, PASSWORD_DEFAULT);
            $query = "INSERT INTO users (username, email, password, role) VALUES ('$username', '$email', '$password_hash', 'user')";
            $insert = mysqli_query($conn, $query);

            if ($insert) {
                $success = "Registrasi berhasil! Silakan <a href='login.php' class='text-indigo-600 underline'>login</a>.";
            } else {
                $error = "Error: " . mysqli_error($conn);
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8" />
    <title>Register</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen px-4">
    <form method="post" action="" class="bg-white p-8 rounded-lg shadow-md max-w-md w-full">
        <h2 class="text-2xl font-semibold mb-6 text-center text-gray-800">Register</h2>

        <?php if ($error): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                <?= $success ?>
            </div>
        <?php endif; ?>

        <label for="username" class="block text-gray-700 font-medium mb-1">Username</label>
        <input type="text" name="username" id="username" required
               value="<?= htmlspecialchars($_POST['username'] ?? '') ?>"
               class="w-full mb-4 px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-indigo-500" />

        <label for="email" class="block text-gray-700 font-medium mb-1">Email</label>
        <input type="email" name="email" id="email" required
               value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
               class="w-full mb-4 px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-indigo-500" />

        <label for="password" class="block text-gray-700 font-medium mb-1">Password</label>
        <input type="password" name="password" id="password" required
               class="w-full mb-4 px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-indigo-500" />

        <label for="password_confirm" class="block text-gray-700 font-medium mb-1">Konfirmasi Password</label>
        <input type="password" name="password_confirm" id="password_confirm" required
               class="w-full mb-6 px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-indigo-500" />

        <button type="submit"
                class="w-full bg-blue-600 hover:bg-indigo-700 text-white font-semibold py-2 rounded transition-colors duration-300">
            Register
        </button>

        <p class="text-center text-gray-600 mt-4">
            Sudah punya akun? <a href="login.php" class="text-blue-600 hover:underline">Login di sini</a>
        </p>
    </form>
</body>
</html>
