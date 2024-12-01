<?php
include_once (__DIR__ . '/bootstrap.php');

use Fienwouters\Onlinestore\Cart;
use Fienwouters\Onlinestore\Db;

// Check of de gebruiker is ingelogd
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

// Haal de user_id op
$user_id = $_SESSION['user']['id'];

// Maak een nieuwe Cart object aan voor de ingelogde gebruiker
$cart = new Cart($user_id);

// Verwerk het formulier voor het toevoegen van producten aan het winkelmandje
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'])) {
    $product_id = $_POST['product_id'];
    $quantity = isset($_POST['quantity']) ? $_POST['quantity'] : 1;

    try {
        $cart->addProduct($product_id, $quantity); // Voeg het product toe aan het winkelmandje
        $message = "Het product is toegevoegd aan je winkelmandje!";
    } catch (Exception $e) {
        $message = "Fout bij het toevoegen aan winkelmandje: " . $e->getMessage();
    }
}

// Haal categorieën op uit de database
try {
    $conn = Db::getConnection();
    $statement = $conn->prepare("SELECT * FROM categories");
    $statement->execute();
    $categories = $statement->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Fout bij het ophalen van categorieën: " . $e->getMessage();
}

// Haal producten op uit de database
$products = [];
try {
    $conn = Db::getConnection();
    
    if (isset($_GET['category'])) {
        $category_id = $_GET['category'];
        $statement = $conn->prepare("SELECT * FROM products WHERE category_id = :category_id");
        $statement->bindValue(':category_id', $category_id, PDO::PARAM_INT);
    } else {
        $statement = $conn->prepare("SELECT * FROM products");
    }
    
    $statement->execute();
    $products = $statement->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Fout bij het ophalen van producten: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Onlinestore</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<nav class="navbar">
    <a href="index.php">Home</a>
    
    <form action="" method="get">
        <input type="text" name="search" placeholder="Zoek producten...">
    </form>

    <!-- Admin link voor admin-gebruiker -->
    <?php if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1): ?>
        <a href="admin.php" class="navbar__admin">Admin</a>
    <?php endif; ?>
    
    <a href="cart_view.php">Winkelmandje</a>
    <a href="profile.php" class="navbar__logout">Mijn profiel</a>
</nav>

<!-- Categorieën -->
<div class="categories">
    <?php foreach ($categories as $category): ?>
        <a href="index.php?category=<?php echo $category['id']; ?>">
            <?php echo htmlspecialchars($category['name']); ?>
        </a>
    <?php endforeach; ?>
</div>

<h1>Welkom <?php echo isset($_SESSION['firstname']) ? htmlspecialchars($_SESSION['firstname']) : 'Gast'; ?>!</h1>

<!-- Succesbericht als product is toegevoegd aan winkelmandje -->
<?php if (isset($message)): ?>
    <p><?php echo htmlspecialchars($message); ?></p>
<?php endif; ?>

<div class="product-grid">
    <?php foreach($products as $product): ?>
        <div class="product">
        <a class="product" href="productdetails.php?id=<?php echo $product['id']; ?>">
            <article>
                <img src="<?php echo htmlspecialchars($product['img']); ?>" alt="<?php echo htmlspecialchars($product['product_name']); ?>" style="max-width: 200px; margin-bottom: 10px;">
                <h2><?php echo htmlspecialchars($product['product_name']); ?></h2>
                <p>Prijs: €<?php echo htmlspecialchars($product['unit_price']); ?></p>

                <!-- Formulier voor toevoegen aan winkelmandje -->
                <form action="index.php" method="post">
                    <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                    <label for="quantity_<?php echo $product['id']; ?>">Aantal:</label>
                    <input type="number" id="quantity_<?php echo $product['id']; ?>" name="quantity" value="1" min="1">
                    <button type="submit">Toevoegen aan winkelmandje</button>
                </form>
            </article>
        </a>
        </div>
    <?php endforeach; ?>
</div>
</body>
</html>
