<?php
require_once "model.php";

class Order extends Model{
    protected $id = null;
    protected $total = null;
    protected $created = null;
    protected $customer_id = null;
    protected $shipped_date = null;
    protected $status = null;
    protected $shippingOptionId = null;
    protected $shippingOptionName = null;
    protected $shippingOptionAmount = null;
    protected $orderItems = array();
    
    function __construct(
        $id = null, 
        $total = null, 
        $created = null, 
        $customer_id = null, 
        $shipped_date = null, 
        $status = null, 
        $shippingOptionId = null)
        {
        $this->id = $id;
        $this->total = $total;
        $this->created = $created;
        $this->customer_id = $customer_id;
        $this->shipped_date = $shipped_date;
        $this->status = $status;
        $this->shippingOptionId = $shippingOptionId;
      
    }

    public function setShippingOptionId($shippingOptionId) {
    $this->shippingOptionId = $shippingOptionId;
    }
    public function setShippingOptionDetails($name, $amount) {
        $this->shippingOptionName = $name;
        $this->shippingOptionAmount = $amount;
    }

    public function getShippingOptionName() {
        return $this->shippingOptionName;
    }
    public function setShippingOptionAmount($shippingAmount) {
        // Convert the shipping amount to float before setting
        $this->shippingOptionAmount = (float)$shippingAmount;
    }
    


    public function getShippingOptionAmount() {
        return $this->shippingOptionAmount;
    }


    function getId(){
        return $this->id;
    }
    function setId($value){
        $this->id = $value;
    }
    /* --------------------------------- */
    function getTotal(){
        return $this->total;
    }
    function setTotal($value){
        $this->total = $value;
    }
    /* ---------------------------------- */
    function getCreated(){
        return $this->created;
    }
    function setCreated($value){
        $this->created = $value;
    }
    /* ----------------------------------- */
    function getCustomerId(){
        return $this->customer_id;
    }
    function setCustomerId($value){
        $this->customer_id = $value;
    }
    /* ---------------------------------- */
    function getShippedDate(){
        return $this->shipped_date;
    }
    function setShippedDate($value){
        $this->shipped_date = $value;
    }

    function getStatus(){
        return $this->status;
    }
    function setStatus($value){
        $this->status = $value;
    }


    function print(){
        echo "</br>" . 
        "Order id: " . $this->id . "</br>" . 
        "Total price: " . $this->total . "</br>" .  
        "Created: " . $this->created . "</br> ". 
        "Customer ID: ". $this->customer_id . "</br> ";
    }

    function save() {
        $connection = parent::getConection();
        if ($this->id) {
            // Update existing order
            $query = "UPDATE orders SET 
            price = ?, 
            customer_id = ?, 
            shipped_date = ?, 
            shipping_option_id = ?  
            WHERE id = ?";
            $statement = $connection->prepare($query);
            $statement->bind_param("iisii", 
            $this->total, 
            $this->customer_id, 
            $this->shipped_date, 
            $this->shippingOptionId, 
            $this->id);
        } else {
            $query = "INSERT INTO orders 
            (price, customer_id, shipped_date, created,  shipping_option_id) 
            VALUES (?, ?, ?, ?, ?)";
            $statement = $connection->prepare($query);
            $statement->bind_param("iissi", 
            $this->total, 
            $this->customer_id, 
            $this->shipped_date, 
            $this->created, 
            $this->shippingOptionId );
            
        }
    
        $result = $statement->execute();
    
        if ($result === false) {
            die('Execute failed: ' . htmlspecialchars($statement->error));
        }
    
        if (!$this->id) {
            $this->id = $statement->insert_id; 
        }
    }
    
