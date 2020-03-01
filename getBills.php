<?php
include 'init.php';
$token = $_SESSION['token'];

$stmt = $db->prepare('SELECT email FROM Users where sessionId=:token');
$stmt->bindValue(':token', $token, SQLITE3_TEXT);
$query = $stmt->execute()->fetchArray();
$email = $query['email'];

$stmt = $db->prepare('SELECT id, payee, payer, ammount, confirmedPayee, confirmedPayer
                        FROM Bills where payee=:email OR payer=:email');
$stmt->bindValue(':email', $email, SQLITE3_TEXT);
$query = $stmt->execute();
$i = 0;

while ($row = $query->fetchArray(SQLITE3_ASSOC)) {
    $result[$i] = $row;
    $i++;
}

echo json_encode($result);
