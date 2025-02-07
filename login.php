<?php
include_once (__DIR__ . '/bootstrap.php');

use Fienwouters\Onlinestore\User;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $user = User::canLogin($email, $password);

    if ($user) {
        $_SESSION['user'] = $user;
		$_SESSION['user_id'] = $user['id'];
		$_SESSION['is_admin'] = $user['is_admin']; // 1 = true, 0 = false
		$_SESSION['firstname'] = $user['firstname'];
		$_SESSION["loggedin"] = true;
        header("Location: index.php");
        exit();
    } else {
        $error = "Ongeldige logingegevens.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Login</title>
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
				</div>
                <div class="form__field">
				<p>Don't have an account? <a href="signup.php" class="btn btn--secondary">Sign up</a></p>
			    </div>
			</form>
		</div>
	</div>
</body>
</html>