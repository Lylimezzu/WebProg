<?php
session_start();
include("db_connect.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $conn = connectDB();

    if ($conn->connect_error) {
        $_SESSION['error_message'] = "Connection failed: " . $conn->connect_error;
        header("Location: user_login.php");
        exit;
    }

    // Retrieve user from the database
    $stmt = $conn->prepare("SELECT user_id, password FROM users WHERE username = ?");
    if (!$stmt) {
        $_SESSION['error_message'] = "Prepare failed: (" . $conn->errno . ") " . $conn->error;
        header("Location: user_login.php");
        exit;
    }

    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($user_id, $hashed_password);
        $stmt->fetch();

        // Debugging logs
        error_log("Username: " . $username);
        error_log("Entered Password: " . $password);
        error_log("Stored Hashed Password: " . $hashed_password);

        // Verify the password
        if (password_verify($password, $hashed_password)) {
            $_SESSION['user_id'] = $user_id;
            $_SESSION['username'] = $username;
            header("Location: landingpage.php");
            exit;
        } else {
            error_log("Password verification failed.");
            $_SESSION['error_message'] = "Invalid username or password.";
            header("Location: user_login.php");
            exit;
        }
    } else {
        error_log("Username not found.");
        $_SESSION['error_message'] = "Invalid username or password.";
        header("Location: user_login.php");
        exit;
    }

 
}
?>
