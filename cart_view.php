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
    $conn = Db::getConnection();
    $statement = $conn->prepare("
        SELECT c.product_id, p.product_name, p.unit_price, c.quantity 
        FROM cart c
        JOIN products p ON c.product_id = p.id
        WHERE c.user_id = :user_id
    ");
    $statement->bindValue(':user_id', $user_id, PDO::PARAM_INT); // Bind de juiste user_id
    $statement->execute();
    $cart_items = $statement->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
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
</head>
<body>
<nav class="navbar">
    <a href="index.php">Home</a>
    
    <form action="" method="get">
        <input type="text" name="search" placeholder="Zoek producten...">
    </form>
    
    <a href="cart_view.php">Winkelmandje</a>
    <a href="profile.php" class="navbar__logout">Mijn profiel</a>
</nav>
<h1>Winkelmandje</h1>

<?php if (isset($message)): ?>
    <p><?php echo htmlspecialchars($message); ?></p>
<?php endif; ?>

<?php if (empty($cart_items)): ?>
    <p>Je winkelmandje is leeg.</p>
<?php else: ?>
    <table>
        <thead>
            <tr>
                <th>Product</th>
                <th>Prijs</th>
                <th>Aantal</th>
                <th>Actie</th>
            </tr>
        </thead>
        <tbody>
            <?php $total = 0; ?>
            <?php foreach ($cart_items as $item): ?>
                <tr>
                    <td><?php echo htmlspecialchars($item['product_name']); ?></td>
                    <td>€<?php echo number_format($item['unit_price'], 2, ',', '.'); ?></td>
                    <td><?php echo htmlspecialchars($item['quantity']); ?></td>
                    <td>
                        <a href="cart_view.php?removeProduct=<?php echo $item['product_id']; ?>">Verwijderen</a>
                    </td>
                </tr>
                <?php $total += $item['unit_price'] * $item['quantity']; ?>
            <?php endforeach; ?>
        </tbody>
    </table>
    <p><strong>Totaal:</strong> €<?php echo number_format($total, 2, ',', '.'); ?></p>
<?php endif; ?>

<a href="checkout.php">Afrekenen</a>
</body>
</html>