
<?php
session_start();
    include '../Database/database_Ferrell_Patel.php';
    include '../Products/cart_functions.php';
    // print_r($_SESSION);
    // print_r($_POST);
   // connect to the database
     $pdo = pdo_connect_mysql(); 



     if($_GET){
        if(isset($_GET['type'])){
            if($_GET['type'] == "remove"){
                $cart_users_id = $_SESSION['userid'];
                $cart_product_id = $_GET['productid'];
                $stmt_to_remove = $pdo->prepare('DELETE from usercart where userid=? and productid=?');
                $stmt_to_remove->execute([$cart_users_id, $cart_product_id]);
                // echo '<script type="text/javascript">location.reload(true);</script>';
                header("Location: http://localhost/onlineshop/Products/cart.php");
                exit;
            }   
            // header("Refresh:1", url = 'http://localhost/onlineshop/Products/cart.php'");
        }
        
    }
    
    if(isset($_POST['update'])){
        // $cart_product_id  = $_POST['product_id'];
        // $cart_quantity  = $_POST['quantity'];
        // $cart_user_id  = $_SESSION['userid'];
    
        // print_r($_POST);
        foreach ($_POST as $k => $v) {
            if (strpos($k, 'quantity') !== false && is_numeric($v)) {
                $cart_users_id = $_SESSION['userid'];
                $update_cart_id = str_replace('quantity-', '', $k);
                $update_cart_quantity = (int)$v;
                // Always do checks and validation
                if (is_numeric($update_cart_id) && $update_cart_quantity > 0) {
                    // Update new quantity
                    $update_into_cart_query = "UPDATE usercart set usercartquantity=? where userid=? and productid=?";
                    $update_cart_stmt = $pdo->prepare($update_into_cart_query);
                    $update_cart_stmt->execute([$update_cart_quantity, $cart_users_id, $update_cart_id]);       
                }
            }
        }
        header("Location: http://localhost/onlineshop/Products/cart.php");
                exit;
    }

    
    // Check the session variable for products in cart
        // $products_in_cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : array();
        $products = array();
        $subtotal = 0.00;
        // $temp = count($products_in_cart);
        // If there are products in cart
        // if ($products_in_cart) {
            // There are products in the cart so we need to select those products from the database
            // Products in cart array to question mark string array, we need the SQL statement to include IN (?,?,?,...etc)
            // $array_to_question_marks = implode(',', array_fill(0, count($products_in_cart), '?'));
            // $stmt = $pdo->prepare('SELECT * FROM products WHERE id IN (' . $array_to_question_marks . ')');
            $cart_users_id = $_SESSION['userid'];
            $stmt = $pdo->prepare('SELECT  products.id,products.img, products.name, Products.price, products.quantity, usercart.usercartquantity
            FROM usercart
            INNER JOIN products ON usercart.productid = products.id where usercart.userid = ?');
            // We only need the array keys, not the values, the keys are the id's of the products
            // $stmt->execute(array_keys($products_in_cart));
            $stmt->execute([$cart_users_id]);
            // Fetch the products from the database and return the result as an Array
            $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
            // Calculate the subtotal
            foreach ($products as $product) {
                $subtotal += (float)$product['price'] * (int)$product['usercartquantity'];
            }
        // }

?>
<?=template_header('Cart')?>
    <script>
         var x = <?php echo $flag; ?>;
         var y = <?php echo $id; ?>;
         console.log("product", x, y);
    </script>
<div class="cart content-wrapper">
    <h1>Shopping Cart</h1>
    <form method="POST">
        <table>
            <thead>
                <tr>
                    <td colspan="2">Product</td>
                    <td>Price</td>
                    <td>Quantity</td>
                    <td>Total</td>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($products)): ?>
                <tr>
                    <td colspan="5" style="text-align:center;">You have no products added in your Shopping Cart</td>
                </tr>
                <?php else: ?>
                <?php foreach ($products as $product): ?>
                <tr>
                    <td class="img">
                        <img src="../Images/<?=$product['img']?>" width="50" height="50" alt="<?=$product['name']?>">
                    </td>
                    <td>
                        <a><?=$product['name']?></a>
                        <br>
                        <a href="cart.php?type=remove&productid=<?=$product['id']?>" class="remove">Remove</a>
                    </td>
                    <td class="price">&dollar;<?=$product['price']?></td>
                    <td class="quantity">
                        <input type="number" name="quantity-<?=$product['id']?>" value="<?=$product['usercartquantity']?>" min="1" max="<?=$product['quantity']?>" placeholder="Quantity" required>
                    </td>
                    <td class="price">&dollar;<?=$product['price'] * $product['usercartquantity']?></td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
        <div class="subtotal">
            <span class="text">Subtotal</span>
            <span class="price">&dollar;<?=$subtotal?></span>
        </div>
        <!-- <input type="hidden" name="product_id" value="<?=$product['id']?>"> -->
        <div class="buttons">
            <input type="submit" value="Update" name="update">
            <input type="submit" value="Place Order" name="placeorder">
        </div>
    </form>
</div>

<?php

?>

<?=template_footer()?>