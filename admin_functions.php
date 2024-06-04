<?php

function addMovie($movie_title, $genre, $duration, $rating, $time, $date, $image) {
    $conn = connectDB();

    $sql = "INSERT INTO schedules (movie_title, genre, duration, rating, time, date, image)
            VALUES ('$movie_title', '$genre', '$duration', '$rating', '$time', '$date', '$image')";

    if ($conn->query($sql) === TRUE) {
        $conn->close();
        return true;
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
        $conn->close();
        return false;
    }
}

function getMovies() {
    $conn = connectDB();

    $sql = "SELECT * FROM schedules";
    $result = $conn->query($sql);

    $movies = array();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $movies[] = $row;
        }
    }

    $conn->close();
    return $movies;
}



// Function to update a movie in the database
function update_movie($conn, $schedule_id, $movie_title, $genre, $duration, $rating, $time, $date, $image) {
    // Prepare the SQL query with placeholders
    $sql = "UPDATE schedules SET 
            movie_title = ?, 
            genre = ?, 
            duration = ?, 
            rating = ?, 
            time = ?, 
            date = ?, 
            image = ? 
            WHERE schedule_id = ?";

    // Prepare the statement
    $stmt = $conn->prepare($sql);

    // Bind parameters
    $stmt->bind_param("sssssssi", $movie_title, $genre, $duration, $rating, $time, $date, $image, $schedule_id);

    // Execute the statement
    if ($stmt->execute()) {
        $stmt->close();
        return true;
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
        $stmt->close();
        return false;
    }
}


function deleteMovie($schedule_id) {
    $conn = connectDB();

    $sql = "DELETE FROM schedules WHERE schedule_id = $schedule_id";

    if ($conn->query($sql) === TRUE) {
        $conn->close();
        return true;
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
        $conn->close();
        return false;
    }
}



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    
</body>
</html>