    function getOrderItems(){
        $connection = parent::getConection();
        $query = "SELECT * FROM order_items WHERE order_id = ?";
        $statement = $connection->prepare($query);
        $statement->bind_param("i", $this->id);

        $executionResult = $statement->execute();

        if ($executionResult === false) {
            die('Execute failed: ' . htmlspecialchars($statement->error));
        }

        $result = $statement->get_result();

        $orderItems = array();
        while ($row = $result->fetch_assoc()) {
            $id = $row['id'];
            $product_id = $row['product_id'];
            $total = $row['price'];
            $quantity= $row['quantity'];
            $is_discounted = $row['is_discounted'];
            $discount_id = $row['discount_id'];
            
            $orderItem = new OrderItem($id, $total, $this->id, $quantity, $product_id, $is_discounted, $discount_id);
            
            $orderItems[] = $orderItem;
        }

        return $orderItems;
    }
    function setOrderItems($orderItems) {
        $this->orderItems = $orderItems;
    }

    function getCustomerName() {
        $connection = parent::getConection();
        $query = "SELECT firstname, lastname, `address` FROM customers WHERE id = ?";
        $statement = $connection->prepare($query);
        $statement->bind_param("i", $this->customer_id);

        $executionResult = $statement->execute();

        if ($executionResult === false) {
            die('Execute failed: ' . htmlspecialchars($statement->error));
        }

        $result = $statement->get_result();
        $customerData = $result->fetch_assoc();
        if ($customerData) {
            $fullName = $customerData['firstname'] . ' ' . $customerData['lastname'] ."</br>" ."Shipping address: ". $customerData['address'];
            return $fullName;
        } else {
            return 'Unknown Customer';
        }
       // return $customerData ? $customerData['firstname'] : 'Unknown Customer';
    }
    
    }
    
    function delete($connection, $id) {
        $queryOrder = "DELETE FROM orders WHERE id = ?";
        $statementOrder = $connection->prepare($queryOrder);
        $statementOrder->bind_param("i", $id);
        $result = $statementOrder->execute();
        if ($result === false) {
           
            die('Execute failed: ' . htmlspecialchars($statementOrder->error));
        }
    
        return $result;
    }

    // Function to update the status of an order
    function updateOrderStatus($connection, $orderId, $newStatus) {
    $query = "UPDATE orders SET status = ? WHERE id = ?";
    $statement = $connection->prepare($query);
    $statement->bind_param("si", $newStatus, $orderId);

    $result = $statement->execute();

    if ($result === false) {
        
        die('Execute failed: ' . htmlspecialchars($statement->error));
    }

    return $result;
}

    function getOrderById($connection, $id) {
        $query = "SELECT * FROM orders WHERE id = ?";
        $statement = $connection->prepare($query);
        $statement->bind_param("i", $id);
    
        $executionResult = $statement->execute();
    
        if ($executionResult === false) {
            die('Execute failed: ' . htmlspecialchars($statement->error));
        }
    
        $result = $statement->get_result();
        $orderData = $result->fetch_assoc();
    
        if ($orderData !== null) {
            $id = $orderData["id"];
            $total = $orderData["price"];
            $created = $orderData["created"];
            $customer_id = $orderData["customer_id"];
            $shipped_date = $orderData["shipped_date"];
          
            $order = new Order($id, $total, $created, $customer_id, $shipped_date);
            return $order;
        } else {
            return null; 
        }
    }
    

function getAllOrdersAndOIbyDate($connection){
    $query = "SELECT orders.*, 
    shipping_options.name AS 
    shipping_name, 
    shipping_options.amount AS 
    shipping_amount
    FROM orders
    LEFT JOIN shipping_options ON 
    orders.shipping_option_id = shipping_options.id
    ORDER BY created DESC";

    $statement = $connection->prepare($query);
    
    if ($statement) {
        $statement->execute();
        $result = $statement->get_result();
        $orders = array();
        
        while ($row = $result->fetch_assoc()) {
            $id = $row['id'];
            $total = $row['price'];
            $created = $row['created'];
            $customer_id = $row['customer_id'];
            $shipped_date = $row["shipped_date"];
            $status = $row['status'];
            
        
            $order = new Order($id, $total, $created, $customer_id, $shipped_date, $status);
            $order->setShippingOptionDetails($row['shipping_name'], $row['shipping_amount']);
            // Fetch order items for the current order
            $orderItems = $order->getOrderItems();
            $order->setOrderItems($orderItems);

            
            $orders[] = $order;
        }
        
        return $orders;
    } else {
        die('Query failed: ' . $connection->error);
    }
}



