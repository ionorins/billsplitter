<?php
include 'init.php';
$token = $_SESSION['token'];
$stmt = $db->prepare('UPDATE Users SET sessionId=null WHERE sessionId=:token');
$stmt->bindValue(':token', $token, SQLITE3_TEXT);
$stmt->execute();
unset($_SESSION['token']);

// -----------------------------------
// from stackoverflow.com/questions/16759719/i-just-cant-destroy-a-session-in-php/43167905
session_unset();
session_destroy();
session_write_close();
setcookie(session_name(), '', 0, '/');
session_regenerate_id(true);
// -----------------------------------

header('Location: index.php');
die();
