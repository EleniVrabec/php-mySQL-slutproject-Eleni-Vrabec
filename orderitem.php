<?php
require_once "model.php";
require_once "product.php";

class OrderItem extends Model{
 protected $id;
 protected $product_id;
 protected $price;
 protected $order_id;
 protected $quantity;
 protected $created;
 protected $is_discounted;
 protected $discount_id; 

 function __construct($id, $price, $order_id, $quantity, $product_id , $is_discounted, $discount_id) {
   $this->id = $id;
   $this->price = $price;
   $this->order_id = $order_id;
   $this->quantity = $quantity;
   $this->product_id = $product_id;
   $this->is_discounted = $is_discounted;
   $this->discount_id = $discount_id;
}


 function getId(){
    return $this->id;
 }

 function setId($value){
    $this->id = $value;
 }
/* ----------------------- */
 function getProductId(){
    return $this->product_id;
 }

 function setProductId($value){
    $this->product_id = $value;
 }

 /* -------------- */

 function getTotal(){
    return $this->price;
 }

 function setTotal($value){
    $this->price = $value;
 }
 /* ---------------------- */
 function getOrderId(){
    return $this->order_id;
 }

 function setOrderId($value){
    $this->order_id = $value;
 }

 function getQuantity() {
   return $this->quantity;
}

function setQuantity($value){
   $this->quantity = $value;
}
public function isDiscounted() {
   return $this->is_discounted;
}

public function getDiscountId()
{
    return $this->discount_id;
}

public function setDiscountId($discount_id)
{
    $this->discount_id = $discount_id;
}


 function print(){
    echo 
     "Order Item Id: " . $this->order_id . "</br>". 
     "product id: " . $this->product_id . "</br>" .
     " total: " . $this->price . "</br>" .
     " order_id: " . $this->order_id . "</br>";
 }


function save() {
   $connection = parent::getConection();
   if ($this->id) {
      $query = "UPDATE order_items SET 
      price = ?, 
      order_id = ?, 
      quantity = ?, 
      product_id = ?, 
      is_discounted = ?, 
      discount_id = ? 
      WHERE id = ?";
      $statement = $connection->prepare($query);
      $statement->bind_param("diiisii", 
      $this->price, 
      $this->order_id, 
      $this->quantity, 
      $this->product_id, 
      $this->is_discounted, 
      $this->discount_id, 
      $this->id);
  } else {
      $query = "INSERT INTO order_items 
      (price, order_id, quantity, created, product_id, is_discounted, discount_id) 
      VALUES (?, ?, ?, CURRENT_TIMESTAMP, ?, ?, ?)";
      $statement = $connection->prepare($query);
      $statement->bind_param("diiisi", 
      $this->price, 
      $this->order_id, 
      $this->quantity, 
      $this->product_id, 
      $this->is_discounted,  
      $this->discount_id);
  }

   $result = $statement->execute();

   if ($result === false) {
       die('Save failed: ' . htmlspecialchars($statement->error));
   }

   $statement->close();
}


function getDiscountCode() {
   
   if ($this->is_discounted) {
       $connection = parent::getConection();
       $query = "SELECT code FROM discounts WHERE id = ?";
       $statement = $connection->prepare($query);
       $statement->bind_param("i", $this->product_id);
       $statement->execute();
       $result = $statement->get_result();

      
       if ($row = $result->fetch_assoc()) {
           return $row['code'];
       } else {
           return null; 
       }
   } else {
       return null; 
   }

}
}
