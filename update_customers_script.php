<?php
require_once "database.php";

$connection = getDataBaseConnection();

$query = "SELECT id, personal_num FROM customers";
$result = $connection->query($query);

if ($result === false) {
    // Error handling for query failure
    die('Query failed: ' . htmlspecialchars($connection->error));
}

while ($row = $result->fetch_assoc()) {
    $customerId = $row['id'];
    $personalNum = $row['personal_num'];

    // Hash the personal_num using password_hash
    $hashedPersonalNum = password_hash($personalNum, PASSWORD_BCRYPT);

    // Update the customer record with the hashed personal_num
    $updateQuery = "UPDATE customers SET personal_num = ? WHERE id = ?";
    $updateStatement = $connection->prepare($updateQuery);
    $updateStatement->bind_param("si", $hashedPersonalNum, $customerId);

    // Execute the update query
    $updateResult = $updateStatement->execute();

    if ($updateResult === false) {
        // Error handling for execute()-failure
        die('Update failed: ' . htmlspecialchars($updateStatement->error));
    }

    echo "Customer with ID $customerId updated successfully.\n";
}

echo "Script executed successfully.";
?>
