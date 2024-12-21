<?php
session_start();
session_destroy();

include_once("conn.php");

$conn -> query("SET foreign_key_checks = 0");
$conn -> query("DELETE FROM carts");
$conn -> query("UPDATE product_unit
         SET added_to_cart = 0
         WHERE imei IN (SELECT imei FROM cart_items)");
$conn -> query("DELETE FROM cart_items");
$conn -> query("SET foreign_key_checks = 1");

// Redirect to the login page
header('Location: login.php');
exit;
?>
