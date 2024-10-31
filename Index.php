<?php
include_once(__DIR__."/classes/Db.php");
session_start();
if($_SESSION["loggedin"] !== true){
    header("Location: login.php");
    exit;
}


try {
    $conn = Db::getConnection();
    //echo "Verbinding succesvol!<br>";
    $statement = $conn->prepare('SELECT * FROM products');
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
    <title>Webshop</title>
</head>
<body>
<nav class="navbar">
    <a href="index.php" class="logo">Webshop</a>
    <a href="index.php">Home</a>
    
    <form action="" method="get">
      <input type="text" name="search">
    </form>
    
    <a href="logout.php" class="navbar__logout">Hi <?php echo htmlspecialchars($_SESSION['firstname']); ?>, logout?</a>

</nav>
    <h1>Welcome!</h1>

    <?php foreach($products as $product): ?>
    <article>
        <h2><?php echo $product['product_name']; ?> : <?php echo $product['unit_price']; ?></h2>
    </article>
    <?php endforeach; ?>
</body>
</html>
