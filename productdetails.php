<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once(__DIR__ . '/vendor/autoload.php');

use Fienwouters\Onlinestore\Product;
session_start();

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("Location: login.php");
    exit();
}

// Bepaal of de ingelogde gebruiker de admin is
$isAdmin = (isset($_SESSION['email']) && $_SESSION['email'] === 'fien@shop.com');

if (isset($_GET['id'])) {
    $product_id = $_GET['id'];
    
    try {
        $product = new Product();
        $product->setId($product_id);
        $productData = $product->fetch();

        // Controleer of het product bestaat
        if (!$productData) {
            echo "Product niet gevonden!";
            exit;
        }
    } catch (Exception $e) {
        echo "Fout bij het ophalen van het product: " . $e->getMessage();
        exit;
    }
} else {
    echo "Product niet gespecificeerd!";
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($productData['product_name']); ?></title>
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

    <!-- Product -->
    <div class="product-details">
        <img src="<?php echo htmlspecialchars($productData['img']); ?>" alt="<?php echo htmlspecialchars($productData['product_name']); ?>" class="product-details__img">
        <div class="product-details__info">
            <h1><?php echo htmlspecialchars($productData['product_name']); ?></h1>
            <p class="product-details__price">Prijs: €<?php echo htmlspecialchars($productData['unit_price']); ?></p>
            <h3>Overzicht</h3>
            <p class="product-details__description"><?php echo html_entity_decode($productData['discription']); ?></p>
        </div>
    </div>
</body>
</html>
