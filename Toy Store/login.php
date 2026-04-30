<?php
session_start();

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user = $_POST['username'];
    $pass = $_POST['password'];

    if ($user == "admin" && $pass == "1234") {
        $_SESSION['user'] = $user;
        header("Location: index.php");
        exit();
    } else {
        $error = "Invalid username or password";
    }
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login Page</title>
    <link rel="stylesheet" href="CSS\login.css">
</head>
<body>
<form class = "container" action="login.php" method="post">
    <?php if ($error != ""): ?>
        <p style="color:red;"><?php echo $error; ?></p>
    <?php endif; ?>
    <div class = loginform></div>
    <label><?php echo "Enter your Username" ?> </label><br><br>
    <input type="text" placeholder="Username" name="username" required><br>
    <label><?php echo "Enter your password" ?></label><br><br>
    <input type="password" placeholder="Password" name="password" required><br><br>
    <input type="submit" value="Login">
</form>
</body>
</html>