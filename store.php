<?php

session_start();

require_once "database.php";
require_once "product.php";

$connection = getDataBaseConnection();

$products = getAllProducts($connection);
$errorMessage = "";

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["submit_order"])) {
    // Hantera produktval
    if (isset($_POST["selected_products"])) {
        $selectedProducts = $_POST["selected_products"];
        $_SESSION['selected_products'] = $selectedProducts;
    } else {
        echo "No Selected Products";
        $errorMessage = "Please select a product";
    }

    // Hantera användaruppgifter
    $userName = $_POST['name'];
    $userLastName = $_POST['lastname'];
    $userEmail = $_POST['email'];
    $userPhone = $_POST['phone'];
    $userCity = $_POST['city'];
    $userPostCode = $_POST['post_code'];
    $userAddress = $_POST['address'];
    $userPersonalNumber = $_POST['personal_num'];

    $discountCode = $_POST['discount_code'];
    // Spara användaruppgifterna i session om det behövs
    $_SESSION['user_info'] = [
        'name' => $userName,
        'lastname' => $userLastName,
        'email' => $userEmail,
        'phone' => $userPhone,
        'city' => $userCity,
        'post_code' => $userPostCode,
        'address' => $userAddress,
        'personal_num' => $userPersonalNumber,
        
    ];
    
    // Kontrollera om rabattkoden finns i databasen
    if (!empty($discountCode)) {
        $discount = getDiscountByCode($connection, $discountCode);
        if (!$discount || $discount->getCode() !== $discountCode) {
            $errorMessage = "Invalid discount code";   
        }
    }

    // Visa felmeddelandet på webbsidan
    if (!empty($errorMessage)) {
        $_SESSION['error_message'] = $errorMessage;
        header("Location: store.php");
        exit();
    } else {
        // Omdirigera till process_checkout.php
        header("Location: process_checkout.php");
        exit();
    }

  
}
?>

<!DOCTYPE html>
<html lang="sv">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles_store.css">
    <title>Grit E-handel</title>
</head>
<body>

    <h1>WELCOME TO GRIT SHOP</h1>
    <!-- Bekräftelsemeddelande -->
    <?php
    if (isset($_GET['order_success'])) { 
        echo "<p>Order successfully added!</p>";
    }
  
    if (!empty($_SESSION['error_message'])) {
        echo "<p>{$_SESSION['error_message']}</p>";
        unset($_SESSION['error_message']);
    }
    ?>
    <!-- Visa produkter -->
    <div class="store">
    <h2>PRODUCTS</h2>
    <form class="form" action="process_checkout.php" method="post">
        <div class="product-container">
            <?php foreach ($products as $product): ?>
                <div class="product-card">
                    <?php 
                        $imageUrls = $product->getAllImageUrls();
                        if (!empty($imageUrls)) {
                            $productImage = $imageUrls[0];
                            echo '<img src="' . $productImage . '" alt="' . $product->getName() . '">';
                        }
                    ?>
                    <div class="product-info">
                        <h3><?php echo $product->getName(); ?></h3>
                        <p>Price: <?php echo $product->getPrice(); ?> kr</p>
                        <label for="quantity">Quantity</label>
                        <input type="number" id="quantity" name="quantity[<?php echo $product->getId(); ?>]" value="1" min="1" required>
                       
                       
                        <input type="checkbox" name="selected_products[]" value="<?php echo $product->getId(); ?>">
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="form-fields">
        <!-- Register user -->
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" required>

        <label for="lastname">Surname:</label>
        <input type="text" id="lastname" name="lastname" required>

        <label for="email">E-mail:</label>
        <input type="email" id="email" name="email" required>

        <label for="phone">Telephon:</label>
        <input type="text" id="phone" name="phone" required>

        <label for="city">City:</label>
        <input type="text" id="city" name="city" required>

        <label for="post_code">Post code:</label>
        <input type="text" id="post_code" name="post_code" required>

        <label for="address">Adress:</label>
        <textarea id="address" name="address" required></textarea>

        <label for="personal_num">Personal number:</label>
        <input type="text" id="personal_num" name="personal_num" required>

        <label for="discount_code">Discount Code:</label>
        <input type="text" id="discount_code" name="discount_code">

        <label for="selected_shipping">Select Shipping Option:</label>
        <select name="selected_shipping" required>
    <?php
    $connection = getDataBaseConnection();
    $query = "SELECT * FROM shipping_options";
    $result = $connection->query($query);

    while ($row = $result->fetch_assoc()) {
        echo "<option value='" . $row['id'] . "'>" . $row['name'] . " - (SEK) " . $row['amount'] . "</option>";
    }

    $connection->close();
    ?>
</select>


        <!-- Knapp för att slutföra köpet -->
        <button type="submit" name="submit_order">Complete purchase</button>
        </div>
    </form>
    </div>
    
</body>
</html>
