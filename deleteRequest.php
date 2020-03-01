<?php
$token = $_SESSION['token'];
$request = $_POST['requestId'];

$stmt = $db->prepare('SELECT email FROM Users where sessionId=:token');
$stmt->bindValue(':token', $token, SQLITE3_TEXT);
$query = $stmt->execute()->fetchArray();
$email = $query['email'];

$stmt = $db->prepare('SELECT email FROM Requests where id=:request');
$stmt->bindValue(':request', $request, SQLITE3_TEXT);
$query = $stmt->execute()->fetchArray();

if ($email != $query['email'])
    echo json_decode('You do not have acces to this resource');

$stmt = $db->prepare('DELETE FROM Requests where id=:request');
$stmt->bindValue(':request', $request, SQLITE3_TEXT);
$query = $stmt->execute()->fetchArray();

echo json_decode('success');
