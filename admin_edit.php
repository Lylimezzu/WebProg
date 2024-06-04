<?php
session_start();

include("db_connect.php");
include("admin_functions.php");

$conn = connectDB();

// Check if schedule_id is provided in URL
if (isset($_GET['schedule_id'])) {
    $schedule_id = $_GET['schedule_id'];

    // Fetch movie details from database based on schedule_id
    $query = "SELECT * FROM schedules WHERE schedule_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $schedule_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if movie details found
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
    } else {
        $_SESSION['error_message'] = "Schedule ID not found.";
        header("Location: admin_dashboard.php");
        exit;
    }
} else {
    $_SESSION['error_message'] = "Schedule ID not provided.";
    header("Location: admin_dashboard.php");
    exit;
}

// Handle form submission for updating movie
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve form data
    $schedule_id = $_POST['schedule_id'];
    $movie_title = $_POST['movie_title'];
    $genre = $_POST['genre'];
    $duration = $_POST['duration'];
    $rating = $_POST['rating'];
    $time = $_POST['time'];
    $date = $_POST['date'];
    $image = $_POST['image'];

    // Call the update function
    if (update_movie($conn, $schedule_id, $movie_title, $genre, $duration, $rating, $time, $date, $image)) {
        $_SESSION['success_message'] = "Movie updated successfully!";
        header("Location: admin_dashboard.php");
        exit;
    } else {
        $_SESSION['error_message'] = "Failed to update movie. Please try again.";
        header("Location: admin_edit.php?schedule_id=" . $schedule_id);
        exit;
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Edit Movie</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>

<body class="bg-gray-950 flex flex-col items-center min-h-screen p-4">

    <div class="flex-grow flex items-center justify-center w-full">
        <div class="bg-gray-800 p-6 rounded-lg shadow-lg w-full max-w-lg">
            <h2 class="text-white text-2xl font-bold text-center mb-4">Edit Movie</h2>

            <form method="POST" action="" class="max-w-md mx-auto bg-gray-800 p-6 rounded-lg shadow-md">
                <input type="hidden" name="schedule_id" value="<?php echo htmlspecialchars($row['schedule_id']); ?>">

                <div class="mb-4">
                    <label for="movie_title" class="block text-white font-bold">Movie Title:</label>
                    <input type="text" id="movie_title" name="movie_title" value="<?php echo htmlspecialchars($row['movie_title']); ?>" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">
                </div>

                <div class="mb-4">
                    <label for="genre" class="block text-white font-bold">Genre:</label>
                    <input type="text" id="genre" name="genre" value="<?php echo htmlspecialchars($row['genre']); ?>" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">
                </div>

                <div class="mb-4">
                    <label for="duration" class="block text-white font-bold">Duration:</label>
                    <input type="text" id="duration" name="duration" value="<?php echo htmlspecialchars($row['duration']); ?>" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">
                </div>

                <div class="mb-4">
                    <label for="rating" class="block text-white font-bold">Rating:</label>
                    <input type="text" id="rating" name="rating" value="<?php echo htmlspecialchars($row['rating']); ?>" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">
                </div>

                <div class="mb-4">
                    <label for="time" class="block text-white font-bold">Time:</label>
                    <input type="time" id="time" name="time" value="<?php echo htmlspecialchars($row['time']); ?>" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">
                </div>

                <div class="mb-4">
                    <label for="date" class="block text-white font-bold">Date:</label>
                    <input type="date" id="date" name="date" value="<?php echo htmlspecialchars($row['date']); ?>" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">
                </div>

                <div class="mb-4">
                    <label for="image" class="block text-white font-bold">Image URL:</label>
                    <input type="url" id="image" name="image" value="<?php echo htmlspecialchars($row['image']); ?>" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">
                </div>

                <div class="text-center">
                    <input type="submit" value="Update Movie" class="w-full py-2 bg-blue-500 text-white font-bold rounded-lg hover:bg-blue-600 transition duration-300">
                </div>
            </form>
        </div>
    </div>

</body>

</html>
