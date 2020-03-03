<?php
// reqects group join request
include 'init.php';
// get session id and parameters of the request
$token = $_SESSION['token'];
$request = $_POST['requestId'];

// get user's email
$stmt = $db->prepare('SELECT email FROM Users where sessionId=:token');
$stmt->bindValue(':token', $token, SQLITE3_TEXT);
$query = $stmt->execute()->fetchArray();
$email = $query['email'];

// check if request was sent to this user
$stmt = $db->prepare('SELECT email FROM Requests where id=:request');
$stmt->bindValue(':request', $request, SQLITE3_TEXT);
$query = $stmt->execute()->fetchArray();

if ($email != $query['email'])
    echo json_decode('You do not have acces to this resource');

// delete request
$stmt = $db->prepare('DELETE FROM Requests where id=:request');
$stmt->bindValue(':request', $request, SQLITE3_TEXT);
$query = $stmt->execute()->fetchArray();

echo json_decode('success');
