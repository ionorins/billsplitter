<?php
include 'init.php';

$group_id = $_POST['groupId'];
$email = $_POST['email'];
$token = $_SESSION['token'];

$stmt = $db->prepare('SELECT email FROM Users where sessionId=:token');
$stmt->bindValue(':token', $token, SQLITE3_TEXT);
$query = $stmt->execute()->fetchArray();
$test_email = $query['email'];

$stmt = $db->prepare('SELECT leader FROM Groups where id=:group_id');
$stmt->bindValue(':group_id', $group_id, SQLITE3_TEXT);
$query = $stmt->execute()->fetchArray();
$owner = $query['leader'];

if ($owner != $test_email) {
    echo json_encode('You are not the owner of this group.');
    die();
}

if ($owner == $email) {
    echo json_encode('You cannot leave your own group.');
    die();
}

$stmt = $db->prepare('DELETE FROM Memberships WHERE groupId=:group_id AND email=:email');
$stmt->bindValue(':group_id', $group_id, SQLITE3_TEXT);
$stmt->bindValue(':email', $email, SQLITE3_TEXT);
$stmt->execute();

echo json_encode('success');
