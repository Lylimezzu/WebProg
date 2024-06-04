<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: user_login.php");
    exit;
}

include("db_connect.php");
$conn = connectDB();

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['update'])) {
        $biodata = $_POST['biodata'];

        $stmt = $conn->prepare("UPDATE users SET biodata = ? WHERE id = ?");
        $stmt->bind_param("si", $biodata, $user_id);

        if ($stmt->execute()) {
            $_SESSION['success_message'] = "Profile updated successfully!";
        } else {
            $_SESSION['error_message'] = "Failed to update profile.";
        }

        $stmt->close();
    } elseif (isset($_POST['delete'])) {
        $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
        $stmt->bind_param("i", $user_id);

        if ($stmt->execute()) {
            session_destroy();
            header("Location: user_register.php");
            exit;
        } else {
            $_SESSION['error_message'] = "Failed to delete profile.";
        }

        $stmt->close();
    } elseif (isset($_POST['upload'])) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["profile_picture"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Check if image file is a actual image or fake image
        $check = getimagesize($_FILES["profile_picture"]["tmp_name"]);
        if($check !== false) {
            $uploadOk = 1;
        } else {
            $uploadOk = 0;
        }

        // Check file size
        if ($_FILES["profile_picture"]["size"] > 500000) {
            $uploadOk = 0;
        }

        // Allow certain file formats
        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
            $uploadOk = 0;
        }

        if ($uploadOk == 1) {
            if (move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $target_file)) {
                $stmt = $conn->prepare("UPDATE users SET picture = ? WHERE user_id = ?");
                $stmt->bind_param("si", $target_file, $user_id);

                if ($stmt->execute()) {
                    $_SESSION['success_message'] = "Profile picture updated successfully!";
                } else {
                    $_SESSION['error_message'] = "Failed to update profile picture.";
                }

                $stmt->close();
            }
        } else {
            $_SESSION['error_message'] = "Failed to upload image.";
        }
    }
}

$stmt = $conn->prepare("SELECT username, phone, email, biodata, picture FROM users WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($username, $phone, $email, $biodata, $picture);
$stmt->fetch();
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <script src="https://cdn.tailwindcss.com"></script>
     <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>

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

         .hidden { display: none; }

     </style>

<script>

//navbar mode fixed
window.onscroll = function () {
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

hamburgerBtn.addEventListener('click', function () {
    // tugel utk 'hamburger-active' di class button
    this.classList.toggle('hamburger-active');


    navMenu.classList.toggle('hidden');

    // tugel untuk transformasi dri tiap garis
    hamburgerLines[0].style.transform = this.classList.contains('hamburger-active') ? 'rotate(45deg)' : 'none';
    hamburgerLines[1].style.transform = this.classList.contains('hamburger-active') ? 'scale(0)' : 'none';
    hamburgerLines[2].style.transform = this.classList.contains('hamburger-active') ? 'rotate(-45deg)' : 'none';
});

function toggleEdit() {
            var editSection = document.getElementById("edit-section");
            var viewSection = document.getElementById("view-section");
            if (editSection.classList.contains("hidden")) {
                editSection.classList.remove("hidden");
                viewSection.classList.add("hidden");
            } else {
                editSection.classList.add("hidden");
                viewSection.classList.remove("hidden");
            }
        }

</script>

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

     <body class="bg-gray-950 flex flex-col items-center min-h-screen p-4">
    <div class="flex-grow flex items-center justify-center w-full">
        <div class="bg-gray-800 p-6 rounded-lg shadow-lg w-full max-w-lg">
            <h2 class="text-white text-2xl font-bold text-center mb-4">User Profile</h2>

            <?php
            if (isset($_SESSION['success_message'])) {
                echo "<div class='bg-green-500 text-white p-2 rounded mb-4'>" . $_SESSION['success_message'] . "</div>";
                unset($_SESSION['success_message']);
            }
            if (isset($_SESSION['error_message'])) {
                echo "<div class='bg-red-500 text-white p-2 rounded mb-4'>" . $_SESSION['error_message'] . "</div>";
                unset($_SESSION['error_message']);
            }
            ?>

            <div id="view-section">
                <div class="flex flex-col items-center">
                    <img src="<?php echo htmlspecialchars($profile_picture); ?>" alt="Profile Picture" class="rounded-full w-32 h-32 mb-4">
                    <h3 class="text-white text-xl font-bold"><?php echo htmlspecialchars($username); ?></h3>
                    <p class="text-white"><?php echo htmlspecialchars($phone); ?></p>
                    <p class="text-white mt-2"><?php echo nl2br(htmlspecialchars($biodata)); ?></p>
                </div>
                <div class="mt-4 flex justify-between">
                    <button onclick="toggleEdit()" class="py-2 px-4 bg-blue-500 text-white font-bold rounded-lg hover:bg-blue-600 transition duration-300">Edit Profile</button>
                    <form method="POST" action="user_logout.php">
                        <input type="submit" value="Logout" class="py-2 px-4 bg-gray-500 text-white font-bold rounded-lg hover:bg-gray-600 transition duration-300">
                    </form>
                </div>
                <form method="POST" action="" class="mt-4">
                    <input type="submit" name="delete" value="Delete Profile" class="py-2 px-4 bg-red-500 text-white font-bold rounded-lg hover:bg-red-600 transition duration-300 w-full">
                </form>
            </div>

            <div id="edit-section" class="hidden">
                <form method="POST" action="" enctype="multipart/form-data">
                    <div class="mb-4">
                        <label for="profile_picture" class="block text-white font-bold">Profile Picture:</label>
                        <input type="file" id="profile_picture" name="profile_picture" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">
                        <input type="submit" name="upload" value="Upload Picture" class="mt-2 py-2 px-4 bg-blue-500 text-white font-bold rounded-lg hover:bg-blue-600 transition duration-300">
                    </div>
                </form>

                <form method="POST" action="">
                    <div class="mb-4">
                        <label for="biodata" class="block text-white font-bold">Biodata:</label>
                        <textarea id="biodata" name="biodata" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500"><?php echo htmlspecialchars($biodata); ?></textarea>
                    </div>
                    <div class="flex justify-between">
                        <input type="submit" name="update" value="Done Edit" class="py-2 px-4 bg-green-500 text-white font-bold rounded-lg hover:bg-green-600 transition duration-300">
                        <button type="button" onclick="toggleEdit()" class="py-2 px-4 bg-gray-500 text-white font-bold rounded-lg hover:bg-gray-600 transition duration-300">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>