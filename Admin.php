<?php
session_start();
include_once(__DIR__ . "/classes/Db.php");

// Controleer of de gebruiker is ingelogd en adminrechten heeft
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['is_admin'] !== true) {
    header("Location: login.php");
    exit;
}

class Admin {
    // functie om product toe te voegen aan de database
    public function addProduct($product_name, $discription, $unit_price, $stock, $category_id) {
        try {
            $conn = Db::getConnection();
            $statement = $conn->prepare("INSERT INTO products (product_name, discription, unit_price, stock, category_id) VALUES (:product_name, :discription, :unit_price, :stock, :category_id)");
            $statement->bindValue(':product_name', $product_name);
            $statement->bindValue(':discription', $discription);
            $statement->bindValue(':unit_price', $unit_price);
            $statement->bindValue(':stock', $stock);
            $statement->bindValue(':category_id', $category_id);
            $statement->execute();
            return true; // Geeft aan dat het product succesvol is toegevoegd
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
            return false; // Geeft aan dat er een fout is opgetreden
        }
    }

    // functie om producten te zoeken in de database
    public function searchProduct($search) {
        try {
            $conn = Db::getConnection();
            $statement = $conn->prepare("SELECT * FROM products WHERE product_name LIKE :search OR discription LIKE :search");
            $statement->bindValue(':search', '%' . $search . '%');
            $statement->execute();
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
            return [];
        }
    }
}

// Maak een nieuw Admin object aan
$admin = new Admin();
$products = []; // Zorg ervoor dat de $products variabele wordt gedefinieerd

// Controleer of het product toevoegen formulier is verzonden
if (isset($_POST['add'])) {
    $product_name = trim($_POST['product_name']);
    $discription = trim($_POST['discription']);
    $unit_price = $_POST['unit_price'];
    $stock = $_POST['stock'];
    $category_id = $_POST['category_id'];

    // Roep de addProduct functie aan
    if ($admin->addProduct($product_name, $discription, $unit_price, $stock, $category_id)) {
        echo "Product is toegevoegd!";
    }
}

// Controleer of het zoekformulier is verzonden
if (isset($_POST['search'])) {
    $search = trim($_POST['search']);
    $products = $admin->searchProduct($search);
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Panel</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <nav class="navbar">
        <a href="index.php">Home</a>
        
        <a href="logout.php" class="navbar__logout">Logout?</a>
    </nav>

    <h1>Admin Panel</h1>
    <p>Welkom, <?php echo htmlspecialchars($_SESSION['firstname']); ?>! U heeft toegang tot de beheerfuncties.</p>
    
    <h2>Producten beheren</h2>

    <!-- Product zoeken -->
    <form method="post">
        <h3>Zoek een product</h3>
        <input type="text" name="search" placeholder="Zoek op naam of omschrijving" required>
        <button type="submit">Zoek</button>
    </form>

    <!-- Productenlijst -->
    <h3>Gezochte Product</h3>
    <div>
        <?php if (!empty($products)): ?>
            <?php foreach ($products as $product): ?>
                <div class="product-container">
                    <h4 class="product-title"><?php echo htmlspecialchars($product['product_name']); ?></h4>
                    <p class="product-description"><strong>Omschrijving:</strong> <?php echo htmlspecialchars($product['discription']); ?></p>
                    <p class="product-price"><strong>Prijs:</strong> €<?php echo htmlspecialchars($product['unit_price']); ?></p>
                    <p class="product-stock"><strong>Voorraad:</strong> <?php echo htmlspecialchars($product['stock']); ?></p>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Geen producten gevonden.</p>
        <?php endif; ?>
    </div>


    <!-- Product toevoegen -->
    <form method="post">
        <h3>Voeg een nieuw product toe</h3>
        <input type="text" name="product_name" placeholder="Product naam" required>
        <input type="text" name="discription" placeholder="Omschrijving" required>
        <input type="number" step="0.01" name="unit_price" placeholder="Prijs" required>
        <input type="number" name="stock" placeholder="Voorraad" required>
        <select name="category_id" required>
            <option value="">Selecteer een categorie</option>
            <option value="1">Telefoonhoesjes</option>
            <option value="2">Telefoonaccessoires</option>
            <option value="3">Tassen</option>
        </select>
        <button type="submit" name="add">Voeg toe</button>
    </form>

</body>
</html>
