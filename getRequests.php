<?php
include 'init.php';
$token = $_SESSION['token'];
$stmt = $db->prepare('SELECT email FROM Users where sessionId=:token');
$stmt->bindValue(':token', $token, SQLITE3_TEXT);
$query = $stmt->execute()->fetchArray();
$email = $query['email'];

$stmt = $db->prepare('SELECT leader, name FROM Requests LEFT JOIN Groups where Requests.email=:email');
$stmt->bindValue(':email', $email, SQLITE3_TEXT);
$query = $stmt->execute()->fetchArray();
// print_r($query);
echo json_encode($query);