<?php
include('db.php');
include('push.php'); 

$id = $_GET['id'];
$product = $conn->query("SELECT name FROM products WHERE id=$id")->fetch_assoc();

$sql = "DELETE FROM products WHERE id=$id";
if ($conn->query($sql) === TRUE) {
    sendPushNotification("Producto eliminado: " . $product['name']); 
    header("Location: index.php"); 
    exit();
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}
?>
