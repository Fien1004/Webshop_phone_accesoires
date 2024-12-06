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

// Haal de gebruiker op uit de database
try {
    $conn = Db::getConnection();
    $stmt = $conn->prepare("SELECT firstname, lastname, email, address, postal_code, city, country, wallet FROM users WHERE id = :id");
    $stmt->bindValue(":id", $user_id, PDO::PARAM_INT);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        throw new Exception("Gebruiker niet gevonden.");
    }
} catch (Exception $e) {
    die("Fout bij het ophalen van gegevens: " . $e->getMessage());
}

// Verwerk het formulier
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstname = $_POST['firstname'] ?? '';
    $lastname = $_POST['lastname'] ?? '';
    $email = $_POST['email'] ?? '';
    $address = $_POST['address'] ?? '';
    $postal_code = $_POST['postal_code'] ?? '';
    $city = $_POST['city'] ?? '';
    $country = $_POST['country'] ?? '';

    // Validatie
    if (empty($firstname) || empty($lastname) || empty($email)) {
        $error = "Alle velden zijn verplicht.";
    } else {
        try {
            $stmt = $conn->prepare("UPDATE users SET firstname = :firstname, lastname = :lastname, email = :email, address = :address, postal_code = :postal_code, city = :city, country = :country WHERE id = :id");
            $stmt->bindValue(":firstname", $firstname);
            $stmt->bindValue(":lastname", $lastname);
            $stmt->bindValue(":email", $email);
            $stmt->bindValue(":address", $address);
            $stmt->bindValue(":postal_code", $postal_code);
            $stmt->bindValue(":city", $city);
            $stmt->bindValue(":country", $country);
            $stmt->bindValue(":id", $user_id, PDO::PARAM_INT);
            $stmt->execute();

            $success = "Profiel succesvol bijgewerkt.";
        } catch (Exception $e) {
            $error = "Fout bij het bijwerken van profiel: " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/profile.css">
    <title>Profiel bijwerken</title>
</head>
<body>
    <nav class="navbar">
        <a href="index.php">Home</a>
        <a href="profile.php">Mijn profiel</a>
    </nav>

    <div class="container">
        <h1>Profiel bijwerken</h1>
        <?php if (isset($error)): ?>
            <p class="error"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>
        <?php if (isset($success)): ?>
            <p class="success"><?php echo htmlspecialchars($success); ?></p>
        <?php endif; ?>
        <form action="update_profile.php" method="post">
            <div>
                <label for="firstname">Voornaam:</label>
                <input type="text" id="firstname" name="firstname" value="<?php echo htmlspecialchars($user['firstname']); ?>">
            </div>
            <div>
                <label for="lastname">Achternaam:</label>
                <input type="text" id="lastname" name="lastname" value="<?php echo htmlspecialchars($user['lastname']); ?>">
            </div>
            <div>
                <label for="email">E-mailadres:</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>">
            </div>
            <div>
                <label for="address">Adres:</label>
                <input type="text" id="address" name="address" value="<?php echo htmlspecialchars($user['address']); ?>">
            </div>
            <div>
                <label for="postal_code">Postcode:</label>
                <input type="text" id="postal_code" name="postal_code" value="<?php echo htmlspecialchars($user['postal_code']); ?>">
            </div>
            <div>
                <label for="city">Stad:</label>
                <input type="text" id="city" name="city" value="<?php echo htmlspecialchars($user['city']); ?>">
            </div>
            <div>
                <label for="country">Land:</label>
                <input type="text" id="country" name="country" value="<?php echo htmlspecialchars($user['country']); ?>">
            </div>
            <button type="submit">Bijwerken</button>
        </form>
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