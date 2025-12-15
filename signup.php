<?php
session_start();
include('db.php');

if (isset($_POST['signup'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // Check if username already exists
    $query = "SELECT * FROM users WHERE username = '$username'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        $error_message = "Username already exists.";
    } else {
        // Hash password before storing using bcrypt
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);  // bcrypt is more secure than md5

        // Insert new user with hashed password
        $query = "INSERT INTO users (username, password) VALUES ('$username', '$hashed_password')";
        if (mysqli_query($conn, $query)) {
            $success_message = "Account created successfully. You can now <a href='login.php'>Login</a>.";
        } else {
            $error_message = "Error creating account. Please try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link rel="stylesheet" href="css/login-style.css">
</head>
<body>
    <h2>Sign Up</h2>

    <!-- Display error or success messages -->
    <?php if (isset($error_message)): ?>
        <p style="color: red;"><?php echo $error_message; ?></p>
    <?php endif; ?>

    <?php if (isset($success_message)): ?>
        <p style="color: green;"><?php echo $success_message; ?></p>
    <?php endif; ?>

    <!-- Signup Form -->
    <form method="POST" action="signup.php">
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit" name="signup">Sign Up</button>
    </form>

    <p>Already have an account? <a href="login.php">Login</a></p>
</body>
</html>
