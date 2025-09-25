<?php
// Database connection
$host = "127.0.0.1";
$user = "webuser";
$pass = "webpass123";
$db   = "bd1";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username && $password) {
        // Use prepared statement to prevent SQL injection
        $stmt = $conn->prepare("SELECT password FROM user2 WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->bind_result($storedHash);
        
        if ($stmt->fetch()) {
            // Compare the submitted password with stored hash
            if (password_verify($password, $storedHash)) {
                $message = "Login successful! Welcome, $username.";
            } else {
                $message = "Invalid password.";
            }
        } else {
            $message = "User not found.";
        }

        $stmt->close();
    } else {
        $message = "Please enter both username and password.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
</head>
<body>
<h2>Login</h2>

<form method="post" action="">
    <label>Username: <input type="text" name="username" required></label><br><br>
    <label>Password: <input type="password" name="password" required></label><br><br>
    <button type="submit">Login</button>
</form>

<p><?php echo htmlspecialchars($message); ?></p>
</body>
</html>
