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
    $stmt = $conn->prepare("SELECT * FROM users WHERE id = :id");
    $stmt->bindValue(":id", $user_id, PDO::PARAM_INT);
    $stmt->execute();
    $userData = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$userData) {
        throw new Exception("Gebruiker niet gevonden.");
    }

    $user = new User();
    $user->setFirstname($userData['firstname'])
         ->setLastname($userData['lastname'])
         ->setEmail($userData['email'])
         ->setWallet($userData['wallet'])
         ->setId($user_id); // Gebruik de setId-methode

} catch (Exception $e) {
    die("Fout bij het ophalen van gegevens: " . $e->getMessage());
}

// Verwerk het formulier
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $current_password = $_POST['current_password'] ?? '';
    $new_password = $_POST['new_password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    try {
        if ($new_password !== $confirm_password) {
            throw new Exception("Nieuwe wachtwoorden komen niet overeen.");
        }

        // Update het wachtwoord via de User-klasse
        $user->updatePassword($current_password, $new_password);

        $success = "Wachtwoord succesvol bijgewerkt.";
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <title>Wachtwoord bijwerken</title>
</head>
<body>
    <nav class="navbar">
        <a href="index.php">Home</a>
        <a href="profile.php">Mijn profiel</a>
    </nav>

    <div class="container">
        <h1>Wachtwoord bijwerken</h1>
        <?php if (isset($error)): ?>
            <p class="error"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>
        <?php if (isset($success)): ?>
            <p class="success"><?php echo htmlspecialchars($success); ?></p>
        <?php endif; ?>
        <form action="update_password.php" method="post">
            <div>
                <label for="current_password">Huidig wachtwoord:</label>
                <input type="password" id="current_password" name="current_password" required>
            </div>
            <div>
                <label for="new_password">Nieuw wachtwoord:</label>
                <input type="password" id="new_password" name="new_password" required>
            </div>
            <div>
                <label for="confirm_password">Bevestig nieuw wachtwoord:</label>
                <input type="password" id="confirm_password" name="confirm_password" required>
            </div>
            <button type="submit">Bijwerken</button>
        </form>
    </div>
</body>
</html>