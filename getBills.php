<?php
// returns user's bills
include 'init.php';
// get session id and user's email
$token = $_SESSION['token'];

$stmt = $db->prepare('SELECT email FROM Users where sessionId=:token');
$stmt->bindValue(':token', $token, SQLITE3_TEXT);
$query = $stmt->execute()->fetchArray();
$email = $query['email'];

// fetch bills
$stmt = $db->prepare('SELECT id, payee, payer, amount, confirmedPayee, confirmedPayer, description
                        FROM Bills where payee=:email OR payer=:email');
$stmt->bindValue(':email', $email, SQLITE3_TEXT);
$query = $stmt->execute();
$i = 0;

// place bills into 2d array
while ($row = $query->fetchArray(SQLITE3_ASSOC)) {
    $result[$i] = $row;
    $i++;
}

// if no bills were found, return empty list
if ($i == 0) {
    echo '[]';
    die();
}

echo json_encode($result);
