<?php
include 'init.php';
$email = $_POST['email'];
$pass = $_POST['password'];
$stmt = $db->prepare('SELECT salt, password FROM Users where email=:email');
$stmt->bindValue(':email', $email, SQLITE3_TEXT);
$query = $stmt->execute()->fetchArray();

if (empty($query)) {
    echo json_encode('Email or password is incorrect.');
    die();
}

$salt = $query['salt'];
$encrypted_password = sha1($salt . '--' . $pass);

if ($encrypted_password == $query['password']) {
    $token = session_id();
    $_SESSION['token'] = $token;
    $stmt = $db->prepare('UPDATE Users SET sessionId=:token WHERE email=:email');
    $stmt->bindValue(':email', $email, SQLITE3_TEXT);
    $stmt->bindValue(':token', $token, SQLITE3_TEXT);
    $stmt->execute();
    echo json_encode('success');
} else
    echo json_encode('Email or password is incorrect.');
