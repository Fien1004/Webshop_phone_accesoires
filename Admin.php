<?php
session_start();

// Controleer of de gebruiker is ingelogd en adminrechten heeft
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['is_admin'] !== true) {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Panel</title>
</head>
<body>
    <h1>Admin Panel</h1>
    <p>Welkom, <?php echo htmlspecialchars($_SESSION['firstname']); ?>! U heeft toegang tot de beheerfuncties.</p>
    
    <!-- Formulieren of links voor productbeheer -->
</body>
</html>
