<?php

    //PDO connection
    $conn = new PDO('mysql:dbname=webshop;host=localhost', "root", "");
    
    //select * from products and fetch as array
    $statement = $conn->prepare('SELECT * FROM products');
    $statement->execute();
    $products = $statement->fetchAll(PDO::FETCH_ASSOC);
    
    //Dit hier onder is hetzelfde al hier boven
    //$conn = new mysqli('localhost', 'root', '', 'webshop');
    /*checking for errors
    if($conn->connect_error){
        echo "ERROR";
    }else{
        echo "Connected";
    }

    $sql = 'SELECT * FROM products';
    $result = $conn->query($sql);
    $products = $result->fetch_all(MYSQLI_ASSOC);
    var_dump($products);*/

?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Webshop</title>
</head>
<body>
    <h1>Welcome!</h1>
    <?php foreach($products as $product): ?>
    <article>
        <h2><?php echo $product['title']; ?> : <?php echo $product['price']; ?></h2>
    </article>
    <?php endforeach; ?>
</body>
</html>