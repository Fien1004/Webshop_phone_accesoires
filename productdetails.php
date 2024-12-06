<?php
require_once (__DIR__ . '/bootstrap.php');

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

use Fienwouters\Onlinestore\Review;
use Fienwouters\Onlinestore\Product;
use Fienwouters\Onlinestore\Cart;

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("Location: login.php");
    exit();
}

// Controleer of de gebruiker een admin is
$isAdmin = isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1;

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
    exit();
}

// Haal producttypes op
$productTypes = $product->getProductTypes();

$allReviews = Review::getAll($product_id);

// Verwerk het formulier voor het toevoegen aan het winkelmandje
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_to_cart'])) {
    $quantity = $_POST['quantity'];
    $product_type = $_POST['product_type']; // Haal het producttype op uit het formulier
    $user_id = $_SESSION['user']['id'];
    $cart = new Cart($user_id);

    try {
        $cart->addProduct($product_id, $quantity, $product_type); // Geef het producttype door
        $message = "Product toegevoegd aan winkelmandje!";
    } catch (Exception $e) {
        $message = "Fout bij het toevoegen aan winkelmandje: " . $e->getMessage();
    }
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
        
        <form action="index.php" method="get">
            <input type="text" name="search" placeholder="Zoek producten..." value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
            <button type="submit">Zoeken</button>
        </form>

        <!-- Admin link voor admin-gebruiker -->
        <?php if ($isAdmin): ?>
            <a href="admin.php" class="navbar__admin">Admin</a>
        <?php endif; ?>

        <a href="cart_view.php">Winkelmandje</a>

        <a href="profile.php" class="navbar__logout">Mijn profiel</a>
    </nav>

    <!-- Productdetails -->
    <div class="product-details">
        <div class="product-details_container">
            <img src="<?php echo htmlspecialchars($productData['img']); ?>" alt="<?php echo htmlspecialchars($productData['product_name']); ?>" class="product-details__img">
            <div class="product-details__info">
                <h1><?php echo htmlspecialchars($productData['product_name']); ?></h1>
                <p class="product-details__price">Prijs: €<?php echo htmlspecialchars($productData['unit_price']); ?></p>
                <!-- Toevoegen aan winkelmandje -->
                <?php if (isset($message)): ?>
                    <p><?php echo htmlspecialchars($message); ?></p>
                <?php endif; ?>
                <form class="add_cart" action="" method="post">
                    <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
                    <label for="quantity">Aantal:</label>
                    <input type="number" id="quantity" name="quantity" value="1" min="1">
                    
                    <label for="product_type">Type:</label>
                    <select id="product_type" name="product_type">
                        <?php foreach ($productTypes as $type): ?>
                            <option value="<?php echo htmlspecialchars($type['type_name']); ?>" data-img="<?php echo htmlspecialchars($type['img']); ?>">
                                <?php echo htmlspecialchars($type['type_name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    
                    <button type="submit" name="add_to_cart">Toevoegen aan winkelmandje</button>
                </form>
                <h3>Overzicht</h3>
                <p class="product-details__description"><?php echo html_entity_decode($productData['discription']); ?></p>
            
            </div>
        </div>
        <!-- Review sectie -->
        <div id="review-form">
            <h3>Laat een review achter:</h3>
            <input id="reviewText" type="text" placeholder="Schrijf hier je review...">
            <select name="rating" id="rating" required>
                <option value="">Kies een rating</option>
                <option value="⭐☆☆☆☆">⭐☆☆☆☆</option>
                <option value="⭐⭐☆☆☆">⭐⭐☆☆☆</option>
                <option value="⭐⭐⭐☆☆">⭐⭐⭐☆☆</option>
                <option value="⭐⭐⭐⭐☆">⭐⭐⭐⭐☆</option>
                <option value="⭐⭐⭐⭐⭐">⭐⭐⭐⭐⭐</option>
            </select>
            <a href="#" id="addReview" data-product_id="<?php echo htmlspecialchars($product_id); ?>">Verstuur review</a>
        </div>
    
        <ul class="reviewslist">
            <!-- Reviews worden hier ingeladen -->
            <?php foreach($allReviews as $review): ?>
                <li>
                    <p><strong><?php echo htmlspecialchars($review['user_firstname']); ?></strong></p>
                    <p>Rating: <?php echo htmlspecialchars($review['rating']); ?></p>
                    <p><?php echo htmlspecialchars($review['text']); ?></p>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>


    <script>
        document.querySelector("#product_type").addEventListener("change", function() {
            let selectedOption = this.options[this.selectedIndex];
            let newImgSrc = selectedOption.getAttribute("data-img");
            document.querySelector(".product-details__img").src = newImgSrc;
        });

        document.querySelector("#addReview").addEventListener("click", function(e) {
            e.preventDefault();
            
            let product_id = this.dataset.product_id;
            let text = document.querySelector("#reviewText").value;
            let rating = document.querySelector("#rating").value;

            if (text === "" || rating === "") {
                alert("Zorg ervoor dat je zowel de reviewtekst als de rating invult!");
                return;
            }

            let formData = new FormData();
            formData.append("user_firstname", "<?php echo $_SESSION['firstname']; ?>");
            formData.append("text", text);
            formData.append("rating", rating);
            formData.append("product_id", product_id);

            fetch("ajax/addReview.php", {
                method: "POST",
                body: formData,
            })
            .then(response => response.json())
            .then(result => {
                let newReview = document.createElement("li");
                newReview.innerHTML = 
                `<strong>${result.user_firstname}</strong>
                <p>${result.rating}</p>
                <p>${result.body}</p>`;

                document.querySelector(".reviewslist").appendChild(newReview);
                document.querySelector("#reviewText").value = "";
                document.querySelector("#rating").value = "";
            })
            .catch(error => console.error("Error:", error));
        });
    </script>
</body>
</html>