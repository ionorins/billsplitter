<?php
// creates new group
include 'init.php';
// get session id and parameters of the request
$name = $_POST['name'];
$token = $_SESSION['token'];

$stmt = $db->prepare('SELECT email FROM Users where sessionId=:token');
$stmt->bindValue(':token', $token, SQLITE3_TEXT);
$query = $stmt->execute()->fetchArray();
$email = $query['email'];

// insert group into database
$group_id = create_token();

$stmt = $db->prepare('INSERT INTO Groups VALUES(:id, :name, :email);');
$stmt->bindValue(':email', $email, SQLITE3_TEXT);
$stmt->bindValue(':name', $name, SQLITE3_TEXT);
$stmt->bindValue(':id', $group_id, SQLITE3_TEXT);
$stmt->execute();

// add the user in the group
$stmt = $db->prepare('INSERT INTO Memberships VALUES(:id, :groupId, :email);');
$stmt->bindValue(':id', create_token(), SQLITE3_TEXT);
$stmt->bindValue(':groupId', $group_id, SQLITE3_TEXT);
$stmt->bindValue(':email', $email, SQLITE3_TEXT);
$stmt->execute();

echo json_encode('success');
