<?php
// returns group join requests sent to user
include 'init.php';
// get session id and user's email
$token = $_SESSION['token'];
$stmt = $db->prepare('SELECT email FROM Users where sessionId=:token');
$stmt->bindValue(':token', $token, SQLITE3_TEXT);
$query = $stmt->execute()->fetchArray();
$email = $query['email'];

// fetch requests
$stmt = $db->prepare('SELECT Requests.id, leader, name FROM Requests LEFT JOIN Groups ON Requests.groupId = Groups.id where Requests.email=:email');
$stmt->bindValue(':email', $email, SQLITE3_TEXT);
$query = $stmt->execute();
$i = 0;

// place requests into 2d array
while ($row = $query->fetchArray(SQLITE3_ASSOC)) {
    $result[$i] = $row;
    $i++;
}

// if no requests were found, return empty list
if ($i == 0) {
    echo '[]';
    die();
}

echo json_encode($result);
