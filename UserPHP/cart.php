<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" type="text/css" href="../Css/cart.css">
  <title>Cart</title>
</head>
<body>
<div class="top-bar box">
    <div class="top-bar left">
      <img src="../Picture/menuBar.svg" class="menuBar svg" alt="Menu" />
    </div>
    <div class="top-bar mid">
      <a href="../UserPHP/shoplist.php" class="logo-link">
        <img src="../Picture/logo2.png" class="logo image" alt="Logo">
      </a>
    </div>
    <div class="top-bar right">
      <a href="logout.php" class="logout">
        <img src="../Picture/logout.svg" class="profile svg" alt="Logout">
      </a>
    </div>
  </div>

  <!-- Side Menu -->
  <div id="sideMenu" class="side-menu">
    <div class="menu-header">
      <span class="close-btn">&times;</span>
      <h2>Menu</h2>
    </div>
    <div class="menu-content">
      <a href="../UserPHP/shoplist.php">- Home</a>
      <a href="userprofile.php">- Profile</a>
      <a href="history.php">- My Orders</a>
    </div>
  </div>

  <!-- Overlay -->
  <div class="overlay"></div>

  <div class="container">
  <div class="back-button">
        <div class="back"><a href="shoplist.php" class="back-link"><img src="../Picture/back-icon.png" class="back-icon" alt="Back"></a></div>
        <div class="button"><h1>Your Cart</h1></div>
      </div>
    <div id="cart-items" class="cart-items">
      <!-- Cart items will be dynamically inserted here -->
    </div>
    <div class="empty-cart-message" style="display: none;">Your cart is empty. Please add items to your cart before proceeding to checkout.</div>

    <div class="cart-total">
      <p id="total">Total: RM0.00</p>
      <button class="checkout-button" onclick="proceedToCheckout()">Proceed to Checkout</button>
    </div>
  </div>

<script>
document.addEventListener('DOMContentLoaded', function() {
  const cartItems = JSON.parse(localStorage.getItem('cartItems')) || [];
  const cartItemsContainer = document.getElementById('cart-items');
  const emptyCartMessage = document.querySelector('.empty-cart-message');
  const checkoutButton = document.querySelector('.checkout-button');
  let total = 0;

  if (cartItems.length === 0) {
    emptyCartMessage.style.display = 'block';
    checkoutButton.disabled = true;
    return;
  }

  cartItems.forEach((item, index) => {
    // Create cart item element
    const cartItemElement = document.createElement('div');
    cartItemElement.className = 'cart-item';
    cartItemElement.dataset.index = index; // Add index for reference

    cartItemElement.innerHTML = `
      <img src="${item.image}" alt="Product Image">
      <div class="item-details">
        <h2>${item.title}</h2>
        <p class="remarks">Remarks: ${item.remarks ? item.remarks : ' '}</p>
        <div class="price-controls">
          <span class="price">RM<span class="item-subtotal">${(item.price * item.quantity).toFixed(2)}</span></span>
          <div class="quantity-controls">
            <button onclick="updateQuantity(this, ${index}, ${item.price}, -1)">-</button>
            <span class="quantity">${item.quantity}</span>
            <button onclick="updateQuantity(this, ${index}, ${item.price}, 1)">+</button>
          </div>
        </div>
      </div>
    `;

    cartItemsContainer.appendChild(cartItemElement);

    // Update total
    total += item.price * item.quantity;
  });

  // Set total
  document.getElementById('total').textContent = `Total: RM${total.toFixed(2)}`;
});


function updateQuantity(button, index, price, change) {
  let cartItems = JSON.parse(localStorage.getItem('cartItems')) || [];
  const quantityElement = button.parentElement.querySelector('.quantity');
  let quantity = parseInt(quantityElement.textContent);
  quantity += change;
  if (quantity < 1) {
    if (confirm('Remove Item\nAre you sure you want to remove this item from your cart?')) {
      removeItem(index);
    }
    return;
  }

  // Update quantity in the DOM
  quantityElement.textContent = quantity;

  // Update subtotal for this item
  const itemSubtotalElement = button.parentElement.parentElement.querySelector('.item-subtotal');
  itemSubtotalElement.textContent = (price * quantity).toFixed(2);

  // Update quantity in the cartItems array
  cartItems[index].quantity = quantity;

  // Update local storage
  localStorage.setItem('cartItems', JSON.stringify(cartItems));

  // Update the total price
  updateTotal();
}

function removeItem(index) {
  let cartItems = JSON.parse(localStorage.getItem('cartItems')) || [];

  // Remove item from array
  cartItems.splice(index, 1);

  // Update local storage
  localStorage.setItem('cartItems', JSON.stringify(cartItems));

  // Remove item from DOM
  const cartItem = document.querySelector(`.cart-item[data-index='${index}']`);
  cartItem.remove();

  // Update indices for remaining items in the DOM
  const remainingItems = document.querySelectorAll('.cart-item');
  remainingItems.forEach((item, newIndex) => {
    item.dataset.index = newIndex;
    item.querySelector('.quantity-controls button:nth-of-type(1)').setAttribute('onclick', `updateQuantity(this, ${newIndex}, ${cartItems[newIndex].price}, -1)`);
    item.querySelector('.quantity-controls button:nth-of-type(2)').setAttribute('onclick', `updateQuantity(this, ${newIndex}, ${cartItems[newIndex].price}, 1)`);
  });

  // Update the total price
  updateTotal();
}

function updateTotal() {
  let total = 0;
  document.querySelectorAll('.cart-item').forEach(item => {
    const subtotal = parseFloat(item.querySelector('.item-subtotal').textContent);
    total += subtotal;
  });
  document.getElementById('total').textContent = `Total: RM${total.toFixed(2)}`;

  // Show or hide the empty cart message and disable the checkout button
  const emptyCartMessage = document.querySelector('.empty-cart-message');
  const checkoutButton = document.querySelector('.checkout-button');

  if (total === 0) {
    emptyCartMessage.style.display = 'block';
    checkoutButton.disabled = true;
  } else {
    emptyCartMessage.style.display = 'none';
    checkoutButton.disabled = false;
  }
}

function proceedToCheckout() {
  if (document.querySelector('.checkout-button').disabled) {
    alert('Your cart is empty. Please add items to your cart before proceeding to checkout.');
    return;
  }
  window.location.href = 'checkout.php';
}

document.addEventListener("DOMContentLoaded", function() {
            const sideMenu = document.getElementById("sideMenu");
            const overlay = document.querySelector('.overlay');
            const container = document.querySelector('.container');
            const menuBar = document.querySelector('.menuBar');
            const closeBtn = document.querySelector('.close-btn');

            function openMenu() {
                sideMenu.style.width = "250px";
                overlay.style.display = "block";
                container.classList.add('transparent');
                document.body.style.overflow = 'hidden'; 
            }

            function closeMenu() {
                sideMenu.style.width = "0";
                overlay.style.display = "none";
                container.classList.remove('transparent');
                document.body.style.overflow = ''; 
            }

            function closeMenuOnOutsideClick(event) {
                if (!event.target.closest('#sideMenu') && !event.target.closest('.menuBar') && !event.target.closest('.overlay')) {
                    closeMenu();
                }
            }

            if (menuBar) {
                menuBar.addEventListener('click', openMenu);
            }

            if (closeBtn) {
                closeBtn.addEventListener('click', closeMenu);
            }

            if (overlay) {
                overlay.addEventListener('click', closeMenu);
            }

            document.addEventListener('click', closeMenuOnOutsideClick);
        });
</script>

</body>
</html>
