<?php

require_once "model.php";

class Product extends Model{

    protected $id = null;
    protected $name = null;
    protected $price = null;
    protected $description = null;
    protected $sku = null;
    protected $stock = null;
    protected $saleable = null;
    protected $created = null;
    protected $img = null;

function __construct(
    $id, 
    $name, 
    $price, 
    $description, 
    $sku,  
    $stock, 
    $saleable, 
    $created, 
    $img)
    {
 $this->id = $id;
 $this->name = $name;
 $this->price = $price;
 $this->description = $description;
 $this->sku = $sku;
 $this->stock = $stock;
 $this->saleable = $saleable;
 $this->created = $created;
 $this->img = $img;
}

function getId(){
    return $this->id;
}
function setId($newid){
 $this->id = $newid;
}

function getName(){
    return $this->name;
}
function setName($value){
    if(empty($value) == false){
        $this->name = $value;
    }
 
}

function getPrice(){
    return $this->price;
}
function setPrice($value){
    if ($value > 0){
        $this->price = $value;
    }
 
}

function getDesc(){
    return $this->description;
}
function setDesc($value){
 $this->description = $value;
}

function getSku(){
    return $this->sku;
}
function setSku($value){
 $this->sku = $value;
}

function getAllImageUrls() {
    $connection = parent::getConection();
    
    $query = "SELECT images.url_path
              FROM images
              JOIN products ON products.img_id = images.id
              WHERE products.id = ?";

    $statement = $connection->prepare($query);
    $statement->bind_param('i', $this->id); 

    $executionResult = $statement->execute();

    if ($executionResult === false) {
        die('Execute failed: ' . htmlspecialchars($statement->error));
    }

    $result = $statement->get_result();
    $imageUrls = [];

    while ($imgData = $result->fetch_assoc()) {
        $imageUrls[] = $imgData["url_path"];
    }

    return $imageUrls;
}


function setImgUrl($value){
 $this->img = $value;
}


public function print(){
    echo 
    "Product id: ".  $this->id. "</br>" . 
    " name: " . $this->name . "</br>" . 
    " sku: " . $this->sku . "</br>" .
    " desc: " . $this->description . "</br>" .
    "price: " . $this->price . "</br>";
}
public function save() {
    $connection = parent::getConection();

    if ($this->id === null) {
        // If the product ID is not set, insert a new product
        $query = "INSERT INTO products 
        (name, price, product_description, sku) 
        VALUES (?, ?, ?, ?)";
        $statement = $connection->prepare($query);
        $statement->bind_param("sdss", 
        $this->name, 
        $this->price, 
        $this->description, 
        $this->sku);
    } else {
        // If the product ID is set, update the existing product
        $query = "UPDATE products SET 
        name = ?, price = ?, product_description = ?, sku = ? 
        WHERE id = ?";
        $statement = $connection->prepare($query);
        $statement->bind_param("sdssi", 
        $this->name, 
        $this->price, 
        $this->description, 
        $this->sku, 
        $this->id);
    }

    $result = $statement->execute();

    if ($result === false) {
        die('Save failed: ' . htmlspecialchars($statement->error));
    }
    // If inserting a new product, set the ID to the last inserted ID
    if ($this->id === null) {
        $this->id = $connection->insert_id;
    }

    echo "Product saved successfully.";
}


};

function getProduct($connection, $sku){
    $query = "SELECT * FROM products WHERE sku = ?";

    $statement = $connection->prepare($query);
    $statement->bind_param('s', $sku);
    $executionResult = $statement->execute();
    if ($executionResult === false) {
    die('Execute failed: ' . htmlspecialchars($statement->error));
}

    $result = $statement->get_result();
    $productData = $result->fetch_assoc();
   
    
   if($productData != null){
    $id = $productData['id'];
    $name = $productData['name'];
    $price = $productData['price'];
    $description = $productData['product_description'];
    $stock = $productData['stock'];
    $saleable = $productData['saleable'];
    $created = $productData['created'];
    $img = $productData['img_id'];

    $product = new Product($id, $name, $price, $description, $sku, $stock, $saleable, $created, $img);
    return $product;

   }else{
    echo "Product not found";
   }
};

function getProductById($connection, $product_id) {
    $query = "SELECT * FROM products WHERE id = ?";
    $statement = $connection->prepare($query);
    $statement->bind_param('i', $product_id);

    $executionResult = $statement->execute();

    if ($executionResult === false) {
        die('Execute failed: ' . htmlspecialchars($statement->error));
    }

    $result = $statement->get_result();
    $productData = $result->fetch_assoc();

    if ($productData !== null) {
        $id = $productData["id"];
        $name = $productData["name"];
        $price = $productData["price"];
        $sku = $productData["sku"];
        $description = $productData['product_description'];
        $stock = $productData['stock'];
        $saleable = $productData['saleable'];
        $created = $productData['created'];
        $img = $productData['img_id'];

        $product = new Product(
            $id, 
            $name, 
            $price, 
            $sku, 
            $description, 
            $stock, 
            $saleable, 
            $created, 
            $img);

        return $product;
    } else {
        echo "Produkten kunde inte hittas!";
        return null;
    }

}

function getAllProducts($connection) {
    $query = "SELECT * FROM products";
    $result = $connection->query($query);

    if ($result === false) {
        die('Query failed: ' . htmlspecialchars($connection->error));
    }

    $products = [];

    while ($row = $result->fetch_assoc()) {
        $id = $row['id'];
        $name = $row['name'];
        $price = $row['price'];
        $sku = $row['sku'];
        $description = $row['product_description'];
        $stock = $row['stock'];
        $saleable = $row['saleable'];
        $created = $row['created'];
        $img = $row['img_id'];

        $product = new Product(
            $id, 
            $name, 
            $price, 
            $sku, 
            $description, 
            $stock, 
            $saleable, 
            $created, 
            $img);
        $products[] = $product;
    }

    return $products;
}

function getProductIdByName($connection, $productName) {
    $productName = mysqli_real_escape_string($connection, $productName);
    $query = "SELECT id FROM products WHERE name = '$productName'";
    $result = mysqli_query($connection, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        return $row['id'];
    }

    return null;
}


