<?php
// Set the timeout period (in seconds). Example: 120 seconds
$timeout_duration = 120; // 120 sec

// Start the session
session_start();

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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu</title>
    <link rel="stylesheet" href="css/menu-style.css">
    <script src="script.js" defer></script>
    <style>
        /* Make cart count more visible */
        .cart-link {
            font-size: 18px;
            font-weight: bold;
            color: #ff6347; /* Red color for visibility */
        }
        
        /* Style the Add to Cart button */
        .add-to-cart {
            background-color: #ff6347; /* Tomato color */
            color: white;
            padding: 10px 20px;
            border: none;
            cursor: pointer;
            border-radius: 5px;
            font-size: 16px;
        }
        
        .add-to-cart:hover {
            background-color: #ff4500; /* Darker red on hover */
        }

        /* Shorten the search box */
        #search {
            width: 250px;
            padding: 8px;
            margin-top: 10px;
            border: 2px solid #ccc;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>Welcome, <?php echo $_SESSION['username']; ?>!</h2>
        <form action="logout.php" method="post">
            <button type="submit" class="logout-btn">Logout</button>
        </form>
        <a href="cart.php" class="cart-link">Cart (<span id="cart-count">0</span>)</a>
    </div>

    <input type="text" id="search" placeholder="Search for food..." />

    <div class="menu-container">
        <?php
        $query = "SELECT * FROM menu"; // Query to get menu items from the database
        $result = mysqli_query($conn, $query);

        while ($row = mysqli_fetch_assoc($result)) {
            $imagePath = 'images/' . strtolower(str_replace(' ', '-', $row['name'])) . '.jpg';
            if (!file_exists($imagePath)) {
                $imagePath = 'images/default.jpg';
            }

            echo "<div class='menu-item'>";
            echo "<img src='" . $imagePath . "' alt='" . $row['name'] . "' class='menu-item-img'>";
            echo "<h3 class='item-name'>" . $row['name'] . "</h3>";
            echo "<p>" . $row['description'] . "</p>";
            echo "<p>Price: $" . $row['price'] . "</p>";
            echo "<button class='add-to-cart' data-id='" . $row['id'] . "' data-name='" . $row['name'] . "' data-description='" . $row['description'] . "' data-price='" . $row['price'] . "'>Add to Cart</button>";
            echo "</div>";
        }
        ?>
    </div>

    <div class="contact-info">
        <div class="location">
            <h3>Location:</h3>
            <p>123 Food Street, Food City, Islamabad</p>
        </div>
        <div class="contact-us">
            <h3>Contact Us:</h3>
            <p>Email: contact@foodfusion.com</p>
            <p>Phone: +92 3181234567</p>
        </div>
    </div>
</body>
</html>
