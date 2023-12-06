<?php

require_once "order.php";
require_once "customer.php";
require_once "database.php";
require_once "orderitem.php";
require_once "discount.php";

$connection = getDataBaseConnection();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Page</title>
    <!-- CSS file -->
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Hello Admin!</h1>
<?php 

//  Delete a selected order
if (isset($_GET['delete_order_id'])) {
    $order_id_to_delete = $_GET['delete_order_id'];
    $success = delete($connection, $order_id_to_delete); 
    if ($success) {
        echo "Order successfully deleted!";
    } else {
        echo "Failed to delete the order.";
    }
}

//  Update the status of an order
if (isset($_POST['update_status'])) {
    $order_id_to_update = $_POST['order_id'];
    $new_status = $_POST['new_status'];
    $success = updateOrderStatus($connection, $order_id_to_update, $new_status);
    if ($success) {
        echo "Order status successfully updated!";
    } else {
        echo "Failed to update order status.";
    }
}

// Handle the form submission to add a new discount code
if (isset($_POST['add_discount'])) {
    $discountCode = $_POST['discount_code'];
    $discountAmount = $_POST['discount_amount'];

    // Create a new Discount object and save it
    $discount = new Discount(null, $discountCode, $discountAmount);
    $discount->save();

    echo "Discount code successfully added!";
}

// Check if the form for shipping is submitted
if ($_SERVER["REQUEST_METHOD"] === "POST") {
   
    if (isset($_POST['shipping_name']) && isset($_POST['shipping_amount'])) {
        
        $shippingName = $_POST['shipping_name'];
        $shippingAmount = $_POST['shipping_amount'];

        // Insert the new shipping option into the database 
        $query = "INSERT INTO shipping_options (name, amount) VALUES (?, ?)";
        $statement = $connection->prepare($query);
        $statement->bind_param("sd", $shippingName, $shippingAmount);
        $result = $statement->execute();

        if ($result) {
            echo "Shipping option added successfully!";
        } else {
            echo "Error adding shipping option.";
        }

        $connection->close();
    }
}

?>
 <div class="forms">
<!-- Form to add a new discount code -->
<form method="post" action="admin.php" class="discount-form">
    <label for="discount_code">Discount Code:</label>
    <input type="text" id="discount_code" name="discount_code" required>

    <label for="discount_amount">Discount Amount:</label>
    <input type="number" id="discount_amount" name="discount_amount" step="0.01" required>

    <input type="submit" name="add_discount" value="Add Discount Code">
</form>

<!-- Form for adding shipping options -->
<form action="admin.php" method="post" class="shipping-form">
    <label for="shipping_name">Shipping Option Name:</label>
    <input type="text" name="shipping_name" required>

    <label for="shipping_amount">Shipping Option Amount (SEK):</label>
    <input type="number" name="shipping_amount" step="0.01" required>

    <button type="submit">Add Shipping Option</button>
</form>
</div>
<?php

// 1. List all orders, their order values, and customers in date order
$orders =  getAllOrdersAndOIbyDate($connection); 
echo "<div class='container'>";
// Display order information
foreach ($orders as $order) {
   
    echo "<div class='order-container'>";
    echo "<div class='order-details'>";
    echo "Order ID: " . $order->getId() . "<br>";
    echo "Customer ID: " . $order->getCustomerId() . "<br>";
    // Get and display the customer name from customers
    $customerName = $order->getCustomerName();
    $customerSurname = $order->getCustomerName();
    echo "Customer Name: " . $customerName . "<br>";
    echo "Order Status: " . $order->getStatus() . "<br>";
    echo "Shipping Method: " . $order->getShippingOptionName() . "<br>";
    echo "Shipping Amount: " . $order->getShippingOptionAmount() . "<br>";
    echo "Total Price: " . $order->getTotal() . "<br>";
    echo "Created Date: " . $order->getCreated() . "<br>";
    echo "</div>";
   

    // Display order items
    echo "<div class='order-items'>";
    echo "Order Items: <br>";
    $orderItems = $order->getOrderItems();
    echo "<table>";
    echo "<tr><th>Order ID</th><th>Product ID</th><th>Price</th><th>Quantity</th><th>Is discounted</th></tr>";
    foreach ($orderItems as $orderItem) {
        echo "<tr>";
        echo "<td>" . $orderItem->getOrderId() . "</td>";
        echo "<td>" . $orderItem->getProductId() . "</td>";
        echo "<td>" . $orderItem->getTotal() . "</td>";
        echo "<td>" . $orderItem->getQuantity() . "</td>";
        echo "<td>" . ($orderItem->isDiscounted() ? 'Yes' : 'No') . "</td>";
        echo "</tr>";
       
    }
    echo "</table>";
    echo "</div>";

     // Form to update order status
     echo "<form method='post' action='admin.php'  class='update-form'>";
     echo "<input type='hidden' name='order_id' value='" . $order->getId() . "'>";
     echo "New Status: " ;
     echo "</br>";
     echo "<select name='new_status' required>";
     echo "<option value=''>Choose status</option>";
     echo "<option value='Processing'>Processing</option>";
     echo "<option value='Completed'>Completed</option>";
     echo "<option value='Shipped'>Shipped</option>";
     echo "<option value='Canceled'>Canceled</option>";
     echo "</select>";
     echo "<input type='submit' name='update_status' value='Update Status'>";
     echo "</form>";
    // Button to delete the order
     echo "<form method='get' action='admin.php' class='delete-form'>";
     echo "<input type='hidden' name='delete_order_id' value='" . $order->getId() . "'>";
     echo "<input type='submit' value='Delete Order'>";
     echo "</form>";
     echo "</div>"; 
    
    /*  echo "<hr class='hr-line'>"; */
    
}
echo "</div>";
?>

</body>
