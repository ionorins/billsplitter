<?php
// returns members of group
include 'init.php';
// get session id, request parameters and user's email
$token = $_SESSION['token'];
$stmt = $db->prepare('SELECT email FROM Users WHERE sessionId=:token');
$stmt->bindValue(':token', $token, SQLITE3_TEXT);
$query = $stmt->execute()->fetchArray();
$email = $query['email'];
$group_id = $_GET['groupId'];

// fetch users
$stmt = $db->prepare('SELECT Users.email, Users.name FROM Memberships LEFT JOIN Users ON Memberships.email = Users.email WHERE Memberships.groupId=:group_id');
$stmt->bindValue(':group_id', $group_id, SQLITE3_TEXT);
$query = $stmt->execute();
$i = 0;
$member  = false;

// place users into 2d array
while ($row = $query->fetchArray(SQLITE3_ASSOC)) {
    $result[$i] = $row;
    if (in_array($email, $row))
        $member = true;
    $i++;
}

// check if user has acces to this resource
if (!$member) {
    echo json_encode('You do not have access to this resource');
    die();
}

echo json_encode($result);