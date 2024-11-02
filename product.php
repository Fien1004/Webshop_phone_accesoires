<?php
    include_once(__DIR__ . "/classes/Db.php");
    session_start();
    if ($_SESSION["loggedin"] !== true) {
        header("Location: login.php");
        exit;
    }

    // Bepaal of de ingelogde gebruiker de admin is
    $isAdmin = ($_SESSION['email'] === 'fien@shop.com');

    if (isset($_GET['id'])) {
        $product_id = $_GET['id'];
        
        try {
            $conn = Db::getConnection();
            $statement = $conn->prepare("SELECT * FROM products WHERE id = :id");
            $statement->bindValue(':id', $product_id, PDO::PARAM_INT);
            $statement->execute();
            $product = $statement->fetch(PDO::FETCH_ASSOC);
    
            // Controleer of het product bestaat
            if (!$product) {
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
    <title>Product page</title>
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
        <img src="<?php echo htmlspecialchars($product['img']); ?>" alt="<?php echo htmlspecialchars($product['product_name']); ?>" class="product-details__img">
        <div class="product-details__info">
            <h1><?php echo htmlspecialchars($product['product_name']); ?></h1>
            <p class="product-details__price">Prijs: €<?php echo htmlspecialchars($product['unit_price']); ?></p>
            <h3>Overzicht</h3>
            <p class="product-details__description"><?php echo htmlspecialchars($product['discription']); ?></p>
        </div>
    </div>
    

</body>
</html>