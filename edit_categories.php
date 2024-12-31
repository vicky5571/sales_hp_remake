<?php
include 'conn.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $category_id = $_POST['category_id'];
    $category_name = $_POST['category_name'];

    $query = "UPDATE CATEGORIES SET CATEGORY_NAME = ? WHERE CATEGORY_ID = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("si", $category_name, $category_id);

    if ($stmt->execute()) {
        header("Location: categories.php");
    } else {
        echo "Error: " . $stmt->error;
    }
}

session_start();
$_SESSION['save_alert'] = 'Category updated successfully!';
header('Location: categories.php');
exit();
