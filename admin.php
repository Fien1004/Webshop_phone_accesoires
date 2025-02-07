<?php
require_once __DIR__ . '/bootstrap.php';
use Fienwouters\Onlinestore\Db;
use Fienwouters\Onlinestore\Product;
use Fienwouters\Onlinestore\User;

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['is_admin'] !== 1) {
    // Geen toegang als geen admin
    header("Location: login.php");
    exit();
}

$products = [];

// Product toevoegen
if (isset($_POST['add'])) {
    $product = new Product();
    $product->setName(trim($_POST['product_name']))
            ->setDescription(trim($_POST['discription']))
            ->setPrice($_POST['unit_price'])
            ->setStock($_POST['stock'])
            ->setCategoryId($_POST['category_id'])
            ->setImage($_POST['img']);

    if ($product->save()) {
        echo "Product is toegevoegd!";
    }
}

// Product zoeken
if (isset($_POST['search'])) {
    $search = trim($_POST['search']);
    $products = Product::search($search);
}

// Product bijwerken
if (isset($_POST['update'])) {
    $product = new Product();
    $product->setId($_POST['product_id'])
            ->setName(trim($_POST['update_product_name']))
            ->setDescription(trim($_POST['update_discription']))
            ->setPrice($_POST['update_unit_price'])
            ->setImage($_POST['update_img'])
            ->setStock($_POST['update_stock'])
            ->setCategoryId($_POST['update_category_id']);
    
    if ($product->save()) {
        echo "Product is bijgewerkt!";
    }
}

// Product verwijderen
if (isset($_POST['delete'])) {
    if (Product::delete($_POST['product_id'])) {
        echo "Product is verwijderd!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Panel</title>
    <link rel="stylesheet" href="css/admin.css">
</head>
<body>
    <nav class="navbar">
        <a href="index.php">Home</a>
        <a href="logout.php" class="navbar__logout">Logout?</a>
    </nav>

    <main class="admin-panel">
        <h1>Admin Panel</h1>
        <p>Welkom, <?php echo htmlspecialchars($_SESSION['firstname']); ?>! U heeft toegang tot de beheerfuncties.</p>
        
        <!-- Producten Beheren Sectie -->
        <section>
            <h2>Producten beheren</h2>

            <!-- Product Zoeken -->
            <form method="post" class="admin-form">
                <h3>Zoek een product</h3>
                <input type="text" name="search" placeholder="Zoek op naam of omschrijving" required>
                <button type="submit">Zoek</button>
            </form>

            <!-- Gezochte Producten -->
            <h3>Resultaten</h3>
            <div class="product-list">
                <?php if (!empty($products)): ?>
                    <?php foreach ($products as $product): ?>
                        <div class="product-container">
                            <img src="<?php echo htmlspecialchars($product['img']); ?>" alt="<?php echo htmlspecialchars($product['product_name']); ?>">
                            <h4><?php echo htmlspecialchars($product['product_name']); ?></h4>
                            <p><strong>Omschrijving:</strong> <?php echo htmlspecialchars($product['discription']); ?></p>
                            <p><strong>Prijs:</strong> €<?php echo htmlspecialchars($product['unit_price']); ?></p>
                            <p><strong>Voorraad:</strong> <?php echo htmlspecialchars($product['stock']); ?></p>

                            <!-- Bijwerken -->
                            <form method="post" class="admin-form">
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

                            <!-- Verwijderen -->
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
        </section>

        <!-- Product Toevoegen -->
        <section>
            <form method="post" class="admin-form">
                <h3>Nieuw product toevoegen</h3>
                <input type="text" name="product_name" placeholder="Product naam" required>
                <input type="text" name="discription" placeholder="Omschrijving" required>
                <input type="number" step="0.01" name="unit_price" placeholder="Prijs" required>
                <input type="text" name="img" placeholder="Afbeelding URL" required>
                <input type="number" name="stock" placeholder="Voorraad" required>
                <select name="category_id" required>
                    <option value="">Selecteer een categorie</option>
                    <option value="1">Telefoonhoesjes</option>
                    <option value="2">Telefoonaccessoires</option>
                    <option value="3">Tassen</option>
                    <option value="4">Airpods accessoires</option>
                </select>
                <button type="submit" name="add">Toevoegen</button>
            </form>
        </section>
    </main>
</body>
</html>
