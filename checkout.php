<?php
session_start();
// Set the timeout period (in seconds). Example: 10 seconds
$timeout_duration = 10; // 10 sec

// Check if the user is logged in and if the session has expired
if (isset($_SESSION['last_activity'])) {
    $session_lifetime = time() - $_SESSION['last_activity'];

    // If the session has expired, destroy the session
    if ($session_lifetime > $timeout_duration) {
        session_unset();         // Remove all session variables
        session_destroy();       // Destroy the session
        header("Location: login.php");  // Redirect to login page
        exit();
    }
}

// Update the last activity time for the current session
$_SESSION['last_activity'] = time();

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include('db.php');

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $userId = $_SESSION['user_id'];
    $address = $_POST['address'];
    $contact = $_POST['contact']; // Get the contact number from the form
    $cart = $_POST['cart']; // JSON string of cart items

    // Insert order data into the database
    $query = "INSERT INTO orders (user_id, address, contact, cart) 
              VALUES ('$userId', '$address', '$contact', '$cart')";
    
    if (mysqli_query($conn, $query)) {
        // Clear cart in session/localStorage and redirect
        echo "<script>
                localStorage.removeItem('cart'); // Clear cart from localStorage
                window.location.href = 'menu.php'; // Redirect to menu page after successful order
              </script>";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <link rel="stylesheet" href="css/checkout-style.css">
</head>
<body>
    <h1>Checkout</h1>
    <form method="post">
        <textarea name="cart" id="cart-data" hidden></textarea>
        <label for="address">Delivery Address:</label>
        <textarea name="address" required></textarea>
        <label for="contact">Contact Number:</label>
        <input type="tel" name="contact" required placeholder="Enter your contact number" />
        <button type="submit">Place Order</button>
    </form>

    <script>
        // Pass the cart data to the server for order processing
        document.getElementById('cart-data').value = localStorage.getItem('cart') || '[]';
    </script>
</body>
</html>
