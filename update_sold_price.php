<?php
include 'conn.php';
header('Content-Type: application/json');

$input = json_decode(file_get_contents('php://input'), true);

if (isset($input['cart_item_id'], $input['sold_price'])) {
    $cartItemId = intval($input['cart_item_id']);
    $soldPrice = floatval($input['sold_price']);

    $updateQuery = "UPDATE cart_items SET sold_price = ? WHERE cart_item_id = ?";
    $stmt = $conn->prepare($updateQuery);
    $stmt->bind_param("di", $soldPrice, $cartItemId);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => $stmt->error]);
    }

    $stmt->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid input']);
}

$conn->close();
