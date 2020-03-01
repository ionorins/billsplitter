<?php
include 'init.php';
$token = $_SESSION['token'];
$stmt = $db->prepare('SELECT email FROM Users WHERE sessionId=:token');
$stmt->bindValue(':token', $token, SQLITE3_TEXT);
$query = $stmt->execute()->fetchArray();
$email = $query['email'];

$stmt = $db->prepare('SELECT leader, Groups.id, name FROM Groups LEFT JOIN Memberships ON Groups.id = Memberships.groupId WHERE Memberships.email=:email');
$stmt->bindValue(':email', $email, SQLITE3_TEXT);
$query = $stmt->execute();
$i = 0;

while ($row = $query->fetchArray(SQLITE3_ASSOC)) {
    $result[$i] = $row;
    $i++;
}

if ($i == 0) {
    echo '[]';
    die();
}

echo json_encode($result);
