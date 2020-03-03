<?php
// adds bill
include 'init.php';

// get session token and request parameters
$payer = $_POST['payer'];
$description = $_POST['description'];
$amount = floatval($_POST['amount']);
$token = $_SESSION['token'];

$stmt = $db->prepare('SELECT email FROM Users where sessionId=:token');
$stmt->bindValue(':token', $token, SQLITE3_TEXT);
$query = $stmt->execute()->fetchArray();
$email = $query['email'];

// check if user exists
$stmt = $db->prepare('SELECT email FROM Users where email=:payer');
$stmt->bindValue(':payer', $payer, SQLITE3_TEXT);
$query = $stmt->execute()->fetchArray();

if (empty($query)) {
    echo json_encode('User ' . h($payer) . ' does not exist.');
    die();
}

// insert bill into db
$stmt = $db->prepare('INSERT INTO Bills VALUES(:id, :payee, :payer, :amount, :confirmedPayee, :confirmedPayer, :description);');
$stmt->bindValue(':id', create_token(), SQLITE3_TEXT);
$stmt->bindValue(':payee', $email, SQLITE3_TEXT);
$stmt->bindValue(':payer', $payer, SQLITE3_TEXT);
$stmt->bindValue(':amount', $amount, SQLITE3_FLOAT);
$stmt->bindValue(':confirmedPayee', 0, SQLITE3_INTEGER);
$stmt->bindValue(':confirmedPayer', 0, SQLITE3_INTEGER);
$stmt->bindValue(':description', $description, SQLITE3_TEXT);
$stmt->execute();

// mail ($payer, 'New bill', 'You have received a new bill from ' . $email . ' worth £' . $ammount . '.');

echo json_encode('success');
