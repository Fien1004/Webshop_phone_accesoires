<?php
require_once __DIR__ . '/bootstrap.php';

use Fienwouters\Onlinestore\Db;
use Fienwouters\Onlinestore\Cart;

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user']['id'];
$cart = new Cart($user_id);

// Haal de producten in het winkelmandje op
try {
    $conn = Db::getConnection();
    $statement = $conn->prepare("
        SELECT c.product_id, p.product_name, p.unit_price, c.quantity 
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

    // Controleer of de gebruiker voldoende saldo heeft
    if ($wallet < $total) {
        throw new Exception("Onvoldoende saldo om de bestelling te plaatsen.");
    }

    // Begin een transactiesessie
    $conn->beginTransaction();

    // Werk het saldo van de gebruiker bij
    $new_wallet = $wallet - $total;
    $stmt = $conn->prepare("UPDATE users SET wallet = :wallet WHERE id = :id");
    $stmt->bindValue(':wallet', $new_wallet, PDO::PARAM_STR); // Gebruik PDO::PARAM_STR voor nauwkeurigheid
    $stmt->bindValue(':id', $user_id, PDO::PARAM_INT);
    $stmt->execute();

    // Sla de bestelling op in de orders-tabel
    $stmt = $conn->prepare("INSERT INTO orders (user_id, total_price) VALUES (:user_id, :total_price)");
    $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->bindValue(':total_price', $total, PDO::PARAM_STR); // Gebruik PDO::PARAM_STR voor nauwkeurigheid
    $stmt->execute();
    $order_id = $conn->lastInsertId();

    // Sla de order_items op
    $stmt = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, unit_price) VALUES (:order_id, :product_id, :quantity, :unit_price)");
    foreach ($cart_items as $item) {
        $stmt->bindValue(':order_id', $order_id, PDO::PARAM_INT);
        $stmt->bindValue(':product_id', $item['product_id'], PDO::PARAM_INT);
        $stmt->bindValue(':quantity', $item['quantity'], PDO::PARAM_INT);
        $stmt->bindValue(':unit_price', $item['unit_price'], PDO::PARAM_STR); // Gebruik PDO::PARAM_STR voor nauwkeurigheid
        $stmt->execute();
    }

    // Leeg het winkelmandje
    $stmt = $conn->prepare("DELETE FROM cart WHERE user_id = :user_id");
    $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();

    // Commit de transactiesessie
    $conn->commit();

    $message = "Bestelling succesvol geplaatst!";
} catch (Exception $e) {
    // Rollback de transactiesessie bij een fout
    $conn->rollBack();
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
</head>
<body>
<nav class="navbar">
    <a href="index.php">Home</a>
    <a href="cart_view.php">Winkelmandje</a>
    <a href="profile.php" class="navbar__logout">Mijn profiel</a>
</nav>
<h1>Afrekenen</h1>

<?php if (isset($message)): ?>
    <p><?php echo htmlspecialchars($message); ?></p>
    <a href="orders.php">Bekijk mijn bestellingen</a>
<?php endif; ?>

<a href="index.php">Terug naar Home</a>
</body>
</html>