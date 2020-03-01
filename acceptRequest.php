<?php
include 'init.php';
$token = $_SESSION['token'];
$request = $_POST['requestId'];

$stmt = $db->prepare('SELECT email FROM Users where sessionId=:token');
$stmt->bindValue(':token', $token, SQLITE3_TEXT);
$query = $stmt->execute()->fetchArray();
$email = $query['email'];

$stmt = $db->prepare('SELECT email, groupId FROM Requests where id=:request');
$stmt->bindValue(':request', $request, SQLITE3_TEXT);
$query = $stmt->execute()->fetchArray();

if ($email != $query['email'])
    echo json_encode('You do not have acces to this resource');

$group_id = $query['groupId'];

$stmt = $db->prepare('INSERT INTO Memberships VALUES(:id, :groupId, :email);');
$stmt->bindValue(':id', create_token(), SQLITE3_TEXT);
$stmt->bindValue(':groupId', $group_id, SQLITE3_TEXT);
$stmt->bindValue(':email', $email, SQLITE3_TEXT);
$stmt->execute();

$stmt = $db->prepare('DELETE FROM Requests where id=:request');
$stmt->bindValue(':request', $request, SQLITE3_TEXT);
$query = $stmt->execute()->fetchArray();

echo json_encode('success');
