<?php
function template_header($title) {
echo <<<EOT
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>$title</title>
		<link href="products.css" rel="stylesheet" type="text/css">
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
	</head>
	<body>
        <header>
            <div class="content-wrapper">
                <img src="../Images/logo.png" alt="Logo" style="
                height: 15vh;
                width: 7vw;
                padding-top: 5px;
            "/>
                <h1 style="margin-top: 15px;">Online Shop</h1>
                <nav>
                    <a href="./products.php">Products</a>
                    <a href="../Search/search.php">Search</a>
                    <a href="products.php?logout='1'">Logout</a>
                </nav>
                <div class="link-icons">
                    <a href="cart.php">
						<i class="fas fa-shopping-cart"></i>
					</a>
                </div>
            </div>
        </header>
        <main>
EOT;
}
// Template footer
function template_footer() {
$year = date('Y');
echo <<<EOT
        </main>
        <footer>
            <div class="content-wrapper">
                <p>&copy; $year, Online shop System</p>
            </div>
        </footer>
        <script src="script.js"></script>
    </body>
</html>
EOT;
}
?>