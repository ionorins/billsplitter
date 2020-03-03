<?php
// changes the name of the user
include 'init.php';
$token = session_id();
$name = $_POST['name'];

$stmt = $db->prepare('UPDATE Users SET name=:name WHERE sessionId=:token');
$stmt->bindValue(':name', $name, SQLITE3_TEXT);
$stmt->bindValue(':token', $token, SQLITE3_TEXT);
$stmt->execute();
echo json_encode('success');