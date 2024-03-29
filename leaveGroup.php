<?php
// removes user from group
include 'init.php';
// get session id, request parameters and user's email
$group_id = $_POST['groupId'];
$token = $_SESSION['token'];
$stmt = $db->prepare('SELECT email FROM Users where sessionId=:token');
$stmt->bindValue(':token', $token, SQLITE3_TEXT);
$query = $stmt->execute()->fetchArray();
$email = $query['email'];

// check if user is the owner of the group
$stmt = $db->prepare('SELECT leader FROM Groups where id=:group_id');
$stmt->bindValue(':group_id', $group_id, SQLITE3_TEXT);
$query = $stmt->execute()->fetchArray();
$owner = $query['leader'];

if ($owner == $email) {
    echo json_encode('You cannot leave a group you own.');
    die();
}

// remove user from group
$stmt = $db->prepare('DELETE FROM Memberships WHERE groupId=:group_id AND email=:email');
$stmt->bindValue(':group_id', $group_id, SQLITE3_TEXT);
$stmt->bindValue(':email', $email, SQLITE3_TEXT);
$stmt->execute();

echo json_encode('success');