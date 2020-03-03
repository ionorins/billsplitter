<?php
// changes the name of the group
include 'init.php';
// get user email and request parameters
$name = $_POST['name'];
$group_id = $_POST['groupId'];
$token = $_SESSION['token'];

$stmt = $db->prepare('SELECT email FROM Users where sessionId=:token');
$stmt->bindValue(':token', $token, SQLITE3_TEXT);
$query = $stmt->execute()->fetchArray();
$email = $query['email'];

// get group leader
$stmt = $db->prepare('SELECT leader FROM Groups where groupId=:group_id');
$stmt->bindValue(':group_id', $group_id, SQLITE3_TEXT);
$query = $stmt->execute()->fetchArray();

// if user is the leader of the group, change its name
if ($email == $query['leader']) {
    $stmt = $db->prepare('UPDATE Users SET name=:name WHERE groupId=:group_id');
    $stmt->bindValue(':name', $name, SQLITE3_TEXT);
    $stmt->bindValue(':group_id', $group_id, SQLITE3_TEXT);
    $stmt->execute();
    echo json_encode('success');
    die();
}

// otherwise return error
echo json_encode('You are not the owner of this group');
