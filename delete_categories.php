<?php
include 'conn.php';

session_start();

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $query = "DELETE FROM CATEGORIES WHERE CATEGORY_ID = $id";

    if (mysqli_query($conn, $query)) {
        $_SESSION['delete_alert'] = "Category deleted successfully!";
    } else {
        $_SESSION['delete_alert'] = "Failed to delete category: " . mysqli_error($conn);
    }

    header("Location: categories.php");
    exit();
} else {
    $_SESSION['delete_alert'] = "Invalid category ID.";
    header("Location: categories.php");
    exit();
}
