<?php
include 'init.php';
$token = $_SESSION['token'];
$stmt = $db->prepare('SELECT email FROM Users where sessionId=:token');
$stmt->bindValue(':token', $token, SQLITE3_TEXT);
$query = $stmt->execute()->fetchArray();
$email = $query['email'];
$group_id = $_GET['groupId'];

$stmt = $db->prepare('SELECT email, Users.name FROM Memberships LEFT JOIN Groups where Groups.id=:group_id');
$stmt->bindValue(':token', $token, SQLITE3_TEXT);
$stmt->bindValue(':group_id', $group_id, SQLITE3_TEXT);
$query = $stmt->execute()->fetchArray();

if (!in_array($email, $query)) {
    echo json_encode('You do not have access to this resource');
    die();
}

echo json_encode($query);