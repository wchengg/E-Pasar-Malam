<?php
// database connection
include_once("database.php");
// Start the session
session_start();

// Handle login
if (isset($_POST['login_submit'])) {
    $contact = $_POST['contact'];
    $password = $_POST['password'];

    // Simple query to match contact and password
    $sql = "SELECT * FROM user WHERE contact='$contact' AND password='$password'";
    $result = $conn->query($sql);
    $row = mysqli_fetch_array($result);

    if ($result->num_rows > 0) {
        $_SESSION['user_id'] = $row['user_id'];
        echo "<script>
                alert('Logged in successfully!');
                window.location.href = 'shoplist.php';
              </script>";
    } else {
        echo "<script>alert('Phone number or password invalid');</script>";
    }
}

// Handle signup
if (isset($_POST['signup_submit'])) {
    $fullname = $_POST['fullname'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $contact = $_POST['contact'];
    $email = $_POST['email'];
    $dob = $_POST['dob'];
    $confirm_password = $_POST['confirm_password'];

    if ($password !== $confirm_password) {
        echo "<script>alert('Passwords do not match');</script>";
    } else {
        // Check if username already exists
        $sql = "SELECT * FROM user WHERE contact='$contact'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            echo "<script>
                    alert('This phone number already been used. Please choose a different phone number.');
                    document.getElementById('signupModal').style.display = 'none';
                    document.getElementById('loginModal').style.display = 'flex';
                  </script>";
        } else {
            // Insert new user
            $sql = "INSERT INTO user (fullname, username, password, contact, email, dob) 
                    VALUES ('$fullname', '$username', '$password', '$contact', '$email', '$dob')";
            
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
    $contact = $_POST['contact'];

    // Query to check if contact exists
    $sql = "SELECT * FROM user WHERE contact='$contact'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo "<script>alert('A password reset link has been sent.');</script>";
    } else {
        echo "<script>alert('Contact not found.');</script>";
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
    <title>User Login</title>
    <link rel="stylesheet" type="text/css" href="../Css/login2.css">
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
        <form class="modal-content animate" action="userlogin.php" method="post">
            <div class="imgcontainer">
                <span onclick="closeModal('loginModal')" class="close" title="Close Modal">&times;</span>
            </div>

            <div class="container">
                <label for="contact"><b>Contact:</b></label>
                <input type="text" placeholder="Enter contact" name="contact" required>

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
        <form class="modal-content animate" action="userlogin.php" method="post">
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

                <label for="dob"><b>Date of Birth:</b></label>
                <input type="date" name="dob" required>

                <button class="submit" name="signup_submit" type="submit">Sign Up</button>
            </div>
        </form>
    </div>

    <!-- Forgot Password Modal -->
    <div id="forgotModal" class="modal">
        <form class="modal-content animate" action="userlogin.php" method="post">
            <div class="imgcontainer">
                <span onclick="closeModal('forgotModal')" class="close" title="Close Modal">&times;</span>
            </div>

            <div class="container">
                <label for="contact"><b>Contact Number:</b></label>
                <input type="text" placeholder="Enter Contact Number" name="contact" required>
                
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
