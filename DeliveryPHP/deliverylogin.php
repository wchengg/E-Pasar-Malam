<?php
    // database connection
    include_once("database.php");

    // Start the session
    session_start();

    // Handle login
    if (isset($_POST['login_submit'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];

        // Use prepared statements to prevent SQL injection
        $stmt = $conn->prepare("SELECT * FROM deliveryman WHERE username = ? AND password = ?");
        $stmt->bind_param("ss", $username, $password);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        if ($row) {
            $_SESSION['deliveryman_id'] = $row['deliveryman_id'];
            echo "<script>
                    alert('Logged in successfully!');
                    window.location.href = 'deliveryorder.php';
                  </script>";
        } else {
            echo "<script>alert('Username or password invalid');</script>";
        }
    }

    // Handle forgot password
if (isset($_POST['forgot_submit'])) {
    $email = $_POST['email'];  // Changed from contact to email

    // Query to check if email exists
    $sql = "SELECT * FROM deliveryman WHERE email='$email'";
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
    <title>Delivery Login</title>
    <link rel="stylesheet" type="text/css" href="../Css/deliverylogin.css">
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
        <form class="modal-content animate" action="deliverylogin.php" method="post">
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
                <span class="signup">Forgot Password? <a href="#" onclick="showModal('forgotModal')">Reset</a></span>
            </div>
        </form>
    </div>

    <div id="forgotModal" class="modal">
        <form class="modal-content animate" action="deliverylogin.php" method="post">
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

        window.onclick = function(event) {
            if (event.target.classList.contains('modal')) {
                closeModal(event.target.id);
            }
        }
    </script>
</body>
</html>

