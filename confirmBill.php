<?php
include 'init.php';
$bill_id = $_GET['billId'];
$token = $_SESSION['token'];
$success = false;

$stmt = $db->prepare('SELECT email FROM Users where sessionId=:token');
$stmt->bindValue(':token', $token, SQLITE3_TEXT);
$query = $stmt->execute()->fetchArray();
$email = $query['email'];

$stmt = $db->prepare('SELECT payee, payer FROM Bills where id=:bill_id');
$stmt->bindValue(':bill_id', $bill_id, SQLITE3_TEXT);
$query = $stmt->execute()->fetchArray();

if ($email == $query['payee']) {
    $stmt = $db->prepare('UPDATE Bills SET confirmedPayee=1 WHERE id=:bill_id');
    $stmt->bindValue(':bill_id', $bill_id, SQLITE3_TEXT);
    $stmt->execute();
    $success = true;
}

if ($email == $query['payer']) {
    $stmt = $db->prepare('UPDATE Bills SET confirmedPayer=1 WHERE id=:bill_id');
    $stmt->bindValue(':bill_id', $bill_id, SQLITE3_TEXT);
    $stmt->execute();
    $success = true;
}

$stmt = $db->prepare('SELECT confirmedPayee, confirmedPayer FROM Bills WHERE id=:bill_id');
$stmt->bindValue(':bill_id', $bill_id, SQLITE3_TEXT);
$query = $stmt->execute()->fetchArray();

if ($query['confirmedPayee'] == 1 && $query['confirmedPayee'] == 1) {
    $stmt = $db->prepare('DELETE FROM Bills WHERE id=:bill_id');
    $stmt->bindValue(':bill_id', $bill_id, SQLITE3_TEXT);
    $stmt->execute()->fetchArray();
}

if ($success) {
    echo json_encode('success');
    die();
}


echo json_encode('You do not have access to this resource.');
