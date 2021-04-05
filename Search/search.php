<?php
session_start();

if (!isset($_SESSION['username'])) {
    $_SESSION['msg'] = "You must log in first";
    header('location: ../Login/login.php');
}
if (isset($_GET['logout'])) {
    session_destroy();
    unset($_SESSION['username']);
    header("location: ../Login/login.php");
}
?>

<?php if (isset($_SESSION['username'])) : ?>
    <?php
    include '../Database/database_Ferrell_Patel.php';
    include 'search_functions.php';

    // connect to the database
    $pdo = pdo_connect_mysql();
    $db = pdo_connect_mysqli();
    $search_products = array();

    if (isset($_POST['term'])) {
        $searchterm = mysqli_real_escape_string($db, $_POST['term']);

        // $term = $_REQUEST['term'];    

        $sql = "SELECT id, name, price, quantity, img FROM products WHERE name LIKE '%" . $searchterm . "%'";
        $r_query = mysqli_query($db, $sql);

        $search_products = mysqli_fetch_array($r_query);
        echo <<<EOT
        <form method="post">
        Search: <input type="text" name="term" />
        <input type="submit" value="Submit" />
        </form>
        <table>
        EOT;
        foreach ($search_products as $product) {
        echo <<<EOT
            <tr>
            <td>
                <a href="cart.php?product_id=<?= $product['id'] ?>&quantity=<?= $product['quantity'] ?>" class="product">

                    <img src="../Images/<?= $product['img'] ?>" width="200" height="200" alt="<?= $product['name'] ?>">
                    <span class="name"><?= $product['name'] ?></span>
                    <span class="price">
                        &dollar;<?= $product['price'] ?>
                        <?php if ($product['rrp'] > 0) : ?>
                            <span class="rrp">&dollar;<?= $product['rrp'] ?></span>
                        <?php endif; ?>
                    </span>
                </a>

                <form method="post">
                    <input type="number" name="quantity" value="1" min="1" max="<?= $product['quantity'] ?>" placeholder="Quantity" required>
                    <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                    <input type="submit" name="insert_into_cart" value="Add to cart">
                </form>
            </td>
            </tr>
            EOT;
        }
       echo <<<EOT 
       </table>
        EOT;
      
    }


    ?>

    <?= template_header('Products') ?>

    <div class="products content-wrapper">
        <h1>Products</h1>

        <form method="post">
            Search: <input type="text" name="term" />
            <input type="submit" value="Submit" />
        </form>
        <table>
        <?php foreach ($search_products as $product) : ?>
            <tr>
            <td>
                <a href="cart.php?product_id=<?= $product['id'] ?>&quantity=<?= $product['quantity'] ?>" class="product">

                    <img src="../Images/<?= $product['img'] ?>" width="200" height="200" alt="<?= $product['name'] ?>">
                    <span class="name"><?= $product['name'] ?></span>
                    <span class="price">
                        &dollar;<?= $product['price'] ?>
                        <?php if ($product['rrp'] > 0) : ?>
                            <span class="rrp">&dollar;<?= $product['rrp'] ?></span>
                        <?php endif; ?>
                    </span>
                </a>

                <form method="post">
                    <input type="number" name="quantity" value="1" min="1" max="<?= $product['quantity'] ?>" placeholder="Quantity" required>
                    <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                    <input type="submit" name="insert_into_cart" value="Add to cart">
                </form>
            </td>
            </tr>
         <?php endforeach; ?>
        </table>
    </div>
<?php endif ?>
<?= template_footer() ?>