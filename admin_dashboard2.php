<?php
session_start();

include("db_connect.php");
include("admin_functions.php");

if (!isset($_SESSION['admin_username'])) {
    header("Location: admin_login.php");
    exit();
}

// Menambahkan film baru
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_movie'])) {
    $movie_title = $_POST['movie_title'];
    $genre = $_POST['genre'];
    $duration = $_POST['duration'];
    $rating = $_POST['rating'];
    $time = $_POST['time'];
    $date = $_POST['date'];
    $image = $_POST['image']; // Ambil URL gambar dari input

    // Simpan URL gambar langsung ke variabel $image_path
    $image_path = $image;

    // Panggil fungsi addMovie dengan parameter yang sesuai
    if (addMovie($movie_title, $genre, $duration, $rating, $time, $date, $image)) {
        echo "Movie added successfully.";
    } else {
        echo "Failed to add movie.";
    }
}


// Mengambil daftar film
$movies = getMovies();

// Menghapus film
if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];

    if (deleteMovie($delete_id)) {
        echo "Movie deleted successfully.";
        // Refresh halaman setelah menghapus
        header("Location: admin_dashboard.php");
        exit();
    } else {
        echo "Failed to delete movie.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <title>FlipBox</title>
</head>

<body class="bg-gray-950 flex flex-col items-center min-h-screen p-4">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        .p {
            background-color: #081b29;
        }

        #logo {
            transition: 0.3s ease;

        }

        #logo:hover {
            text-shadow: 0 0 10px red,
                0 0 20px red;
            color: red;
        }

        .custom-box-shadow {
            box-shadow: 0 0 15px red, 0 0 25px red;
        }

        .custom-transition {
            transition: 0.3s ease;
        }

        .hover-scale:hover {
            transform: scale(1.1);
            color: red;
            text-shadow: 0 0 10px red, 0 0 20px red;
        }

        .slider-wrapper {
            overflow: hidden;
            width: 100%;
            max-width: 350px;
            margin: auto;
        }

        .slider {
            display: flex;
            transition: transform 1s ease-in-out;
        }



        @media (min-width: 1024px) {
            .slider-wrapper {
                max-width: 700px;
            }
        }

        .navbar-fixed {
            position: fixed;
            z-index: 9999;
            color: rgb(15 23 42);
            backdrop-filter: blur(5px);
            box-shadow: inset 0 -1px 0 0 rgba(0, 0, 0, 0.2);
        }

        .hamburger-line {
            width: 30px;
            height: 2px;
            margin-top: 0.5rem;
            display: block;
            text-shadow: 0 0 10px pink,
                0 0 20px pink;
            color: pink;
        }

        .hamburger-active>span:nth-child(1) {
            transform: rotate(45deg);
        }

        .hamburger-active>span:nth-child(2) {
            transform: scale(0);
        }

        .hamburger-active>span:nth-child(3) {
            transform: rotate(-45deg);
        }

    </style>

<!-- navbar starts here -->
<header class="bg-transparent absolute top-0 left-0 w-full flex items-center z-10">
        <div class="container mx-auto">
            <div class="flex items-center justify-between relative">
                <div class="px-4">
                    <div id="logo">
                        <a href="" class="font-bold text-xl text-red-600 py-6 block transition duration-300 hover:text-red-600 hover:scale-105 lg:px-32 lg:scale-150">Admin's Dashboard</a>
                    </div>
                </div>
                <div class="flex items-center px-4">
                    <button id="hamburger" name="hamburger" type="button" class="block absolute right-4 lg:hidden">
                        <span
                            class="hamburger-line bg-red-700 h-1 w-6 block mb-1 transition duration-300 ease-in-out origin-top-left"></span>
                        <span class="hamburger-line bg-red-700 h-1 w-6 block mb-1"></span>
                        <span
                            class="hamburger-line bg-red-700 h-1 w-6 block mb-1 transition duration-300 ease-in-out origin-bottom-left"></span>
                    </button>

                    <nav id="nav-menu"
                        class="hidden absolute py-5 bg-gray-900 shadow-lg rounded-lg max-w-[250px] w-full right-4 top-full lg:block lg:static lg:bg-transparent lg:max-w-full lg:shadow-none lg:rounded-none">
                        <ul class="block lg:flex">
                            <li class="group"><a href="admin_dashboard.php"
                            class="text-white hover:text-red-600 hover:transition duration-300 hover:font-bold ml-10 py-2 mx-8 flex">Movie List</a>
                                   
                            </li>
                            <li><a href="admin_dashboard2.php"
                            class="underline underline-offset-8 font-bold text-red-600 ml-10 py-2 mx-8 flex">Add Movie</a>
                            </li>
                        </ul>
                    </nav>

                </div>
            </div>
        </div>
    </header>
<!-- navbar ends here -->

<!-- form add movie starts here -->
<br></br>
<br></br>
<br></br>
<div class="flex-grow flex items-center justify-center w-full">
        <div class="bg-gray-800 p-6 rounded-lg shadow-lg w-full max-w-lg">
            <h2 class="text-white text-2xl font-bold text-center mb-4">Add New Movie</h2>

            <form method="POST" action="" enctype="multipart/form-data" class="space-y-4">
                <div>
                    <label for="movie_title" class="block text-white">Movie Title:</label>
                    <input type="text" id="movie_title" name="movie_title" required class="w-full p-2 rounded bg-gray-700 text-white">
                </div>

                <div>
                    <label for="genre" class="block text-white">Genre:</label>
                    <input type="text" id="genre" name="genre" required class="w-full p-2 rounded bg-gray-700 text-white">
                </div>

                <div>
                    <label for="duration" class="block text-white">Duration:</label>
                    <input type="text" id="duration" name="duration" required class="w-full p-2 rounded bg-gray-700 text-white">
                </div>

                <div>
                    <label for="rating" class="block text-white">Rating:</label>
                    <input type="text" id="rating" name="rating" required class="w-full p-2 rounded bg-gray-700 text-white">
                </div>

                <div>
                    <label for="time" class="block text-white">Time:</label>
                    <input type="time" id="time" name="time" required class="w-full p-2 rounded bg-gray-700 text-white">
                </div>

                <div>
                    <label for="date" class="block text-white">Date:</label>
                    <input type="date" id="date" name="date" required class="w-full p-2 rounded bg-gray-700 text-white">
                </div>

                <div>
                    <label for="image_url" class="block text-white">Image URL:</label>
                    <input type="url" id="image" name="image" placeholder="https://example.com/image.jpg" required class="w-full p-2 rounded bg-gray-700 text-white">
                </div>

                <div>
                    <button type="submit" name="add_movie" class="w-full py-2 bg-red-700 text-white font-bold rounded hover:bg-red-800 hover:scale-105 transition duration-300">Add Movie</button>
                </div>
            </form>

            
        </div>
    </div>
  <!-- form add movie ends here -->