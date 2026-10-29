<?php
// Include database connection
include_once("database.php");

// Start session
session_start();
if (!isset($_SESSION['user_id'])) {
    header("location: userlogin.php");
    exit();
}

// Fetch user ID from session
$user_id = $_SESSION['user_id'];

// Fetch user profile data from the database
$sql = "SELECT * FROM user WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Close the connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" type="text/css" href="../Css/checkout.css">
  <title>Checkout</title>
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

  <!-- Back Button -->
  <div class="container">
    <div class="back-button">
      <div class="back"><a href="cart.php" class="back-link"><img src="../Picture/back-icon.png" class="back-icon" alt="Back"></a></div>
      <div class="button"><h1>Checkout</h1></div>
    </div>

    <div class="order-summary">
        <h2>Order Summary</h2>
        <div id="checkout-items" class="checkout-items"></div>
        <div class="order-total">
          <p id="total">Total: RM0.00</p>
        </div>
    </div>

    <div class="pickup-details">
      <h2>Pickup Details</h2>
      <form id="checkout-form" method="POST">

        <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($user['fullname']); ?>"  readonly/>

        <input type="tel" id="phone" name="phone" value="<?php echo htmlspecialchars($user['contact']); ?>" readonly/>
        
        <label for="pickup-time">Preferred Pickup Time:</label>
        <input type="time" id="pickup-time" name="pickup-time" required>

        <!-- Payment Method Selection -->
        <h2>Payment Method</h2>
        <label for="payment-method">Choose Payment Method:</label>
        <select id="payment-method" name="payment-method" required>
          <option value="" disabled selected>Select One</option>
          <option value="credit-card">Credit Card</option>
          <option value="cod">Cash on Delivery</option>
        </select>

        <!-- Credit Card Payment Fields -->
        <div id="credit-card-fields" class="payment-details" style="display:none;">
          <h3>Credit Card Details</h3>
          <label for="card-number">Card Number:</label>
          <input type="text" id="card-number" name="card-number" placeholder="Enter your card number" required><br>
          
          <label for="expiry-date">Expiry Date:</label><br>
          <input type="text" id="expiry-date" name="expiry-date" placeholder="MM/YY" required><br>
          
          <label for="cvv">CVV:</label><br>
          <input type="text" id="cvv" name="cvv" placeholder="3-digit CVV" required><br>
        </div>

        <!-- Cash on Delivery (COD) Fields -->
        <div id="cod-fields" class="payment-details" style="display:none;">
          <h3>Cash on Delivery (COD)</h3>
          <p>You will pay upon receiving your order at pickup.</p>
        </div>

        <button type="submit" class="pay-button">Pay <span id="total-amount">RM0.00</span></button>
      </form>
    </div>
  </div>

  <div class="overlay"></div>
  
  <!-- Success Popup -->
  <div id="success-popup" class="popup">
    <div class="popup-content">
      <span class="close" onclick="closePopup()">&times;</span>
      <h2>Payment Successful!</h2>
      <p>Your order has been successfully placed. You will receive a confirmation shortly.</p>
      <button onclick="redirectToConfirmation()">OK</button>
    </div>
  </div>

  <script>
