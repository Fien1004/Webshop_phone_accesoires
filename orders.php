<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/bootstrap.php';

use Fienwouters\Onlinestore\Order;

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user']['id'];
$order = new Order($user_id);

try {
    $orders = $order->getOrders();
} catch (Exception $e) {
    echo "Fout bij het ophalen van bestellingen: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mijn Bestellingen</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<nav class="navbar">
    <a href="index.php">Home</a>
    <a href="cart_view.php">Winkelmandje</a>
    <a href="profile.php" class="navbar__logout">Mijn profiel</a>
</nav>
<h1>Mijn Bestellingen</h1>

<?php if (empty($orders)): ?>
    <p>Je hebt nog geen bestellingen geplaatst.</p>
<?php else: ?>
    <table>
        <thead>
            <tr>
                <th>Bestelling ID</th>
                <th>Totaalbedrag</th>
                <th>Datum</th>
                <th>Naam</th>
                <th>E-mailadres</th>
                <th>Adres</th>
                <th>Product</th>
                <th>Type</th>
                <th>Aantal</th>
                <th>Prijs</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($orders as $order): ?>
                <tr>
                    <td><?php echo htmlspecialchars($order['id']); ?></td>
                    <td>€<?php echo number_format($order['total_price'], 2, ',', '.'); ?></td>
                    <td><?php echo htmlspecialchars($order['order_date'] ?? ''); ?></td>
                    <td><?php echo htmlspecialchars($order['firstname'] . ' ' . $order['lastname']); ?></td>
                    <td><?php echo htmlspecialchars($order['email']); ?></td>
                    <td><?php echo htmlspecialchars($order['address'] . ', ' . $order['postal_code'] . ' ' . $order['city'] . ', ' . $order['country']); ?></td>
                    <td><?php echo htmlspecialchars($order['product_name']); ?></td>
                    <td><?php echo htmlspecialchars($order['product_type']); ?></td>
                    <td><?php echo htmlspecialchars($order['quantity']); ?></td>
                    <td>€<?php echo number_format($order['unit_price'], 2, ',', '.'); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>

<a href="index.php">Terug naar Home</a>
</body>
</html>