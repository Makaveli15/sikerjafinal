<?php
session_start();
if ($_SESSION['role'] !== 'user') {
    header("Location: ../login.php");
    exit;
}
echo "<h1>Dashboard User</h1>";
echo "<a href='../logout.php'>Logout</a>";
