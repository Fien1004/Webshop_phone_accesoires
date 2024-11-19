<?php
include_once(__DIR__ . "/classes/Db.php");
require_once __DIR__ . '/bootstrap.php';

use Fienwouters\Onlinestore\Db;

// Check of de gebruiker is ingelogd
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$user = $_SESSION['user'];
$isAdmin = isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == true;

// Haal producten op uit de database
try {
    $conn = Db::getConnection();
    $statement = $conn->prepare("SELECT * FROM products");
    $statement->execute();
    $products = $statement->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Fout bij het ophalen van producten: " . $e->getMessage();
}

// Haal categorieën op uit de database
try {
    $statement = $conn->prepare("SELECT * FROM categories");
    $statement->execute();
    $categories = $statement->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Fout bij het ophalen van categorieën: " . $e->getMessage();
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
    <?php if ($isAdmin): ?> 
        <a href="admin.php" class="navbar__admin">Admin</a> 
    <?php endif; ?>
    
    <a href="logout.php" class="navbar__logout">Logout?</a>
</nav>

<!-- Categorieën -->
<div class="categories">
    <?php foreach ($categories as $category): ?>
        <a href="index.php?category=<?php echo $category['id']; ?>">
            <?php echo htmlspecialchars($category['name']); ?>
        </a>
    <?php endforeach; ?>
</div>

<h1>Welcome <?php echo isset($_SESSION['firstname']) ? htmlspecialchars($_SESSION['firstname']) : 'Guest'; ?>!</h1>

<div class="product-grid">
    <?php foreach($products as $product): ?>
        <a class="product" href="productdetails.php?id=<?php echo $product['id']; ?>">
        <article>
                <img src="<?php echo htmlspecialchars($product['img']); ?>" alt="<?php echo htmlspecialchars($product['product_name']); ?>" style="max-width: 200px; margin-bottom: 10px;">
                <h2><?php echo htmlspecialchars($product['product_name']); ?></h2>
                <p>Prijs: €<?php echo htmlspecialchars($product['unit_price']); ?></p>
            </article>
        </a>
    <?php endforeach; ?>
</div>
</body>
</html>
