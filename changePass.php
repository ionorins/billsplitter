<?php
// changes the password of the user
include 'init.php';
// get session id and parameters of the request
$token = session_id();
$pass = $_POST['password'];
$new_pass = $_POST['newPassword'];

$stmt = $db->prepare('SELECT salt, password FROM Users where sessionId=:token');
$stmt->bindValue(':token', $token, SQLITE3_TEXT);
$query = $stmt->execute()->fetchArray();

// check if old password matches
$salt = $query['salt'];
$encrypted_password = sha1($salt . '--' . $pass);

if ($encrypted_password == $query['password']) {
    // check if new password is long enough
    if (strlen($new_pass) < 8) {
        echo json_encode('Password is too short.');
        die();
    }

    // hash and salt new password
    $salt = sha1(time());
    $encrypted_pass = sha1($salt . '--' . $new_pass);

    // insert it into the database
    $stmt = $db->prepare('UPDATE Users SET password=:pass, salt=:salt WHERE sessionId=:token');
    $stmt->bindValue(':pass', $encrypted_pass, SQLITE3_TEXT);
    $stmt->bindValue(':salt', $salt, SQLITE3_TEXT);
    $stmt->bindValue(':token', $token, SQLITE3_TEXT);
    $stmt->execute();
    echo json_encode('success');
} else
    echo json_encode('Password is incorrect.');
