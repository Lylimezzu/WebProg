<?php
include("db_connect.php");

$conn = connectDB();

function getMovies()
{
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

// Mengambil daftar film
$movies = getMovies();


if (isset($_GET['schedule_id']) && is_numeric($_GET['schedule_id'])) {
    $schedule_id = $_GET['schedule_id'];

    // Fetch movie details from the database
    $stmt = $conn->prepare("SELECT * FROM schedules WHERE schedule_id = ?");
    $stmt->bind_param("i", $schedule_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $movie = $result->fetch_assoc();
    } else {
        echo "Movie not found.";
        exit;
    }

    $stmt->close();
    $conn->close();
} else {
    echo "No movie ID provided.";
    exit;
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <title class="text-white"><?php echo htmlspecialchars($movie['movie_title']); ?></title>
</head>

<body class="bg-gray-950">

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

        span {
            text-shadow: 0 0 10px red,
                0 0 15px red;
            color: red;
        }

        .blob1 {
            filter: blur(50px);

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
            text-shadow: 0 0 10px red,
                0 0 20px red;
            color: red;
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

    <header class="bg-transparent absolute top-0 left-0 w-full flex items-center z-10">
        <div class="container mx-auto">
            <div class="flex items-center justify-between relative">
                <div class="px-4">
                    <div id="logo">
                        <a href="" class="font-bold text-xl text-red-600 py-6 block transition duration-300 hover:text-red-600 hover:scale-105 lg:px-32 lg:scale-150">FlipBox</a>
                    </div>
                </div>
                <div class="flex items-center px-4">
                    <button id="hamburger" name="hamburger" type="button" class="block absolute right-4 lg:hidden">
                        <span class="hamburger-line bg-red-700 h-1 w-6 block mb-1 transition duration-300 ease-in-out origin-top-left"></span>
                        <span class="hamburger-line bg-red-700 h-1 w-6 block mb-1"></span>
                        <span class="hamburger-line bg-red-700 h-1 w-6 block mb-1 transition duration-300 ease-in-out origin-bottom-left"></span>
                    </button>

                    <nav id="nav-menu" class="hidden absolute py-5 bg-gray-900 shadow-lg rounded-lg max-w-[250px] w-full right-4 top-full lg:block lg:static lg:bg-transparent lg:max-w-full lg:shadow-none lg:rounded-none">
                        <ul class="block lg:flex">
                            <li class="group"><a href="landingpage.php" class="underline underline-offset-8 font-bold text-red-600 ml-10 py-2 mx-8 flex">Schedule</a>
                            </li>
                            <li><a href="watchlist.php" class="text-white hover:text-red-600 hover:transition duration-300 hover:font-bold ml-10 py-2 mx-8 flex">Watch List</a>
                            </li>
                            <li><a href="user_profile.php" class="text-white hover:text-red-600 hover:transition duration-300 hover:font-bold ml-10 py-2 mx-8 flex">Profile</a>
                            </li>
                        </ul>
                    </nav>

                </div>
            </div>
        </div>
    </header>

    <div class="mt-24 ml-16 text-left">
        <a href="landingpage.php" class="ml-20 inline-block bg-red-700 px-4 py-2 rounded-lg text-white font-bold hover:shadow-md hover:scale-105 transition duration-300">Back</a>
    </div>

    <body class="bg-gray-950 flex flex-col items-center min-h-screen p-4">
        <div class="flex-grow flex items-center justify-center w-full mt-5">
            <div class="flex p-6 rounded-lg shadow-lg w-full max-w-6xl">
                <div class="flex-shrink-0">
                    <img src="<?php echo htmlspecialchars($movie['image']); ?>" alt="<?php echo htmlspecialchars($movie['movie_title']); ?>" class="text-5xl rounded-md">

                </div>
                <div class="flex flex-col justify-center ml-10">
                    <div class="flex justify-between items-start">
                        <h2 class="text-white text-2xl font-bold mb-4"><?php echo htmlspecialchars($movie['movie_title']); ?></h2>
                    </div>
                    <div class="space-y-2 text-white">
                        <p><a class="font-bold">Genre: </a><?php echo htmlspecialchars($movie['genre']); ?></p>
                        <p><a class="font-bold">Duration: </a><?php echo htmlspecialchars($movie['duration']); ?></p>
                        <p><a class="font-bold">Producer: </a> <?php echo htmlspecialchars($movie['producer']); ?></p>
                        <p><a class="font-bold">Director: </a> <?php echo htmlspecialchars($movie['director']); ?></p>
                        <p><a class="font-bold">Writer: </a> <?php echo htmlspecialchars($movie['writer']); ?></p>
                        <p><a class="font-bold">Production: </a> <?php echo htmlspecialchars($movie['production']); ?></p>
                        <p><a class="font-bold">Casts: </a> <?php echo htmlspecialchars($movie['casts']); ?></p>
                    </div>

                    <div class="mt-6 text-white">
                        <h3 class="text-xl font-bold">Synopsis</h3>
                        <p class="mt-3"><?php echo nl2br(htmlspecialchars($movie['summary'])); ?></p>
                    </div>

                </div>
            </div>
        </div>

    </body>


    <script>
        //navbar mode fixed
        window.onscroll = function() {
            const header = document.querySelector('header');
            const fixedNav = header.offsetTop;

            if (window.pageYOffset > fixedNav) {
                header.classList.add('navbar-fixed');
            } else {
                header.classList.remove('navbar-fixed')
            }
        }

        //animasi img slides
        const slider = document.querySelector('.slider');
        const images = document.querySelectorAll('.slider img');
        let index = 0;

        function slideImages() {
            index++;
            if (index >= images.length) {
                index = 0;
            }
            slider.style.transform = `translateX(${-index * 100}%)`;

        }

        setInterval(slideImages, 3000);

        // animasi tugel
        const hamburgerBtn = document.getElementById('hamburger');
        const hamburgerLines = document.querySelectorAll('.hamburger-line');
        const navMenu = document.querySelector('#nav-menu');

        hamburgerBtn.addEventListener('click', function() {
            // tugel utk 'hamburger-active' di class button
            this.classList.toggle('hamburger-active');


            navMenu.classList.toggle('hidden');

            // tugel untuk transformasi dri tiap garis
            hamburgerLines[0].style.transform = this.classList.contains('hamburger-active') ? 'rotate(45deg)' : 'none';
            hamburgerLines[1].style.transform = this.classList.contains('hamburger-active') ? 'scale(0)' : 'none';
            hamburgerLines[2].style.transform = this.classList.contains('hamburger-active') ? 'rotate(-45deg)' : 'none';
        });
    </script>
    <footer class="footer bg-gray-950 text-white py-8">
        <div class="flex justify-center mb-4">
            <a href="https://www.instagram.com/charlnne/" class="mr-4 inline-flex justify-center items-center w-10 h-10 bg-transparent border-2 border-red-700 rounded-full text-red-700 hover:text-slate-900 hover:bg-red-700 transition duration-500">
                <i class='bx bxl-instagram text-2xl'></i>
            </a>
            <a href="https://www.linkedin.com/in/charlene-t-b3790528b/" class="mr-4 inline-flex justify-center items-center w-10 h-10 bg-transparent border-2 border-red-700 rounded-full text-red-700 hover:text-slate-900 hover:bg-red-700 transition duration-500">
                <i class='bx bxl-linkedin text-2xl'></i>
            </a>
            <a href="#" class="mr-4 inline-flex justify-center items-center w-10 h-10 bg-transparent border-2 border-red-700 rounded-full text-red-700 hover:text-slate-900 hover:bg-red-700 transition duration-500">
                <i class='bx bxl-facebook text-2xl'></i>
            </a>
        </div>

        <ul class="list-none flex flex-wrap justify-center mb-4">

            <li class="mr-6 mb-2">
                <a href="landingpage.php" class="text-white hover:font-semibold hover:text-red-700 transition duration-500">Schedule</a>
            </li>
            <li class="mr-6 mb-2">
                <a href="watchlist.php" class="text-white hover:font-semibold hover:text-red-700 transition duration-500">Watch List</a>
            <li class="mr-6 mb-2">
                <a href="user_profile.php" class="text-white hover:font-semibold hover:text-red-700 transition duration-500">Profile</a>

        </ul>

        <p class="text-center text-xs">&copy; 2024 FlipBox | All Rights Reserved</p>
    </footer>
</body>

</html>