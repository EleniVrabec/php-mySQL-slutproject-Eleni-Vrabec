<?php
require_once "model.php";

class Customer extends Model{
    protected $id = null;
    protected $firstname = null;
    protected $email = null;
    protected $lastname = null;
    protected $personal_num = null;
    protected $phone = null;
    protected $address = null;
    protected $city = null;
    protected $postnum = null;
    protected $created = null;

    function __construct(
        $id = null, 
        $firstname = null, 
        $email = null, 
        $lastname = null, 
        $personal_num = null, 
        $phone = null, 
        $address = null, 
        $city = null, 
        $postnum = null, 
        $created = null)
        {
        $this->id = $id;
        $this->firstname = $firstname;
        $this->email = $email;
        $this->lastname= $lastname;
        $this->personal_num = $personal_num;
        $this->phone = $phone;
        $this->address= $address;
        $this->city = $city;
        $this->postnum = $postnum;
        $this->created = $created;
        }
    /* ---------------------- */
    function getPhone(){
        return $this->phone;
    }
    function setPhone($value){
        $this->phone = $value;
    }
    /* ------------------------ */
    function getPostNum(){
        return $this->postnum;
    }
    function setPostNum($value){
        $this->postnum = $value;
    }
    /* ----------------------- */
    function getCity(){
        return $this->city;
    }
    function setCity($value){
        $this->city = $value;
    }
    /* ------------------------ */
    function getCreated(){
        return $this->created;
    }
    function setCreated($value){
        $this->created = $value;
    }
    /* ------------------------ */
    function getId(){
        return $this->id;
    }
    function setId($value){
        $this->id = $value;
    }
    /* ------------------------ */
    function getFirstName(){
        return $this->firstname;
    }
    function setFirstName($value){
        $this->firstname = $value;
    }
    /* ---------------------- */
    function getEmail(){
        return $this->email;
    }
    function setEmail($value){
        $this->email = $value;
    }
     /* ----------------------- */
    function getAddress(){
        return $this->address;
    }
    function setAddress($value){
        $this->address = $value;
    }
    /* -------------------------*/
    function getLastName(){
        return $this->lastname;
    }
    function setLastName($value){
        $this->lastname = $value;
    }
    /* ------------------------ */

    function getPN(){
        return $this->personal_num;
    }
    function setPN($value){
        $this->personal_num = $value;
    }
    /* ---------------------------------- */
    
    function print(){
        echo "</br>" . 
        "Customer id: " . $this->id . "</br>" . 
        "Firstname: " . $this->firstname . "</br>" .  
        "Lastname: " . $this->lastname . "</br> ". 
        "Email: " . $this->email . "</br> ";
    }

    function save() {
        $connection = parent::getConection();  
        if ($this->id) {
            // Update existing customer
            $query = "UPDATE customers 
            SET firstname = ?, lastname = ?, email = ?, phone = ?, address = ?, city = ?, postnum = ?, personal_num = ? 
            WHERE id = ?";

            $statement = $connection->prepare($query);
            $statement->bind_param("sssssssi", 
            $this->firstname, 
            $this->lastname, 
            $this->email, 
            $this->phone, 
            $this->address, 
            $this->city, 
            $this->postnum, 
            $this->personal_num, 
            $this->id);
        } else {
            // Insert new customer
            $query = "INSERT INTO 
            customers (firstname, lastname, email, phone, `address`, city, postnum, created, personal_num) 
            VALUES (?, ?, ?, ? , ?, ?, ?, ?, ? )";

            $statement = $connection->prepare($query);
            $now = date('Y-m-d H:i:s'); 
            $statement->bind_param("sssssssss", 
            $this->firstname, 
            $this->lastname, 
            $this->email, 
            $this->phone, 
            $this->address, 
            $this->city, 
            $this->postnum, 
            $now, 
            $this->personal_num);
        }
        
        $result = $statement->execute();
        if ($result === false) {
            die('Save failed: ' . htmlspecialchars($statement->error));
        }
        // If it's a new customer, set the generated ID
        if (!$this->id) {
            $this->id = $statement->insert_id; 
        }
        
    }
    
}

function getCustomer($connection, $email){
    $query = "SELECT * FROM customers WHERE email = ?";
    $statement = $connection->prepare($query);
    $statement->bind_param("s", $email);   
    $executionResult = $statement->execute();
    
    if ($executionResult === false) {
        die('Execute failed: ' . htmlspecialchars($statement->error));
    }

    $result = $statement->get_result();
    $customerData = $result->fetch_assoc();
   
   if($customerData != null){
    
    $id = $customerData['id'];
    $firstname = $customerData['firstname'];
    $lastname = $customerData['lastname']; 
    $personal_num = $customerData['personal_num']; 
    $phone = $customerData['phone'];
    $address = $customerData['address'];
    $city = $customerData['city'];
    $created = $customerData['created'];

    $customer = new Customer($id, $firstname, $email,  $lastname, $personal_num, $phone, $address, $city, $created);
    return $customer;

   }else{
    echo "Customer not found";
    return null;
   }


}




