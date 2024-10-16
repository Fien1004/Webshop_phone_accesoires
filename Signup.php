<?php
    if(!empty($_POST)){
        $email = $_POST['email'];
        $password = $_POST['password'];

        $options = [
            'cost' => 12,
        ];
        $hash = password_hash($password, PASSWORD_DEFAULT, $options);

        $conn = new PDO('mysql:dbname=Onlinestore;host=localhost', "root", "");
        $statement = $conn->prepare('insert into users (email, password) values (:email, :password)');
        $statement->bindValue(':email', $email);//safe tegen sql injectie
        $statement->bindValue(':password', $hash);//safe tegen sql injectie
        $statement->execute();
	
    }

?><!DOCTYPE html>
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
                <h2 form__title>Sign Up</h2>

                <?php if(isset($error)): ?>
                <div class="form__error">
                    <p>
                        Sorry, this email is already registered. Please log in or try a different email.
                    </p>
                </div>
                <?php endif; ?>

                <div class="form__field">
                    <label for="Email">Email</label>
                    <input type="text" name="email" required>
                </div>
                <div class="form__field">
                    <label for="Password">Password</label>
                    <input type="password" name="password" required>
                </div>

                <div class="form__field">
                    <input type="submit" value="Sign Up" class="btn btn--primary">  
                </div>
            </form>
        </div>
    </div>
</body>
</html>
