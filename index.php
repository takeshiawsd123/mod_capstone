<?php
session_start();

// CONNECT TO CORRECT DB
$conn = new mysqli("localhost", "root", "", "smart_track_db");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Redirect if already logged in
if (isset($_SESSION['username'])) {
    header("Location: dashboard.php");
    exit();
}

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = htmlspecialchars(trim($_POST['username']));
    $password = trim($_POST['password']);

    // HARD CODED ADMIN (OPTIONAL)
    if ($username === "admin" && $password === "admin123") {
        $_SESSION['id'] = 0;
        $_SESSION['username'] = "admin";
        $_SESSION['role'] = "admin";

        header("Location: dashboard.php");
        exit();
    }

    // DATABASE LOGIN
    $stmt = $conn->prepare("SELECT id, password, role FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        if (password_verify($password, $row['password'])) {
            $_SESSION['id'] = $row['id'];
            $_SESSION['username'] = $username;
            $_SESSION['role'] = strtolower($row['role']); // normalize

            header("Location: dashboard.php");
            exit();
        } else {
            $error = "Invalid username or password.";
        }
    } else {
        $error = "Invalid username or password.";
    }

    $stmt->close();
}
?>