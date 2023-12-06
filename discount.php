<?php

require_once "model.php";

class Discount extends Model
{
    protected $id;
    protected $code;
    protected $amount;

    public function __construct($id = null, $code = null, $amount = null)
    {
        $this->id = $id;
        $this->code = $code;
        $this->amount = $amount;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getCode()
    {
        return $this->code;
    }

    public function setCode($code)
    {
        $this->code = $code;
    }

    public function getAmount()
    {
        return $this->amount;
    }

    public function setAmount($amount)
    {
        $this->amount = $amount;
    }

    public function print()
    {
        echo "Discount ID: " . $this->id . "<br>";
        echo "Code: " . $this->code . "<br>";
        echo "Amount: " . $this->amount . "<br>";
    }

    public function save()
    {
        $connection = parent::getConection();

        if ($this->id) {
            // Update existing discount
            $query = "UPDATE discounts SET code = ?, amount = ? WHERE id = ?";
            $statement = $connection->prepare($query);
            $statement->bind_param("sdi", $this->code, $this->amount, $this->id);
        } else {
            // Insert new discount
            $query = "INSERT INTO discounts (code, amount) VALUES (?, ?)";
            $statement = $connection->prepare($query);
            $statement->bind_param("sd", $this->code, $this->amount);
        }

        $result = $statement->execute();

        if ($result === false) {
            die('Save failed: ' . htmlspecialchars($statement->error));
        }
        $statement->close();
    } 
}

 function getDiscountByCode($connection, $code)
    {
        $query = "SELECT * FROM discounts WHERE code = ?";
        $statement = $connection->prepare($query);
        $statement->bind_param("s", $code);
        $statement->execute();
        $result = $statement->get_result();

        // Check if a discount with the given code exists
        if ($result->num_rows > 0) {
            $discountData = $result->fetch_assoc();

            // Create a Discount object and set its properties
            $discount = new Discount();
            $discount->setId($discountData['id']);
            $discount->setCode($discountData['code']);
            $discount-> setAmount($discountData['amount']);
            

            return $discount;
        } else {
            return null;
        }
}
?>
