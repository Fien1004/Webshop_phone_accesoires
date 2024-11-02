<?php
include_once(__DIR__ . "/classes/Db.php");
session_start();
if ($_SESSION["loggedin"] !== true) {
    header("Location: login.php");
    exit;
}

// Bepaal of de ingelogde gebruiker de admin is
$isAdmin = ($_SESSION['email'] === 'fien@shop.com');

try {
    $conn = Db::getConnection();
    
    // Haal de categorieën op
    $categoryStatement = $conn->prepare('SELECT * FROM categories');
    $categoryStatement->execute();
    $categories = $categoryStatement->fetchAll(PDO::FETCH_ASSOC);
    
    // Haal de geselecteerde categorie op
    $categoryId = $_GET['category'] ?? null;

    // Query om producten op te halen
    $sql = 'SELECT * FROM products';
    if ($categoryId) {
        $sql .= ' WHERE category_id = :categoryId';
    }
    $statement = $conn->prepare($sql);
    if ($categoryId) {
        $statement->bindValue(':categoryId', $categoryId);
    }
    $statement->execute();
    $products = $statement->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo 'Fout bij het verbinden met de database: ' . $e->getMessage();
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

<h1>Welcome <?php echo htmlspecialchars($_SESSION['firstname']); ?>!</h1>

<div class="product-grid">
    <?php foreach($products as $product): ?>
        <a class="product" href="product.php?id=<?php echo $product['id']; ?>">
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
