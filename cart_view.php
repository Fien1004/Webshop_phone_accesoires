<?php
require_once __DIR__ . '/bootstrap.php';

use Fienwouters\Onlinestore\Db;
use Fienwouters\Onlinestore\Cart;

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user']['id'];
$cart = new Cart($user_id); // Maak een nieuwe Cart object aan

// Verwijder een product uit het winkelmandje
if (isset($_GET['removeProduct'])) {
    $product_id = $_GET['removeProduct'];

    try {
        $cart->removeProduct($product_id); // Roep de removeProduct methode aan
        $message = "Product is verwijderd uit je winkelmandje!";
    } catch (Exception $e) {
        echo "Fout bij het verwijderen van het product: " . $e->getMessage();
    }
}

try {
    // Haal de producten op die in het winkelmandje van de gebruiker zitten
    $cart_items = $cart->getCartItems();
} catch (Exception $e) {
    echo "Fout bij het ophalen van het winkelmandje: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Winkelmandje</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/cart.css">
</head>
<body>
<nav class="navbar">
    <a href="index.php" id="home-link">Home</a>
    
    <form action="" method="get" id="search-form">
        <input type="text" name="search" placeholder="Zoek producten..." id="search-input">
    </form>
    
    <a href="cart_view.php" id="cart-link">Winkelmandje</a>
    <a href="profile.php" class="navbar__logout" id="profile-link">Mijn profiel</a>
</nav>
<h1 id="cart-title">Winkelmandje</h1>

<?php if (isset($message)): ?>
    <p id="cart-message"><?php echo htmlspecialchars($message); ?></p>
<?php endif; ?>

<?php if (empty($cart_items)): ?>
    <p id="empty-cart">Je winkelmandje is leeg.</p>
<?php else: ?>
    <table id="cart-table">
        <thead>
            <tr>
                <th id="product-heading">Product</th>
                <th id="type-heading">Type</th>
                <th id="price-heading">Prijs</th>
                <th id="quantity-heading">Aantal</th>
                <th id="action-heading">Actie</th>
            </tr>
        </thead>
        <tbody>
            <?php $total = 0; ?>
            <?php foreach ($cart_items as $item): ?>
                <tr class="cart-item">
                    <td class="product-name"><?php echo htmlspecialchars($item['product_name']); ?></td>
                    <td class="product-type"><?php echo htmlspecialchars($item['product_type']); ?></td>
                    <td class="product-price">€<?php echo number_format($item['unit_price'], 2, ',', '.'); ?></td>
                    <td class="product-quantity"><?php echo htmlspecialchars($item['quantity']); ?></td>
                    <td class="product-action">
                        <a href="cart_view.php?removeProduct=<?php echo $item['product_id']; ?>" class="remove-link">Verwijderen</a>
                    </td>
                </tr>
                <?php $total += $item['unit_price'] * $item['quantity']; ?>
            <?php endforeach; ?>
        </tbody>
    </table>
    <p id="total-amount"><strong>Totaal:</strong> €<?php echo number_format($total, 2, ',', '.'); ?></p>
<?php endif; ?>

<a href="checkout.php" id="checkout-link">Afrekenen</a>
</body>
</html>
