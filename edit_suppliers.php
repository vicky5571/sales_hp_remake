<?php
include 'conn.php';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['supplier_id'];
    $name = $_POST['supplier_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];

    $query = "UPDATE SUPPLIERS SET SUPPLIER_NAME = '$name', EMAIL = '$email', PHONE = '$phone' WHERE SUPPLIER_ID = $id";
    if ($conn->query($query)) {
        echo "<script>alert('Supplier updated successfully!'); window.location.href='suppliers.php';</script>";
    } else {
        echo "<script>alert('Error: " . $conn->error . "');</script>";
    }
}
