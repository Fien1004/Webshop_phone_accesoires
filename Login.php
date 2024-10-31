<?php
include_once(__DIR__ . "/classes/Db.php");
session_start();

function canLogin($p_email, $p_password) {
    try {
        // Gebruik de Db-class om de verbinding te krijgen
        $conn = Db::getConnection();

        // Bereid de SQL-statement voor
        $statement = $conn->prepare("SELECT firstname, password FROM users WHERE email = :email");
        $statement->bindValue(':email', $p_email);
        $statement->execute();

        $user = $statement->fetch(PDO::FETCH_ASSOC);

        // Controleer of de gebruiker bestaat en of het wachtwoord klopt
        if ($user && password_verify($p_password, $user['password'])) {
            return $user; // Retourneer het hele $user array
        } else {
            return false;
        }
    } catch (Exception $e) {
        // Eventuele verbindingsfouten afvangen
        echo "Error: " . $e->getMessage();
        return false;
    }
}

if (!empty($_POST)) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $user = canLogin($email, $password); // Roep canLogin aan en sla het resultaat op in $user

    if ($user) {
        $_SESSION['loggedin'] = true;
        $_SESSION['email'] = $email;
        $_SESSION['firstname'] = $user['firstname']; // Voeg voornaam toe aan sessie
		
		    // Controleer of de gebruiker admin is
			if ($email === 'fien@shop.com') {
				$_SESSION['is_admin'] = true;
			} else {
				$_SESSION['is_admin'] = false;
			}

        header('location: index.php');
        exit;
    } else {
        // NIET OK
        $error = true;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>IMDFlix</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
	<div class="storelogin">
		<div class="form form--login">
			<form action="" method="post">
				<h2 form__title>Login</h2>

				<?php if(isset($error)): ?>
				<div class="form__error">
					<p>
						Sorry, we can't log you in with that email address and password. Can you try again?
					</p>
				</div>
				<?php endif; ?>

				<div class="form__field">
					<label for="Email">Email</label>
					<input type="text" name="email">
				</div>
				<div class="form__field">
					<label for="Password">Password</label>
					<input type="password" name="password">
				</div>

				<div class="form__field">
					<input type="submit" value="Sign in" class="btn btn--primary">	
					<input type="checkbox" id="rememberMe"><label for="rememberMe" class="label__inline">Remember me</label>
				</div>
                <div class="form__field">
				<p>Don't have an account? <a href="signup.php" class="btn btn--secondary">Sign up</a></p>
			    </div>
			</form>
		</div>
	</div>
</body>
</html>