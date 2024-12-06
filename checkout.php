<?php
require_once __DIR__ . '/bootstrap.php';

use Fienwouters\Onlinestore\Db;
use Fienwouters\Onlinestore\Cart;
use Fienwouters\Onlinestore\Order;

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user']['id'];
$cart = new Cart($user_id);
$order = new Order($user_id);

// Haal de producten in het winkelmandje op
try {
    $conn = Db::getConnection();
    $statement = $conn->prepare("
        SELECT c.product_id, p.product_name, p.unit_price, c.quantity, c.product_type 
        FROM cart c
        JOIN products p ON c.product_id = p.id
        WHERE c.user_id = :user_id
    ");
    $statement->bindValue(':user_id', $user_id, PDO::PARAM_INT);
    $statement->execute();
    $cart_items = $statement->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Fout bij het ophalen van het winkelmandje: " . $e->getMessage();
    exit();
}

$total = 0;
foreach ($cart_items as $item) {
    $total += $item['unit_price'] * $item['quantity'];
}

// Haal het huidige saldo van de gebruiker op
try {
    $stmt = $conn->prepare("SELECT wallet FROM users WHERE id = :id");
    $stmt->bindValue(':id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        throw new Exception("Gebruiker niet gevonden.");
    }

    $wallet = $user['wallet'];

    // Plaats de bestelling
    $message = $order->placeOrder($cart_items, $total, $wallet);
} catch (Exception $e) {
    $message = "Fout bij het plaatsen van de bestelling: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Afrekenen</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/checkout.css">
</head>
<body>
<nav class="navbar">
    <a href="index.php" class="navbar__link">Home</a>
    <a href="cart_view.php" class="navbar__link">Winkelmandje</a>
    <a href="profile.php" class="navbar__logout">Mijn profiel</a>
</nav>
<main class="checkout">
    <h1 class="checkout__title">Afrekenen</h1>

    <?php if (isset($message)): ?>
        <p class="checkout__message"><?php echo htmlspecialchars($message); ?></p>
        <a href="orders.php" class="checkout__link">Bekijk mijn bestellingen</a>
    <?php endif; ?>

    <a href="index.php" class="checkout__back-link">Terug naar Home</a>
</main>
</body>
</html>
