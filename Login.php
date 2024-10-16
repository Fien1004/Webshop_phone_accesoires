<?php

	function canLogin($p_email, $p_password){

		$conn = new PDO('mysql:dbname=Onlinestore;host=localhost', "root", "");
		$statement = $conn->prepare("select * from users where email = :email");
		$statement->bindValue(':email', $p_email);
		$statement->execute();

		$user = $statement->fetch(PDO::FETCH_ASSOC);
			
		if($user){
			$hash = $user['password'];
			if(password_verify($p_password, $hash)){
				return true;
			}

		}else{
			//not found
			return false;
		}
	}

	if(!empty($_POST)){
		$email = $_POST['email']; //name van input
		$password = $_POST['password']; //name van input


		if(canLogin($email, $password)){
			session_start();
			$_SESSION['loggedin'] = true;
			$_SESSION['email'] = $email;

			header('location: index.php');
			
		} else{
			//NIET OK
			$error = true;
		}
	}
	

?><!DOCTYPE html>
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