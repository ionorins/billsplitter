<?php
include 'init.php';
$token = session_id();
$pass = $_POST['password'];
$new_pass = $_POST['newPassword'];
$name = $_POST['name'];
$stmt = $db->prepare('SELECT salt, password FROM Users where sessionId=:token');
$stmt->bindValue(':token', $token, SQLITE3_TEXT);
$query = $stmt->execute()->fetchArray();

$salt = $query['salt'];
$encrypted_password = sha1($salt . '--' . $pass);

if ($encrypted_password == $query['password']) {
    if (strlen($new_pass) < 8) {
        echo json_encode('Password is too short.');
        die();
    }

    $salt = sha1(time());
    $encrypted_pass = sha1($salt . '--' . $new_pass);

    $stmt = $db->prepare('UPDATE Users SET password=:pass, salt=:salt WHERE sessionId=:token');
    $stmt->bindValue(':pass', $encrypted_pass, SQLITE3_TEXT);
    $stmt->bindValue(':salt', $salt, SQLITE3_TEXT);
    $stmt->bindValue(':token', $token, SQLITE3_TEXT);
    $stmt->execute();
    echo json_encode('success');
} else
    echo json_encode('Password is incorrect.');
