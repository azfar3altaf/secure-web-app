// Function to filter menu items based on the search input
function filterMenu() {
    const search = document.getElementById('search').value.toLowerCase();
    const items = document.querySelectorAll('.menu-item');

    items.forEach(function (item) {
        const itemName = item.querySelector('.item-name').textContent.toLowerCase();
        if (itemName.includes(search)) {
            item.style.display = 'block';
        } else {
            item.style.display = 'none';
        }
    });
}

// Add to Cart functionality
function addToCart(event) {
    const button = event.target;
    const id = button.getAttribute('data-id');
    const name = button.getAttribute('data-name');
    const description = button.getAttribute('data-description');
    const price = button.getAttribute('data-price');

    // Get existing cart from localStorage or initialize an empty array
    let cart = JSON.parse(localStorage.getItem('cart')) || [];
    
    // Check if the item is already in the cart
    const itemIndex = cart.findIndex(item => item.id === id);

    if (itemIndex > -1) {
        // If item is already in the cart, increase the quantity
        cart[itemIndex].quantity += 1;
    } else {
        // Otherwise, add new item to the cart
        cart.push({ id, name, description, price, quantity: 1 });
    }

    // Save updated cart back to localStorage
    localStorage.setItem('cart', JSON.stringify(cart));
    updateCartCount();  // Update cart count on menu page

    // Alert user that the item has been added
    alert(`${name} has been added to your cart!`);
}

// Update cart item count
function updateCartCount() {
    const cart = JSON.parse(localStorage.getItem('cart')) || [];
    const count = cart.reduce((total, item) => total + item.quantity, 0);
    document.getElementById('cart-count').textContent = count;
}

// Event listeners
window.onload = function () {
    document.getElementById('search').addEventListener('keyup', filterMenu);
    document.querySelectorAll('.add-to-cart').forEach(button => button.addEventListener('click', addToCart));
    updateCartCount();  // Update cart count on page load
};
