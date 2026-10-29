<?php
// database connection
include_once("database.php");
// Start the session
session_start();

// Handle login
if (isset($_POST['login_submit'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Simple query to match username and password
    $sql = "SELECT * FROM merchant WHERE username='$username' AND password='$password'";
    $result = $conn->query($sql);
    $row = mysqli_fetch_array($result);

    if ($result->num_rows > 0) {
        $_SESSION['merchant_id'] = $row['merchant_id']; // Store merchant_id in session
        echo "<script>
                alert('Logged in successfully!');
                window.location.href = 'orders.php'; // Redirect to registeritem.php
              </script>";
    } else {
        echo "<script>alert('Username or password invalid');</script>";
    }
}

// Handle signup
if (isset($_POST['signup_submit'])) {
    $fullname = $_POST['fullname'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $contact = $_POST['contact'];
    $email = $_POST['email'];
    $businesslicenseno = $_POST['businesslicenseno'];
    $shopname = $_POST['shopname'];
    $shopcategory1 = $_POST['shopcategory1'];
    $shopcategory2 = $_POST['shopcategory2'];
    $confirm_password = $_POST['confirm_password'];

    if ($password !== $confirm_password) {
        echo "<script>alert('Passwords do not match');</script>";
    } else {
        // Check if username already exists
        $sql = "SELECT * FROM merchant WHERE username='$username'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            echo "<script>
                    alert('Username already exists. Please choose a different username.');
                    document.getElementById('signupModal').style.display = 'none';
                    document.getElementById('loginModal').style.display = 'flex';
                  </script>";
        } else {
            // Insert new merchant
            $sql = "INSERT INTO merchant (fullname, username, password, contact, email, businesslicenseno, shopname, shopcategory1, shopcategory2) 
                    VALUES ('$fullname', '$username', '$password', '$contact', '$email', '$businesslicenseno', '$shopname', '$shopcategory1', '$shopcategory2')";
            
            if ($conn->query($sql) === TRUE) {
                $_SESSION['registration_success'] = true;
                echo "<script>
                        alert('Registered successfully! Please log in.');
                      </script>";
            } else {
                echo "Error: " . $conn->error;
            }
        }
    }
}


// Handle forgot password
if (isset($_POST['forgot_submit'])) {
    $email = $_POST['email'];  // Changed from contact to email

    // Query to check if email exists
    $sql = "SELECT * FROM merchant WHERE email='$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Normally, you'd send an email with a reset link here
        echo "<script>alert('A password reset link has been sent to your email.');</script>";
    } else {
        echo "<script>alert('Email not found.');</script>";
    }
}

// Close the connection
$conn->close();
?>



<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Merchant Login</title>
    <link rel="stylesheet" type="text/css" href="../Css/merchantlogin.css">
</head>
<body>
    <div class="content">
        <div class="logo-container">
            <img src="../Picture/logo2.png" class="logo">
        </div>
        <h2>Welcome to E-Pasar Malam SS2</h2>
        <button class="loginnow" onclick="showModal('loginModal')">Login now!</button>
    </div>

    <!-- Login Modal -->
    <div id="loginModal" class="modal">
        <form class="modal-content animate" action="merchantlogin.php" method="post">
            <div class="imgcontainer">
                <span onclick="closeModal('loginModal')" class="close" title="Close Modal">&times;</span>
            </div>

            <div class="container">
                <label for="username"><b>Username:</b></label>
                <input type="text" placeholder="Enter Username" name="username" required>

                <label for="password"><b>Password:</b></label>
                <input type="password" placeholder="Enter Password" name="password" required>
                  
                <button class="submit" name="login_submit" type="submit">Login</button>
            </div>

            <div class="container" style="background-color:#f1f1f1">
                <span class="signup">Not a member yet? <a href="#" onclick="showModal('signupModal')">Sign up</a></span>
                <span class="signup">Forgot Password? <a href="#" onclick="showModal('forgotModal')">Reset</a></span>
            </div>
        </form>
    </div>

    <!-- Sign-Up Modal -->
    <div id="signupModal" class="modal">
        <form class="modal-content animate" action="merchantlogin.php" method="post">
            <div class="imgcontainer">
                <span onclick="closeModal('signupModal')" class="close" title="Close Modal">&times;</span>
            </div>

            <div class="container">
                <label for="fullname"><b>Full Name:</b></label>
                <input pattern="[A-Za-z ]*" title="Please enter your full name. Alphabet only."
                type="text" placeholder="Enter Full Name" name="fullname" required>

                <label for="contact"><b>Contact Number:</b></label>
                <input pattern="\d*.{10,}" title="Your contact number should be number only. At least 10 numbers."
                 type="tel" placeholder="Enter Contact Number" name="contact" required>

                <label for="email"><b>Email:</b></label>
                <input type="email" placeholder="Enter Email" name="email" required>

                <label for="username"><b>Username:</b></label>
                <input type="text" placeholder="Enter Username" name="username" required>

                <label for="password"><b>Create a Password:</b></label>
                <input type="password" placeholder="Enter Password" name="password" required>

                <label for="confirm_password"><b>Re-enter Password:</b></label>
                <input type="password" placeholder="Re-enter Password" name="confirm_password" required>

                <label for="businesslicenseno"><b>Business License No. (SSM):</b></label>
                <input type="text" pattern="\d{4}-\d{2}-\d{6}" title="Your SSM should follow the format YYYY-XX-XXXXXX."
                    placeholder="2024-01-000001" name="businesslicenseno" required>


                <label for="shopname"><b>Shop Name:</b></label>
                <input type="text" placeholder="Shop Name" name="shopname" required>

                <label for="shopcategory1"><b>Primary Shop Category:</b></label>
                <select name="shopcategory1" required>
                    <option value="" disabled selected>Select One</option>
                    <option value="Food & Beverages">Food & Beverages</option>
                    <option value="Bread & Pastries">Bread & Pastries</option>
                    <option value="Fresh Items">Fresh Items (e.g., Fruits, Vegetables, Meat, Seafood)</option>
                    <option value="Clothing & Accessories">Clothing & Accessories</option>
                    <option value="Household Items">Household Items</option>
                    <option value="Electronics & Gadgets">Electronics & Gadgets</option>
                    <option value="Health & Beauty">Health & Beauty</option>
                    <option value="Toys & Hobbies">Toys & Hobbies</option>
                    <option value="Books & Stationery">Books & Stationery</option>
                    <option value="Flowers & Plants">Flowers & Plants</option>
                </select>

                <label for="shopcategory2"><b>Secondary Shop Category:</b></label>
                <select name="shopcategory2">
                    <option value="" disabled selected>Optional</option>
                    <option value="Food & Beverages">Food & Beverages</option>
                    <option value="Bread & Pastries">Bread & Pastries</option>
                    <option value="Fresh Items">Fresh Items (e.g., Fruits, Vegetables, Meat, Seafood)</option>
                    <option value="Clothing & Accessories">Clothing & Accessories</option>
                    <option value="Household Items">Household Items</option>
                    <option value="Electronics & Gadgets">Electronics & Gadgets</option>
                    <option value="Health & Beauty">Health & Beauty</option>
                    <option value="Toys & Hobbies">Toys & Hobbies</option>
                    <option value="Books & Stationery">Books & Stationery</option>
                    <option value="Flowers & Plants">Flowers & Plants</option>
                    <option value="none">----</option>
                </select>



                <button class="submit" name="signup_submit" type="submit">Sign Up</button>
            </div>
        </form>
    </div>

    <div id="forgotModal" class="modal">
        <form class="modal-content animate" action="merchantlogin.php" method="post">
            <div class="imgcontainer">
                <span onclick="closeModal('forgotModal')" class="close" title="Close Modal">&times;</span>
            </div>

            <div class="container">
                <label for="email"><b>Email:</b></label>
                <input type="email" placeholder="Enter Email" name="email" required>

                <button class="submit" name="forgot_submit" type="submit">Reset Password</button>
            </div>
        </form>
    </div>



    <script>
        function showModal(modalId) {
            document.getElementById(modalId).style.display = 'flex';
        }

        function closeModal(modalId) {
            document.getElementById(modalId).style.display = 'none';
        }

        // Close modals when clicking outside
        window.onclick = function(event) {
            if (event.target.classList.contains('modal')) {
                closeModal(event.target.id);
            }
        }

        // Show login modal if registration was successful
        window.onload = function() {
            <?php if (isset($_SESSION['registration_success']) && $_SESSION['registration_success'] === true): ?>
                showModal('loginModal');
                <?php unset($_SESSION['registration_success']); ?>
            <?php endif; ?>
        }
    </script>
</body>
</html>
