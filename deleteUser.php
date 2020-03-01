<?php
include 'init.php';
$token = $_SESSION['token'];
$pass = $_POST['password'];
$stmt = $db->prepare('SELECT salt, password FROM Users where sessionId=:token');
$stmt->bindValue(':token', $token, SQLITE3_TEXT);
$query = $stmt->execute()->fetchArray();

$salt = $query['salt'];
$encrypted_password = sha1($salt . '--' . $pass);

if ($encrypted_password == $query['password']) {
    $stmt = $db->prepare('DELETE FROM Users WHERE sessionId=:token');
    $stmt->bindValue(':token', $token, SQLITE3_TEXT);
    $stmt->execute();
    echo json_encode('success');
} else
    echo json_encode('Password is incorrect.');