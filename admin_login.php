<?php
session_start();

$admin_username = 'admin';
$admin_password = 'admin123';


// Pesan kesalahan login
$login_error = '';

// Proses login saat form dikirim
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username_input = $_POST['username'];
    $password_input = $_POST['password'];

    // Verifikasi username dan password
    if ($username_input === $admin_username && $password_input === $admin_password) {
        // Jika login sukses, set session untuk admin
        $_SESSION['admin_username'] = $username_input;
        header("Location: admin_dashboard.php");
        exit();
    } else {
        $login_error = "INVALID Username or Password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>
<body class="bg-gray-900 flex justify-center items-center min-h-screen p-4">
    <form class="bg-gray-800 p-6 rounded-lg shadow-lg space-y-4 w-full max-w-sm xl:max-w-md" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <h2 class="text-red-700 text-2xl font-bold text-center">LOGIN ADMIN</h2>
        <div class="space-y-2">
            <label class="block text-white">Username:</label>
            <input type="text" name="username" placeholder="Username" class="w-full p-2 rounded bg-gray-700 text-white" required>
        </div>
        <div class="space-y-2">
            <label class="block text-white">Password:</label>
            <input type="password" name="password" placeholder="Password" class="w-full p-2 rounded bg-gray-700 text-white" required>
        </div>
        <button class="w-full bg-red-700 text-white py-3 rounded-lg font-semibold hover:bg-red-900 hover:scale-105 hover:shadow-lg transition duration-300" type="submit">Login</button>
        <?php if ($login_error): ?>
        <p class="text-red-500 text-center mt-4"><?php echo $login_error; ?></p>
        <?php endif; ?>
    </form>
</body>
</html>
