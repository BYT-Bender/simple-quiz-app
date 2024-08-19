<?php
    session_start();

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $username = htmlspecialchars(trim($_POST['username']));
        $password = trim($_POST['password']);

        require_once 'assets/scripts/config.php';

        // Fetch the user_id and password
        if ($stmt = $conn->prepare("SELECT id, password FROM users WHERE username = ?")) {
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $stmt->bind_result($user_id, $hashed_password);
            $stmt->fetch();

            if (password_verify($password, $hashed_password)) {
                $_SESSION['username'] = $username;
                $_SESSION['user_id'] = $user_id;
                header("Location: index.php");
                exit();
            } else {
                $error_message = "Invalid username or password.";
            }

            $stmt->close();
        } else {
            $error_message = "Database error: Unable to prepare the SQL statement.";
        }

        $conn->close();
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="assets/stylesheets/form.css">
</head>
<body>
    <main>
        <form action="login.php" method="POST" class="login-form">
            <h1>Welcome Back!</h1>
            <?php
                // Display error
                if (isset($error_message)) {
                    echo '<p class="error">' . $error_message . '</p>';
                }
            ?>
            <div class="row">
                <label for="username">Username</label>
                <input type="text" name="username" id="username" placeholder="Enter username" required>
            </div>
            <div class="row">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" placeholder="Enter password" required>
            </div>
            <div class="row">
                <button type="submit">Login</button>
                <p>Don't have an account? <a href="register.php">Register here</a></p>
            </div>
        </form>
    </main>
</body>
</html>