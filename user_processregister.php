<?php
session_start();
include("db_connect.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $phone = $_POST['phone'];

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $conn = connectDB();

    if ($conn->connect_error) {
        $_SESSION['error_message'] = "Connection failed: " . $conn->connect_error;
        header("Location: user_register.php");
        exit;
    }

    $stmt = $conn->prepare("INSERT INTO users (username, password, phone) VALUES (?, ?, ?)");
    if (!$stmt) {
        $_SESSION['error_message'] = "Prepare failed: (" . $conn->errno . ") " . $conn->error;
        header("Location: user_register.php");
        exit;
    }

    $stmt->bind_param("sss", $username, $hashed_password, $phone);

    if ($stmt->execute()) {
        $_SESSION['success_message'] = "Registration successful!";
        header("Location: user_login.php");
        exit;
    } else {
        $_SESSION['error_message'] = "Registration failed: (" . $stmt->errno . ") " . $stmt->error;
        header("Location: user_register.php");
        exit;
    }

   
}
?>
