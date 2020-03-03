<?php
// returns the amount of money the user owes
include 'init.php';
$token = $_SESSION['token'];

$stmt = $db->prepare('SELECT email FROM Users where sessionId=:token');
$stmt->bindValue(':token', $token, SQLITE3_TEXT);
$query = $stmt->execute()->fetchArray();
$email = $query['email'];

$stmt = $db->prepare('SELECT SUM(amount) FROM Bills where payer=:email');
$stmt->bindValue(':email', $email, SQLITE3_TEXT);
$query = $stmt->execute()->fetchArray();

echo json_encode($query['SUM(amount)']);
