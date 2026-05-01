<?php
session_start();
include "db_conn.php"; 

if (!isset($_SESSION['role']) || !isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

$role = $_SESSION['role'];
$user_id = $_SESSION['id'] ?? 0; 
$username = $_SESSION['username'] ?? 'User';

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}