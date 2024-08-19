<?php
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $username = htmlspecialchars(trim($_POST['username']));
        $password = trim($_POST['password']);

        require_once 'assets/scripts/config.php';

        // Check if username already exists
        if ($stmt = $conn->prepare("SELECT id FROM users WHERE username = ?")) {
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows > 0) {
                $error_message = "Username already taken.";
            } else {
                $hashed_password = password_hash($password, PASSWORD_BCRYPT);

                if ($stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)")) {
                    $stmt->bind_param("ss", $username, $hashed_password);

                    if ($stmt->execute()) {
                        header("Location: login.php");
                        exit();
                    } else {
                        $error_message = "Error: " . $stmt->error;
                    }

                    $stmt->close();
                } else {
                    $error_message = "Database error: Unable to prepare the SQL statement.";
                }
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
    <title>Register</title>
    <link rel="stylesheet" href="assets/stylesheets/form.css">
</head>
<body>
    <main class="register">
        <form action="register.php" method="POST" class="register-form">
            <h1>Register</h1>
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
                <button type="submit">Register</button>
                <p>Already have an account? <a href="login.php">Login here</a></p>
            </div>
        </form>
    </main>
</body>
</html>