document.addEventListener('DOMContentLoaded', function() {
  const checkoutItemsContainer = document.getElementById('checkout-items');
  const totalAmountElement = document.getElementById('total');
  const paymentMethodSelect = document.getElementById('payment-method');
  const creditCardFields = document.getElementById('credit-card-fields');
  const codFields = document.getElementById('cod-fields');
  const cartItems = JSON.parse(localStorage.getItem('cartItems')) || [];
  let total = 0;

  // Function to update total amount
  function updateTotal() {
    total = cartItems.reduce((acc, item) => {
      return acc + (item.price * item.quantity);
    }, 0);
    totalAmountElement.textContent = `Total: RM${total.toFixed(2)}`;
    document.getElementById('total-amount').textContent = `RM${total.toFixed(2)}`;
  }

  // Render checkout items
  cartItems.forEach((item, index) => {
    const checkoutItemElement = document.createElement('div');
    checkoutItemElement.className = 'checkout-item';
    checkoutItemElement.dataset.index = index;

  checkoutItemElement.innerHTML = `
    <img src="${item.image}" class="checkout-image" alt="Product Image">
    <div class="item-details">
      <h2>${item.title}</h2>
      <p>Price: RM${item.price.toFixed(2)}</p>
      <p>Remarks: ${item.remarks || ' '}</p>
      <div class="quantity-controls">
        <button class="quantity-btn" data-action="decrease">-</button>
        <input type="number" class="quantity-input" value="${item.quantity}" min="1">
        <button class="quantity-btn" data-action="increase">+</button>
      </div>
    </div>
  `;


    checkoutItemsContainer.appendChild(checkoutItemElement);
  });

  // Initialize total
  updateTotal();

  // Handle quantity changes
// Handle quantity changes
document.querySelectorAll('.quantity-btn').forEach(button => {
  button.addEventListener('click', function() {
    const itemElement = this.closest('.checkout-item');
    const quantityInput = itemElement.querySelector('.quantity-input');
    const index = itemElement.dataset.index;

    let quantity = parseInt(quantityInput.value);

    if (this.dataset.action === 'increase') {
      quantity++;
    } else if (this.dataset.action === 'decrease') {
      if (quantity > 1) {
        quantity--;
      } else if (quantity === 1) {
        // Show confirmation before setting quantity to 0
        if (confirm("Are you sure you want to delete this item?")) {
          quantity = 0;
          // Optionally, remove the item from the cart
          cartItems.splice(index, 1);
          itemElement.remove();
        } else {
          return; // Exit if user cancels
        }
      }
    }

    if (quantity > 0) {
      quantityInput.value = quantity;
      cartItems[index].quantity = quantity;
    } else {
      // Optionally, handle the case when the quantity reaches 0
      // If you want to remove the item from the cartItems array
      cartItems.splice(index, 1);
      itemElement.remove(); // Remove the item from the DOM
    }

    // Save updated cartItems array to localStorage
    localStorage.setItem('cartItems', JSON.stringify(cartItems));

    // Update total amount
    updateTotal();
  });
});


  // Toggle payment fields
  paymentMethodSelect.addEventListener('change', function() {
    if (this.value === 'credit-card') {
      creditCardFields.style.display = 'block';
      codFields.style.display = 'none';
      document.querySelectorAll('#credit-card-fields input').forEach(input => {
        input.setAttribute('required', 'true');
      });
    } else if (this.value === 'cod') {
      creditCardFields.style.display = 'none';
      codFields.style.display = 'block';
      document.querySelectorAll('#credit-card-fields input').forEach(input => {
        input.removeAttribute('required');
      });
    } else {
      creditCardFields.style.display = 'none';
      codFields.style.display = 'none';
      document.querySelectorAll('#credit-card-fields input').forEach(input => {
        input.removeAttribute('required');
      });
    }
  });

  // Form submission handling
  document.getElementById('checkout-form').addEventListener('submit', function(event) {
    event.preventDefault();

    // Prepare order data
    const formData = new FormData(this);
    const orderData = {
      name: document.getElementById('name').value,
      contact_number: document.getElementById('phone').value,  // Changed from `phone` to `contact_number`
      pickup_time: document.getElementById('pickup-time').value, // Changed from `pickupTime` to `pickup_time`
      payment_method: paymentMethodSelect.value,  // Changed from `paymentMethod` to `payment_method`
      items: cartItems, 
    };



    // Send order data to the server
    fetch('process_order.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify(orderData)
    })
    .then(response => response.json())
    .then(data => {
      if (data.message === "Order placed successfully") {
        document.getElementById('success-popup').style.display = 'block';
        // Optionally clear cart
        localStorage.removeItem('cartItems');
        // Optionally redirect after a delay
        setTimeout(() => redirectToConfirmation(), 2000);
      } else {
        alert("Failed to place the order. Please try again.");
      }
    })
    .catch(error => {
      console.error('Error:', error);
      alert("An error occurred. Please try again.");
    });
  });

  // Close popup function
  function closePopup() {
    document.getElementById('success-popup').style.display = 'none';
  }

  // Redirect function
  function redirectToConfirmation() {
    window.location.href = 'order-confirmation.php'; // Update this with the actual confirmation page URL
  }
});

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