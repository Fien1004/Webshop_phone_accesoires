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
    public function addProduct($product_name, $discription, $unit_price, $stock, $category_id, $img) {
        try {
            $conn = Db::getConnection();
            $statement = $conn->prepare("INSERT INTO products (product_name, discription, unit_price, stock, category_id, img) VALUES (:product_name, :discription, :unit_price, :stock, :category_id, :img)");
            $statement->bindValue(':product_name', $product_name);
            $statement->bindValue(':discription', $discription);
            $statement->bindValue(':unit_price', $unit_price);
            $statement->bindValue(':stock', $stock);
            $statement->bindValue(':category_id', $category_id);
            $statement->bindValue(':img', $img);
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

    public function updateProduct($product_id, $product_name, $discription, $unit_price, $img, $stock, $category_id) {
        try {
            $conn = Db::getConnection();
            $statement = $conn->prepare("UPDATE products SET product_name = :product_name, discription = :discription, unit_price = :unit_price, img = :img, stock = :stock, category_id = :category_id WHERE id = :product_id");
            $statement->bindValue(':product_id', $product_id);
            $statement->bindValue(':product_name', $product_name);
            $statement->bindValue(':discription', $discription);
            $statement->bindValue(':unit_price', $unit_price);
            $statement->bindValue(':img', $img);
            $statement->bindValue(':stock', $stock);
            $statement->bindValue(':category_id', $category_id);
            $statement->execute();
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public function deleteProduct($product_id) {
        try {
            $conn = Db::getConnection();
            $statement = $conn->prepare("DELETE FROM products WHERE id = :product_id");
            $statement->bindValue(':product_id', $product_id);
            $statement->execute();
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
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
    $img = $_POST['img'];

    // Roep de addProduct functie aan
    if ($admin->addProduct($product_name, $discription, $unit_price, $stock, $category_id, $img)) {
        echo "Product is toegevoegd!";
    }
}

// Controleer of het zoekformulier is verzonden
if (isset($_POST['search'])) {
    $search = trim($_POST['search']);
    $products = $admin->searchProduct($search);
}

// Bijwerken van een product
if (isset($_POST['update'])) {
    $product_id = $_POST['product_id'];
    $product_name = trim($_POST['update_product_name']);
    $discription = trim($_POST['update_discription']);
    $unit_price = $_POST['update_unit_price'];
    $stock = $_POST['update_stock'];
    $category_id = $_POST['update_category_id'];
    $img = $_POST['update_img'];

    $admin->updateProduct($product_id, $product_name, $discription, $unit_price, $img, $stock, $category_id);
    echo "Product is bijgewerkt!";
}

// Verwijderen van een product
if (isset($_POST['delete'])) {
    $product_id = $_POST['product_id'];
    $admin->deleteProduct($product_id);
    echo "Product is verwijderd!";
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
                    <img src="<?php echo htmlspecialchars($product['img']); ?>" alt="<?php echo htmlspecialchars($product['product_name']); ?>" style="max-width: 200px; margin-bottom: 10px;">
                    <h4 class="product-title"><?php echo htmlspecialchars($product['product_name']); ?></h4>
                    <p class="product-description"><strong>Omschrijving:</strong> <?php echo htmlspecialchars($product['discription']); ?></p>
                    <p class="product-price"><strong>Prijs:</strong> €<?php echo htmlspecialchars($product['unit_price']); ?></p>
                    <p class="product-stock"><strong>Voorraad:</strong> <?php echo htmlspecialchars($product['stock']); ?></p>

                    <!-- Formulier om het product bij te werken -->
                    <form method="post">
                        <h4>Bijwerken van product</h4>
                        <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                        <input type="text" name="update_product_name" value="<?php echo htmlspecialchars($product['product_name']); ?>" required>
                        <input type="text" name="update_discription" value="<?php echo htmlspecialchars($product['discription']); ?>" required>
                        <input type="number" step="0.01" name="update_unit_price" value="<?php echo htmlspecialchars($product['unit_price']); ?>" required>
                        <input type="text" name="update_img" value="<?php echo htmlspecialchars($product['img']); ?>" required>
                        <input type="number" name="update_stock" value="<?php echo htmlspecialchars($product['stock']); ?>" required>
                        <select name="update_category_id" required>
                            <option value="1" <?php echo $product['category_id'] == 1 ? 'selected' : ''; ?>>Telefoonhoesjes</option>
                            <option value="2" <?php echo $product['category_id'] == 2 ? 'selected' : ''; ?>>Telefoonaccessoires</option>
                            <option value="3" <?php echo $product['category_id'] == 3 ? 'selected' : ''; ?>>Tassen</option>
                        </select>
                        <button type="submit" name="update">Bijwerken</button>
                    </form>

                    <!-- Formulier om het product te verwijderen -->
                    <form method="post" onsubmit="return confirm('Weet je zeker dat je dit product wilt verwijderen?');">
                        <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                        <button type="submit" name="delete">Verwijder</button>
                    </form>
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
        <input type="text" name="img" placeholder="Afbeelding" required>
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
