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
    <title>Telefoonaccessoires</title>
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

<?php foreach($products as $product): ?>
    <article>
        <h2><?php echo htmlspecialchars($product['product_name']); ?> : <?php echo htmlspecialchars($product['unit_price']); ?></h2>
    </article>
<?php endforeach; ?>
</body>
</html>
