<?php
require_once __DIR__ . '/bootstrap.php';
use Fienwouters\Onlinestore\User;

if (!empty($_POST)) {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $address = $_POST['address'];
    $postal_code = $_POST['postal_code'];
    $city = $_POST['city'];
    $country = $_POST['country'];


    try {
        // Controleer of het e-mailadres al bestaat
        if (User::emailExists($email)) {
            throw new Exception('Er bestaat al een account met dit e-mailadres');
        }

        // Maak een nieuw User object aan
        $user = new User();
        $user->setFirstname($firstname)
             ->setLastname($lastname)
             ->setEmail($email)
             ->setPassword($password)
                ->setAddress($address)
                ->setPostalCode($postal_code)
                ->setCity($city)
                ->setCountry($country)
             ->save(); // Sla de gebruiker op
        
        // Redirect naar de loginpagina na succesvolle registratie
        header("Location: login.php");
        exit;
    } catch (Exception $e) {
        $error = $e->getMessage(); // Haal de foutmelding op
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sign Up</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="storelogin">
        <div class="form form--login">
            <form action="" method="post">
                <h2 class="form__title">Sign Up</h2>

                <?php if (isset($error)): ?>
                <div class="form__error">
                    <p><?php echo htmlspecialchars($error); ?></p>
                </div>
                <?php endif; ?>

                <div class="form__field">
                    <label for="firstname">Voornaam</label>
                    <input type="text" name="firstname">
                </div>

                <div class="form__field">
                    <label for="lastname">Achternaam</label>
                    <input type="text" name="lastname">
                </div>

                <div class="form__field">
                    <label for="email">Email</label>
                    <input type="email" name="email">
                </div>

                <div class="form__field">
                    <label for="password">Wachtwoord</label>
                    <input type="password" name="password">
                </div>

                <div class="form__field">
                    <label for="address">Adres</label>
                    <input type="text" name="address">
                </div>

                <div class="form__field form__field--inline">
                    <div class="form__inline-item">
                        <label for="postal_code">Postcode</label>
                        <input type="text" name="postal_code">
                    </div>
                    <div class="form__inline-item">
                        <label for="city">Stad</label>
                        <input type="text" name="city">
                    </div>
                </div>

                <div class="form__field">
                    <label for="country">Land</label>
                    <select name="country" id="country">
                        <option value="">Selecteer jouw land</option>
                        <option value="BE">België</option>
                        <option value="NL">Nederland</option>
                        <option value="DE">Duitsland</option>
                        <option value="FR">Frankrijk</option>
                    </select>
                </div>

                <div class="form__field">
                    <input type="submit" value="Sign Up" class="btn btn--primary">
                </div>
                <div class="form__field">
                    <p>Do you have an account? <a href="login.php" class="btn btn--secondary">Log in</a></p>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
