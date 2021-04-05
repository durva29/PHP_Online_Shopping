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
    include '../Products/products_functions.php';

    // connect to the database
    $pdo = pdo_connect_mysql();
    $db = pdo_connect_mysqli();


    // The amounts of products to show on each page
    $num_products_on_each_page = 4;
    // The current page, in the URL this will appear as index.php?page=products&p=1, index.php?page=products&p=2, etc...
    $current_page = isset($_GET['p']) && is_numeric($_GET['p']) ? (int)$_GET['p'] : 1;
    // Select products ordered by the date added
    $stmt = $pdo->prepare('SELECT * FROM products ORDER BY date_added DESC LIMIT ?,?');
    // bindValue will allow us to use integer in the SQL statement, we need to use for LIMIT
    $stmt->bindValue(1, ($current_page - 1) * $num_products_on_each_page, PDO::PARAM_INT);
    $stmt->bindValue(2, $num_products_on_each_page, PDO::PARAM_INT);
    $stmt->execute();
    // Fetch the products from the database and return the result as an Array
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
    // Get the total number of products
    $total_products = $pdo->query('SELECT * FROM products')->rowCount();

    if (isset($_POST['insert_into_cart'])) {
        $cart_product_id  = $_POST['product_id'];
        $cart_quantity  = $_POST['quantity'];
        $cart_user_id  = $_SESSION['userid'];

        // Check if the user already have same product in the cart.
        $check_cart_query = 'select count(*) as cnt from usercart where userid = ? and productid = ?';
        $check_cart_stmt = $pdo->prepare($check_cart_query);
        $check_cart_stmt->execute([$cart_user_id, $cart_product_id]);
        $count_of_user_products = $check_cart_stmt->fetchAll(PDO::FETCH_ASSOC);
        if ($count_of_user_products[0]['cnt'] == 0) {
            $insert_into_cart_query = "INSERT INTO usercart (USERID, PRODUCTID, usercartquantity) VALUES ('$cart_user_id','$cart_product_id','$cart_quantity')";
            $insert_cart_stmt = $pdo->prepare($insert_into_cart_query);
            $insert_cart_stmt->execute();
        } else {
            $get_cart_query = "select usercartquantity from usercart where userid=? and productid=?";
            $get_cart_stmt = $pdo->prepare($get_cart_query);
            $get_cart_stmt->execute([$cart_user_id, $cart_product_id]);
            $get_user_products = $get_cart_stmt->fetchAll(PDO::FETCH_ASSOC);
            $newquantity = $get_user_products[0]['usercartquantity'] + $cart_quantity;

            $update_quantity_query =  "UPDATE usercart set usercartquantity=? where userid=? and productid=?";
            $update_cart_stmt = $pdo->prepare($update_quantity_query);
            $update_cart_stmt->execute([$newquantity, $cart_user_id, $cart_product_id]);
        }
    }
    ?>

    <?= template_header('Products') ?>

    <div class="products content-wrapper">
        <h1>Products</h1>
        <p>Total <?= $total_products ?> Products in the inventory</p>

        <div class="products-wrapper">
            <?php foreach ($products as $product) : ?>
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
            <?php endforeach; ?>
        </div>

    </div>
<?php endif ?>
<?= template_footer() ?>