<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Login</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.7/dist/tailwind.min.css">
</head>
<body class="bg-gray-100 flex justify-center items-center h-screen">
    <div class="max-w-md w-full p-6 bg-white rounded-lg shadow-lg">
        <h2 class="text-2xl font-bold text-center mb-4">User Login</h2>

        <?php
        session_start();

        if (isset($_SESSION['error_message'])) {
            echo '<p class="text-red-500 text-sm mb-4">' . $_SESSION['error_message'] . '</p>';
            unset($_SESSION['error_message']);
        }
        ?>

<form action="user_processlogin.php" method="POST">
            <div class="mb-4">
                <label for="username" class="block text-sm font-medium text-gray-700">Username</label>
                <input type="text" id="username" name="username" required class="mt-1 p-2 border border-gray-300 rounded-lg w-full">
            </div>
            <div class="mb-4">
                <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                <input type="password" id="password" name="password" required class="mt-1 p-2 border border-gray-300 rounded-lg w-full">
            </div>
            <button type="submit" class="w-full py-2 bg-blue-500 text-white font-bold rounded-lg hover:bg-blue-600 transition duration-300">Login</button>
        </form>
        <?php if (isset($_SESSION['error_message'])): ?>
            <p class="text-red-500 mt-4 text-center"><?php echo $_SESSION['error_message']; unset($_SESSION['error_message']); ?></p>
        <?php endif; ?>
    </div>
</body>
</html>

