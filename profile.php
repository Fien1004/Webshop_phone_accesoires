<?php
require_once __DIR__ . '/bootstrap.php';

use Fienwouters\Onlinestore\Db;
use Fienwouters\Onlinestore\User;

// Controleer of de gebruiker is ingelogd
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("Location: login.php");
    exit();
}

// Haal de ingelogde gebruiker-ID op uit de sessie
$user_id = $_SESSION['user']['id'] ?? null;

// Als er geen gebruiker-ID is, omgeleid naar login
if (!$user_id) {
    header("Location: login.php");
    exit();
}

// Controleer of de gebruiker een admin is
$isAdmin = isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1;

// Haal de gebruiker op uit de database
try {
    $conn = Db::getConnection();
    $stmt = $conn->prepare("SELECT firstname, lastname, email, address, postal_code, city, country, wallet FROM users WHERE id = :id");
    $stmt->bindValue(':id', $user_id);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        throw new Exception("Gebruiker niet gevonden.");
    }
} catch (Exception $e) {
    die("Fout bij het ophalen van gegevens: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <title>Profile</title>
</head>
<body>
    <nav class="navbar">
        <a href="index.php">Home</a>
        <a href="cart_view.php">Winkelmandje</a>

        <!-- Admin link voor admin-gebruiker -->
        <?php if ($isAdmin): ?>
            <a href="admin.php" class="navbar__admin">Admin</a>
        <?php endif; ?>
        
        <a href="logout.php" class="navbar__logout">Uitloggen</a>
    </nav>

    <div class="container">
        <h1>Mijn Profiel</h1>
        <p><strong>Naam:</strong> <?php echo htmlspecialchars($user['firstname'] . " " . $user['lastname']); ?></p>
        <p><strong>E-mailadres:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
        <p><strong>Addres:</strong> <?php echo htmlspecialchars($user['address']) ?></p>
        <p><strong>Postcode:</strong> <?php echo htmlspecialchars($user['postal_code']); ?></p>
        <p><strong>Stad:</strong> <?php echo htmlspecialchars($user['city']); ?></p>
        <p><strong>Land:</strong> <?php echo htmlspecialchars($user['country']); ?></p>
        <p><strong>Saldo:</strong> €<?php echo htmlspecialchars($user['wallet']); ?></p>
        <h2>Bestellingen</h2>
        <a href="orders.php">Bekijk mijn bestellingen</a>

        <h2>Profiel beheren</h2>
        <a href="update_profile.php">Bewerk je profiel</a>
        <a href="update_password.php">Wijzig je wachtwoord</a>

        <h2>Account verwijderen</h2>
        <form action="delete_profile.php" method="POST">
            <p>Wil je je account verwijderen? Dit kan niet ongedaan worden gemaakt.</p>
            <button type="submit" class="btn btn-danger">Account verwijderen</button>
        </form>
    </div>
    
</body>
</html>