
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

// Initialize message
$message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    // Simple protection against SQL injection
    $username = $conn->real_escape_string($username);

    // Query user
    $sql = "SELECT password FROM user1 WHERE username = '$username'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();

        // Compare passwords (plain text version)
        if ($password === $row['password']) {
            $message = "Login successful! Welcome, $username.";
        } else {
            $message = "Invalid password.";
        }
    } else {
        $message = "User not found.";
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
