<?php
include 'init.php';
$payer = $_POST['payer'];
$ammount = floatval($_POST['ammount']);
$token = $_SESSION['token'];

$stmt = $db->prepare('SELECT email FROM Users where sessionId=:token');
$stmt->bindValue(':token', $token, SQLITE3_TEXT);
$query = $stmt->execute()->fetchArray();
$email = $query['email'];

$stmt = $db->prepare('SELECT email FROM Users where email=:payer');
$stmt->bindValue(':payer', $payer, SQLITE3_TEXT);
$query = $stmt->execute()->fetchArray();

if (empty($query)) {
    echo json_encode('User ' . h($payer) . ' does not exist.');
    die();
}

$stmt = $db->prepare('INSERT INTO Bills VALUES(:id, :payee, :payer, :ammount, :confirmedPayee, :confirmedPayer);');
$stmt->bindValue(':id', create_token(), SQLITE3_TEXT);
$stmt->bindValue(':payee', $email, SQLITE3_TEXT);
$stmt->bindValue(':payer', $payer, SQLITE3_TEXT);
$stmt->bindValue(':ammount', $ammount, SQLITE3_FLOAT);
$stmt->bindValue(':confirmedPayee', 0, SQLITE3_INTEGER);
$stmt->bindValue(':confirmedPayer', 0, SQLITE3_INTEGER);
$stmt->execute();

mail ($payer, 'New bill', 'You have received a new bill from ' . $email . ' worth £' . $ammount . '.');

echo json_encode('success');
