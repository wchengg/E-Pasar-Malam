<?php
  // database connection
  include_once("database.php");
  // Start the session
  session_start();
  // Check login (change id to userid/adminid/merchantid)
  if(!isset($_SESSION['user_id'])){
    header("location:userlogin.php");
    exit(); // Ensure no further code is executed after redirect
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" type="text/css" href="../Css/stall1.css">
  <title>Products</title>
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

  <div class="content">
    <!-- Back Button -->
    <div class="back-button">
      <a href="shoplist.php" class="back-link"><img src="../Picture/back-icon.png" class="back-icon"></a>
    </div>

    <!-- Main Content -->
    <div class="main-content">
      <div class="container">

        <!-- This is the shop detail -->
        <?php
          if(isset($_GET['shopid'])){
            $shopid=$_GET['shopid'];
            $sql = "SELECT * FROM shop WHERE shop_id = $shopid";
            $result = mysqli_query($conn,$sql);
            //fetch data
            $row = mysqli_fetch_array($result);
          } else{
            echo "<h2>No data</h2>";
          }
        ?>
        <div class="product-header">
          <img src="<?php echo $row['shoppic']?>" alt="Header Image" class="header-image">
          <div class="product-details">
            <h1><?php echo $row['shopname']; ?></h1>
            <p class="category"><?php echo $row['shopcategory']; ?></p>
            <p class="rating">⭐<?php echo $row['shoprating']; ?></p>
            <p class="arrival-time">Pick up in <?php echo $row['shoptime'];?> mins</p>
          </div>
        </div>

        <!-- Start here is products -->
        <div class="products">
          <?php
            $sql = "SELECT * FROM item WHERE shop_id=$shopid ORDER BY item_id ";
            $result = $conn->query($sql);
            if($result->num_rows > 0){
              while($row = $result->fetch_assoc()){ 
          ?>
            <div class="product-item">
                <img src="<?php echo $row['itempic'];?> " alt="Product 1" class="product-image">
                <div class="product-info">
                    <h2><?php echo $row['itemname'];?> </h2>
                    <p><?php echo $row['sold'];?>  sold</p>
                    <p class="price">RM<?php echo $row['itemprice'];?> </p>
                </div>
                <div class="cart-controls">
                    <button class="add-to-cart" onclick="openPopup(this, '<?php echo $row['itempic'];?>', '<?php echo $row['itemname'];?>', <?php echo $row['itemprice'];?>, '<?php echo $row['item_id'];?>')">+</button>
                </div>
            </div>

          <?php
              }
            }else{
          ?>  
            <h2>Sorry, no product from this stall.</h2>

          <?php
            };
          ?>
        </div>
      </div>

      <div class="view-cart">
        <p id="cart-total">RM0.00</p>
        <a href="./cart.php" class="checkout-link">
          <button class="checkout-button">View cart</button>
        </a>
      </div>
    </div>

    <div id="popup" class="popup">
      <div class="popup-content">
        <span class="close" onclick="closePopup()">&times;</span>
        <img id="popup-image" src="" alt="Product Image">
        <h2 id="popup-title">...</h2>
        <p id="popup-price">...</p> 
        <textarea id="popup-remarks" placeholder="Add any remarks..." maxlength="20"></textarea>
        <div class="cart-controls">
          <button onclick="changeQuantityPopup(-1)">-</button>
          <span id="popup-quantity">1</span>
          <button onclick="changeQuantityPopup(1)">+</button>
        </div>
        <button class="add-to-cart-popup" onclick="addToCartFromPopup()">Add to Cart</button>
      </div>
    </div>
  </div>

  <script>
    // Cart variables
      let cartTotal = 0;
      let itemCount = 0;
      let currentPrice = 0;
      let currentQuantity = 1;
      let currentButton = null;
      let currentImage = '';
      let currentTitle = '';

      function openPopup(button, image, title, price, itemId) {
          currentButton = button;
          currentPrice = price;
          currentImage = image;
          currentTitle = title;
          currentItemId = itemId; // Store item_id
          document.getElementById('popup-image').src = image;
          document.getElementById('popup-title').innerText = title;
          document.getElementById('popup-price').innerText = `RM${price.toFixed(2)}`;
          document.getElementById('popup-quantity').innerText = 1;
          document.getElementById('popup-remarks').value = ""; // Reset remarks
          document.getElementById('popup').style.display = 'flex';
      }


      function closePopup() {
        document.getElementById('popup').style.display = 'none';
      }

      function changeQuantityPopup(change) {
        currentQuantity += change;
        if (currentQuantity < 1) currentQuantity = 1;
        document.getElementById('popup-quantity').innerText = currentQuantity;
        document.getElementById('popup-price').innerText = `RM${(currentPrice * currentQuantity).toFixed(2)}`;
      }

      function addToCartFromPopup() {
        closePopup();
        const parent = currentButton.closest('.cart-controls');
        let quantity = currentQuantity;
        let remarks = document.getElementById('popup-remarks').value; // Get remarks

        parent.innerHTML = `
            <button onclick="changeQuantity(this, ${currentPrice}, -1)" data-image="${currentImage}" data-title="${currentTitle}" data-item-id="${currentItemId}" data-remarks="${remarks}">-</button>
            <span class="quantity">${quantity}</span>
            <button onclick="changeQuantity(this, ${currentPrice}, 1)" data-image="${currentImage}" data-title="${currentTitle}" data-item-id="${currentItemId}" data-remarks="${remarks}">+</button>
        `;
        parent.setAttribute('data-quantity', quantity);

        // Update the cart total and item count
        cartTotal += currentPrice * currentQuantity;
        itemCount += currentQuantity;
        updateCart();

        // Store cart data in localStorage
        let cartItems = JSON.parse(localStorage.getItem('cartItems')) || [];
        let existingItemIndex = cartItems.findIndex(item => item.title === currentTitle);

        if (existingItemIndex >= 0) {
            // Update existing item quantity
            cartItems[existingItemIndex].quantity += currentQuantity;
            cartItems[existingItemIndex].remarks = remarks; // Update remarks
        } else {
            // Add new item
            cartItems.push({ 
                title: currentTitle, 
                price: currentPrice, 
                quantity: currentQuantity, 
                image: currentImage, 
                item_id: currentItemId, // Store item_id
                remarks: remarks // Store remarks
            });
        }
        
        localStorage.setItem('cartItems', JSON.stringify(cartItems));
      }


      function changeQuantity(button, price, change) {
        const parent = button.closest('.cart-controls');
        let quantity = parseInt(parent.getAttribute('data-quantity'));
        let itemId = button.getAttribute('data-item-id'); // Get item_id
        let remarks = button.getAttribute('data-remarks'); // Get remarks

        quantity += change;

        if (quantity <= 0) {
            // Remove item from the cart visually
            parent.innerHTML = `<button class="add-to-cart" onclick="openPopup(this, '${button.dataset.image}', '${button.dataset.title}', ${price}, '${itemId}')">+</button>`;
            
            // Update the total and item count
            cartTotal -= price;
            itemCount--;
            
            // Remove item from local storage
            let cartItems = JSON.parse(localStorage.getItem('cartItems')) || [];
            cartItems = cartItems.filter(item => item.title !== button.dataset.title);
            localStorage.setItem('cartItems', JSON.stringify(cartItems));
            
        } else {
            // Update the quantity visually
            parent.querySelector('.quantity').innerText = quantity;
            cartTotal += price * change;
            itemCount += change;
            parent.setAttribute('data-quantity', quantity);
            
            // Update cart data in local storage
            let cartItems = JSON.parse(localStorage.getItem('cartItems')) || [];
            let itemIndex = cartItems.findIndex(item => item.title === button.dataset.title);
            if (itemIndex >= 0) {
                cartItems[itemIndex].quantity = quantity;
                cartItems[itemIndex].item_id = itemId; // Update item_id
                cartItems[itemIndex].remarks = remarks; // Update remarks
            }
            localStorage.setItem('cartItems', JSON.stringify(cartItems));
        }

        updateCart();
      }




      function updateCart() {
        let cartItems = JSON.parse(localStorage.getItem('cartItems')) || [];
        cartTotal = 0;
        itemCount = 0;

        cartItems.forEach(item => {
          cartTotal += item.price * item.quantity;
          itemCount += item.quantity;

          // Find the product item container by matching the title
          let productContainers = document.querySelectorAll('.product-item');
          productContainers.forEach(container => {
            let productTitle = container.querySelector('.product-info h2').innerText;
            if (productTitle === item.title) {
              let controls = container.querySelector('.cart-controls');
              controls.innerHTML = `
                <button onclick="changeQuantity(this, ${item.price}, -1)" data-image="${item.image}" data-title="${item.title}">-</button>
                <span class="quantity">${item.quantity}</span>
                <button onclick="changeQuantity(this, ${item.price}, 1)" data-image="${item.image}" data-title="${item.title}">+</button>
              `;
              controls.setAttribute('data-quantity', item.quantity);
            }
          });
        });

        document.getElementById('cart-total').innerText = `RM${cartTotal.toFixed(2)} (${itemCount} items)`;
      }

      // Call updateCart when the page loads to ensure cart total is updated from local storage
      window.onload = updateCart;


      //Sidemenu
      const backButton = document.querySelector('.back-button');
      const sideMenu = document.getElementById('sideMenu');
      const overlay = document.querySelector('.overlay');
      const container = document.querySelector('.container');

      function openMenu() {
        sideMenu.style.width = "250px"; // Adjust the width as needed
        overlay.style.display = "block";
        container.classList.add('transparent');
        document.body.style.overflow = 'hidden'; 
        backButton.classList.add('hidden'); // Hide the back button
      }

      function closeMenu() {
        sideMenu.style.width = "0";
        overlay.style.display = "none";
        container.classList.remove('transparent');
        document.body.style.overflow = ''; 
        backButton.classList.remove('hidden'); // Show the back button
      }

      function closeMenuOnOutsideClick(event) {
        if (!event.target.closest('#sideMenu') && !event.target.closest('.menuBar') && !event.target.closest('.overlay')) {
          closeMenu();
        }
      }

      document.querySelector('.menuBar').addEventListener('click', openMenu);
      document.querySelector('.close-btn').addEventListener('click', closeMenu);
      overlay.addEventListener('click', closeMenu);

      document.addEventListener('click', closeMenuOnOutsideClick);

  </script>
  
  
</body>
</html>
