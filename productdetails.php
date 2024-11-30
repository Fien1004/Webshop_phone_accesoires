<?php
require_once __DIR__ . '/bootstrap.php';
require_once(__DIR__ . '/vendor/autoload.php');

use Fienwouters\Onlinestore\Review;


use Fienwouters\Onlinestore\Product;
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
    exit;
}

$allReviews = Review::getAll($product_id);
//var_dump($allReviews);

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
        
        <a href="profile.php" class="navbar__logout">Mijn profiel</a>
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

    <!-- Review formulier -->
    <h3>Laat een review achter:</h3>
    <div id="review-form">
        <input id="reviewText" type="text" placeholder="Schrijf hier je review...">
        <select name="rating" id="rating" required>
            <option value="">Kies een rating</option>
            <option value="⭐☆☆☆☆">⭐☆☆☆☆</option>
            <option value="⭐⭐☆☆☆">⭐⭐☆☆☆</option>
            <option value="⭐⭐⭐☆☆">⭐⭐⭐☆☆</option>
            <option value="⭐⭐⭐⭐☆">⭐⭐⭐⭐☆</option>
            <option value="⭐️⭐️⭐️⭐️⭐️">⭐️⭐️⭐️⭐️⭐️</option>
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

    <script>
        document.querySelector("#addReview").addEventListener("click", function(e){

            e.preventDefault();
            
            //product_id?
            //review tekst?
            //rating?

            let product_id = this.dataset.product_id; // Haal het product_id uit de dataset
            let text = document.querySelector("#reviewText").value;
            let rating = document.querySelector("#rating").value;

            if (text === "" || rating === "") {
                alert("Zorg ervoor dat je zowel de reviewtekst als de rating invult!");
                return;
            }

            console.log("Product ID:", product_id);
            console.log("Review tekst:", text);
            console.log("Rating:", rating);
            console.log("User ID:", <?php echo $_SESSION['user']['id']; ?>);
            console.log("User firstname:", "<?php echo $_SESSION['firstname']; ?>");

            //post naar databank (AJAX)
            let formData = new FormData();
            formData.append("user_firstname", "<?php echo $_SESSION['firstname']; ?>");
            formData.append("text", text);
            formData.append("rating", rating);
            formData.append("product_id", product_id);


            fetch("ajax/addReview.php", {
                method: "POST",
                body: formData,
            })
            .then(response => response.json()) // Geef de JSON-data door
            .then(result => {
                console.log("Serverresponse:", result); // Controleer de inhoud
                let newReview = document.createElement("li");
                newReview.innerHTML = 
                `   <strong>${result.user_firstname}</strong>: 
                    <p> ${result.rating} </p>
                    <p> ${result.body}</p>`; 

                console.log(result.body);

                document
                        .querySelector(".reviewslist")
                        .appendChild(newReview);

                // Reset de form-velden
                document.querySelector("#reviewText").value = "";
                document.querySelector("#rating").value = "";
            })
            .catch(error => {
                console.error("Error:", error);
            });



        });

    </script>

</body>
</html>
