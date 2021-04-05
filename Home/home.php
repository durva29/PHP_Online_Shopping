<?php 
include 'home_functions.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Online shop</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="home.css">
</head>

<body>

    <div class="container">
    <?=template_header('Home')?>
        <!-- <div class="navbar">
            <div class="navbar__left">
                <img src="../Images/logo.png" alt="Logo" />
                <h2>Online Shop</h2>
            </div>
            <div class="navbar__right">
                <a href="../Login/register.php" class="navbar__right__links">
                    <h2>Sign up</h2>
                </a>
                <a href="../Login/login.php" class="navbar__right__links">
                    <h2>Login</h2>
                </a>
            </div>
        </div> -->
        <div class="carousel">
                <img class="carousel_img" src="../Images/home_1.png" style="width:100%">
                <img class="carousel_img" src="../Images/home_2.png" style="width:100%">
                <img class="carousel_img" src="../Images/Carousel-3.jpg" style="width:100%">
        </div>
    </div>


    <script>
            var myIndex = 0;
            carousel();

            function carousel() {
                var i;
                var x = document.getElementsByClassName("carousel_img");
                for (i = 0; i < x.length; i++) {
                    x[i].style.display = "none";
                }
                myIndex++;
                if (myIndex > x.length) {
                    myIndex = 1
                }
                x[myIndex - 1].style.display = "block";
                setTimeout(carousel, 2000); // Change image every 2 seconds
            }
        </script>
</body>

</html>