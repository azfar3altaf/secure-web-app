<?php
session_start();
// Set the timeout period (in seconds). Example: 120 seconds
$timeout_duration = 120; // 120 sec

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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart</title>
    <link rel="stylesheet" href="css/cart-style.css">
    <script src="script.js" defer></script>
</head>
<body>
    <h1>Your Cart</h1>
    <div id="cart-items"></div>
    <a href="checkout.php" class="checkout-btn">Proceed to Checkout</a>

    <script>
        // Function to render the cart items dynamically from localStorage
        function renderCart() {
            const cart = JSON.parse(localStorage.getItem('cart')) || [];
            const cartItems = document.getElementById('cart-items');

            if (cart.length === 0) {
                cartItems.innerHTML = "<p>Your cart is empty.</p>";
            } else {
                cartItems.innerHTML = cart.map(item => 
                    `<div class="cart-item">
                        <h3>${item.name}</h3>
                        <p>Description: ${item.description}</p>
                        <p>Price: $${item.price}</p>
                        <p>Quantity: ${item.quantity}</p>
                        <button class="delete-btn" data-id="${item.id}">Delete</button>
                    </div>`).join('');
            }

            // Add delete functionality to each delete button
            document.querySelectorAll('.delete-btn').forEach(button => {
                button.addEventListener('click', deleteItem);
            });
        }

        // Delete item from the cart
        function deleteItem(event) {
            const id = event.target.getAttribute('data-id');
            let cart = JSON.parse(localStorage.getItem('cart')) || [];
            cart = cart.filter(item => item.id !== id); // Remove item by id
            localStorage.setItem('cart', JSON.stringify(cart)); // Save updated cart
            renderCart(); // Re-render cart to reflect the change
        }

        renderCart(); // Call the renderCart function when the page loads
    </script>
</body>
</html>
