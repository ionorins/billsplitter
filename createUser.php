<?php
// creates new user
include 'init.php';
// get rewuest parameters
$email = $_POST['email'];
$name  = $_POST['name'];
$pass  = $_POST['password'];

// check if email is valid
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode('Email is not valid.');
    die();
}

// check if password is long enough
if (strlen($pass) < 8) {
    echo json_encode('Password is too short.');
    die();
}

// check if user has already created an account
$stmt = $db->prepare('SELECT email FROM Users where email=:email');
$stmt->bindValue(':email', $email, SQLITE3_TEXT);
$query = $stmt->execute()->fetchArray();
if (!empty($query)) {
    echo json_encode('User ' . h($email) . ' is already registered.');
    die();
}

// hash and salt password
$salt = sha1(time());
$encrypted_pass = sha1($salt . '--' . $pass);

// insert user into the database
$stmt = $db->prepare('INSERT INTO Users VALUES(:email, :name, :pass, :salt, :token);');
$stmt->bindValue(':email', $email, SQLITE3_TEXT);
$stmt->bindValue(':name', $name, SQLITE3_TEXT);
$stmt->bindValue(':pass', $encrypted_pass, SQLITE3_TEXT);
$stmt->bindValue(':salt', $salt, SQLITE3_TEXT);
$stmt->bindValue(':token', null, SQLITE3_NULL);
$stmt->execute();

echo json_encode('success');
