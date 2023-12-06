<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
echo "My application";
require_once "order.php"; 
require_once "customer.php";
require_once "database.php";
require_once "orderitem.php";
require_once "product.php";
require_once "discount.php";

$connection = getDataBaseConnection();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
   // Handle the customer registration
   if (
       isset($_POST['name']) && 
       isset($_POST['email']) && 
       isset($_POST['address']) && 
       isset($_POST['lastname']) && 
       isset($_POST['city']) && 
       isset($_POST['post_code']) && 
       isset($_POST['personal_num']) && 
       isset($_POST['phone']) &&
       isset($_POST['selected_shipping'])
   ) {

       $customerName = $_POST['name'];
       $customerEfternamn = $_POST['lastname'];
       $customerEmail = $_POST['email'];
       $customerAddress = $_POST['address'];
       $customerCity = $_POST['city'];
       $customerPostNum = $_POST['post_code'];
       $customerPn = $_POST['personal_num'];
       $customerPhone = $_POST['phone'];

       $existingCustomer = getCustomer($connection, $customerEmail);

       if ($existingCustomer) {
           $customerID = $existingCustomer->getId();
       } else {
           $newCustomer = new Customer();
           $newCustomer->setFirstName($customerName);
           $newCustomer->setLastName($customerEfternamn);
           $newCustomer->setEmail($customerEmail);
           $newCustomer->setPhone($customerPhone);
           $newCustomer->setAddress($customerAddress);
           $newCustomer->setCity($customerCity);
           $newCustomer->setPostNum($customerPostNum);
           $newCustomer->setPN($customerPn);
           $newCustomer->save(); 
           $customerID = $newCustomer->getId();
       }

       // Handle the product selection
       if (isset($_POST['selected_products'])) {
             $selectedProducts = $_POST['selected_products'];
             $totalPrice = 0;
        
             $order = new Order(null, 0, date('Y-m-d H:i:s'), $customerID, date('Y-m-d H:i:s'));
             
             // Set the chosen shipping method to the order
             if (isset($_POST['selected_shipping'])) {
                $selectedShippingId = $_POST['selected_shipping'];

                // Retrieve the selected shipping details from the database based on the ID
                $selectedShippingQuery = "SELECT id, name, amount FROM shipping_options WHERE id = ?";
                $stmt = $connection->prepare($selectedShippingQuery);
                $stmt->bind_param("i", $selectedShippingId);

                
                if ($stmt->execute()) {
                    // Get the result
                    $selectedShippingResult = $stmt->get_result();

                    // Check if the result is valid
                    if ($selectedShippingResult && $selectedShippingRow = $selectedShippingResult->fetch_assoc()) {
                        $shippingOptionId = $selectedShippingRow['id'];
                        $shippingName = $selectedShippingRow['name'];
                        $shippingAmount = $selectedShippingRow['amount'];
                    } else {
                        echo "Invalid selected shipping data";
                        exit();
                    }
                } else {
                    
                    echo "Error executing prepared statement for selected shipping";
                    exit();
                }

                $order->setShippingOptionId($shippingOptionId);
                $order->setShippingOptionAmount($shippingAmount);
                // Set shipping option details (name and amount)
                $order->setShippingOptionDetails($shippingName, $shippingAmount);
                echo "Shipping Amount: " . $shippingAmount . "<br>";
                $order->save();

                foreach ($selectedProducts as $productId) {
                    $product = getProductById($connection, $productId);
                    
                    if ($product) {
                        $quantity = $_POST['quantity'][$productId];
                      
                        // Calculate the discounted price
                        $discountCode = $_POST['discount_code'];
                        $discount = getDiscountByCode($connection, $discountCode);
                
                        if ($discount) {
                            // Apply the discount to calculate the discounted price for each product
                            $discountedPrice = $product->getPrice() - ($product->getPrice() * ($discount->getAmount() / 100));
                
                            // Set is_discounted to 1, product has a discount
                            $isDiscounted = 1;
                            $discountId = $discount->getId();
                            // Calculate the total price for the item
                            $price = $discountedPrice * $quantity;
                
                            // Create OrderItem and save it
                            $orderItem = new OrderItem(
                                null, 
                                $price, 
                                $order->getId(), 
                                $quantity, 
                                $productId, 
                                $isDiscounted,
                                $discount->getId() 
                            );
                            $orderItem->save();
                
                            // total price for the entire order
                            $totalPrice += $price;
                        } else {
                            // If no discount, set is_discounted to 0
                            $isDiscounted = 0;
                            $discountId = null;
                            // Calculate the total price for the item without discount
                            $price = $product->getPrice() * $quantity;
                
                            // Create OrderItem and save it without a discount
                            $orderItem = new OrderItem(null, $price, $order->getId(), $quantity, $productId, null, $isDiscounted);
                            $orderItem->save();
                            
                            // total price for the entire order
                            $totalPrice += $price;
                        }
                    } else {
                        echo "Failed to retrieve product with ID: $productId";
                        header("Location: store.php?product_id_error=true");
                        exit();
                    }
                }
                // Get the shipping amount directly from the Order instance
                $shippingAmount = $order->getShippingOptionAmount();
              
                // Add the shipping amount to the total price
                $totalPrice += $shippingAmount;
                
             

                $order->setTotal($totalPrice);
                $order->save();

                if (!$order) {
                    header("Location: store.php?order_failure=true");
                    exit();
                    
                }
               
            } else {
                echo "Missing user information";
                header("Location: store.php");
                exit();
            }
        }
    }
}
// Kontrollera om rabattkoden finns i databasen
$discountCode = $_POST['discount_code'];
if (!empty($discountCode)) {
    $discount = getDiscountByCode($connection, $discountCode);

    if (!$discount || $discount->getCode() !== $discountCode) {
        $_SESSION['error_message'] = "Invalid discount code";
        header("Location: store.php");
        exit();
    }
}

// Kontrollera om några produkter har valts
if (empty($selectedProducts)) {
    $_SESSION['error_message'] = "No selected products, please select a product!";
    header("Location: store.php?no_selected_products=true");
    exit();
} else {
    unset($_SESSION['selected_products']);
    header("Location: store.php?order_success=true");
    exit();
}

?>
