<?php
include "init.php";

// if user is not logged in, redirect to index
if (array_key_exists('token', $_SESSION) == false) {
        echo '<script> location.replace("index.php"); </script>';
        die();
}

$token = $_SESSION['token'];
$stmt = $db->prepare('SELECT email FROM Users where sessionId=:token');
$stmt->bindValue(':token', $token, SQLITE3_TEXT);
$query = $stmt->execute()->fetchArray();
$email = $query['email'];

if ($email == null) {
        echo '<script> location.replace("index.php"); </script>';
        die();
}
?>

<div class="navbar">
        <a class="navbar-brand" href="groups.php">Groups</a>
        <a class="navbar-brand" href="bills.php">Bills</a>
        <a class="navbar-brand" href="account.php">Account</a>
        <a class="navbar-brand" href="logout.php">Log Out</a>
</div>
<div class="space"> space </div>