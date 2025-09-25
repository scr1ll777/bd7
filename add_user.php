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
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username && $email && $password) {
        // Hash the password
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        // Use prepared statement to prevent SQL injection
        $stmt = $conn->prepare("INSERT INTO user2 (username, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $email, $passwordHash);

        if ($stmt->execute()) {
            $message = "User '$username' successfully registered!";
        } else {
            $message = "Error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        $message = "Please fill all fields.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register User</title>
</head>
<body>
<h2>Register New User</h2>

<form method="post" action="">
    <label>Username: <input type="text" name="username" required></label><br><br>
    <label>Email: <input type="email" name="email" required></label><br><br>
    <label>Password: <input type="password" name="password" required></label><br><br>
    <button type="submit">Register</button>
</form>

<p><?php echo htmlspecialchars($message); ?></p>
</body>
</html>
