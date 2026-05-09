<?php
require_once __DIR__ . '/db.php';

if (isset($_GET['add_to_cart']) && !empty($_GET['product'])) {
    $productName = trim($_GET['product']);
    $productId = isset($_GET['id']) ? trim($_GET['id']) : preg_replace('/[^a-z0-9_-]+/i', '-', strtolower($productName));
    $productPrice = isset($_GET['price']) ? floatval($_GET['price']) : 0;

    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    if (!isset($_SESSION['cart'][$productId])) {
        $_SESSION['cart'][$productId] = [
            'id' => $productId,
            'name' => $productName,
            'price' => $productPrice,
            'qty' => 0,
        ];
    }

    $_SESSION['cart'][$productId]['qty'] += 1;
    $_SESSION['cart'][$productId]['price'] = $productPrice;

    $redirectUrl = strtok($_SERVER['REQUEST_URI'], '?');
    header('Location: ' . $redirectUrl);
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Game Store</title>
    <link rel="stylesheet" href="CSS/style.css">
</head>
<body>
<!-- <header class="site-header">
    
</header> -->

<div class="site-shell">