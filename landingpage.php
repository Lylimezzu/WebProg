 <?php
    session_start();
    include("db_connect.php");

    // Check if the user is logged in
    if (!isset($_SESSION['user_id'])) {
        header("Location: user_login.php");
        exit;
    }

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
                             <li class="group"><a href="landing.php" class="underline underline-offset-8 font-bold text-red-600 ml-10 py-2 mx-8 flex">Schedule</a>
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

     
<div class="xl:ml-20">
     <!-- header title -->
    <div class="text-white font-bold text-3xl mt-28 ml-12">
        <a>FlipBox's Movie List</a>
    </div>

    <!-- sub title start here -->
    <div class="flex pt-4 text-white xl:mb-10">
        <a class="flex items-center w-auto bg-transparent text-white text-2xl font-normal font-sans px-12 py-2">
            <i class='bx bx-male bx-tada text-2xl text-white mr-2'></i>
            Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?> !
        </a>
    </div>
    <!-- sub title end here -->   

     <!-- movie lists starts here -->
     <div class="px-10 flex-wrap justify-center xl:mx-auto">

         <!-- membuat variable baru untuk representatifkan function getMovies -->
         <?php foreach ($movies as $movie) : ?>
             <div class="flex p-4 xl:mb-0">
                 <div class="rounded-md shadow-md overflow-hidden xl:scale-75 xl:-translate-y-16 xl:-ml-11">
                     <img src="<?php echo $movie['image']; ?>" alt="<?php echo $movie['movie_title']; ?>" alt="" width="block xl:img-small w-full">
                 </div>
                 <div class="ml-6">
                     <h3 class="font-semibold text-xl text-white xl:text-3xl">
                         <a><?php echo $movie['movie_title']; ?></a>
                     </h3>

                     <div class="flex flex-col xl:flex-row">
                         <p class="pt-3 font-normal text-xs text-white xl:text-xl xl:pl-1 xl:mt-3">Genre: </p>
                         <p class="xl:ml-4 pt-3 font-normal text-xs text-white xl:text-xl xl:pl-1 xl:mt-3"><?php echo $movie['genre']; ?></p>
                     </div>

                     <div class="flex flex-col xl:flex-row">
                         <p class="pt-3 font-normal text-xs text-white xl:text-xl xl:pl-1 xl:mt-3">Duration: </p>
                         <p class="xl:ml-4 pt-3 font-normal text-xs text-white xl:text-xl xl:pl-1 xl:mt-3"><?php echo $movie['duration']; ?></p>
                     </div>

                     <div class="flex flex-col xl:flex-row">
                         <p class="pt-3 font-normal text-xs text-white xl:text-xl xl:pl-1 xl:mt-3">Rating: </p>
                         <p class="xl:ml-4 pt-3 font-normal text-xs text-white xl:text-xl xl:pl-1 xl:mt-3"><?php echo $movie['rating']; ?></p>
                     </div>

                     <div class="mt-12">
                         <a href="moviedetail.php?schedule_id=<?php echo $movie['schedule_id']; ?>" class="ml-1 inline-block bg-red-700 px-4 py-2 rounded-lg text-white hover:shadow-md hover:scale-105 transition duration-300">Movie Detail</a>
                     </div>


                 </div>
             </div>
         <?php endforeach; ?>
     </div>

     <!-- movie lists ends here -->
     </div>
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