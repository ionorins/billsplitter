<?php
// sends group joining request
include 'init.php';
// get request parameters and user email
$group_id = $_POST['groupId'];
$email = $_POST['email'];
$token = $_SESSION['token'];

$stmt = $db->prepare('SELECT email FROM Users where sessionId=:token');
$stmt->bindValue(':token', $token, SQLITE3_TEXT);
$query = $stmt->execute()->fetchArray();
$test_email = $query['email'];

// check if user is owner of the group
$stmt = $db->prepare('SELECT leader FROM Groups where id=:group_id');
$stmt->bindValue(':group_id', $group_id, SQLITE3_TEXT);
$query = $stmt->execute()->fetchArray();
$owner = $query['leader'];

if ($owner != $test_email) {
    echo json_encode('You are not the owner of this group.');
    die();
}

// check if added user exists
$stmt = $db->prepare('SELECT * FROM Users where email=:email');
$stmt->bindValue(':email', $email, SQLITE3_TEXT);
$stmt->bindValue(':group_id', $group_id, SQLITE3_TEXT);
$query = $stmt->execute()->fetchArray();

if (empty($query)) {
    echo json_encode('User ' . h($email) . ' does not exist.');
    die();
}

// check if request was already sent
$stmt = $db->prepare('SELECT * FROM Requests where email=:email AND groupId=:group_id');
$stmt->bindValue(':email', $email, SQLITE3_TEXT);
$stmt->bindValue(':group_id', $group_id, SQLITE3_TEXT);
$query = $stmt->execute()->fetchArray();

if (!empty($query)) {
    echo json_encode('Request already sent.');
    die();
}

// check if user is already in group
$stmt = $db->prepare('SELECT * FROM Memberships where email=:email AND groupId=:group_id');
$stmt->bindValue(':email', $email, SQLITE3_TEXT);
$stmt->bindValue(':group_id', $group_id, SQLITE3_TEXT);
$query = $stmt->execute()->fetchArray();

if (!empty($query)) {
    echo json_encode('User already in group.');
    die();
}

// insert request into database
$stmt = $db->prepare('INSERT INTO Requests VALUES(:id, :group_id, :email);');
$stmt->bindValue(':id', create_token(), SQLITE3_TEXT);
$stmt->bindValue(':group_id', $group_id, SQLITE3_TEXT);
$stmt->bindValue(':email', $email, SQLITE3_TEXT);
$stmt->execute();

// mail($email, 'Join group request', 'You have been invited by ' . $owner . ' to join a group.');

echo json_encode('success');
