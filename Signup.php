<?php

$users = [
    "fien@shop.com" => "12345isnotsecure"
];

// Function to sign up user
function canSignup($email, $password, $users){
    if(!isset($users[$email])) {
        // If email not registered, sign up is possible
        return true;
    } else {
        // If email is registered
        return false;
    }
}

if(!empty($_POST)){
    $email = $_POST['email']; // name of input
    $password = $_POST['password']; // name of input

    if(canSignup($email, $password, $users)){
        $users[$email] = $password;

        session_start();
        $_SESSION['loggedin'] = true;
        $_SESSION['email'] = $email;

        header('location: login.php');
    } else {
        $error = true;
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
