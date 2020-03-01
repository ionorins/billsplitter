<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include 'database.php';
$db = new Database();
session_start();

function create_token() {
    return md5(uniqid(rand(), true));
}

function h($string) {
    return htmlspecialchars($string, ENT_QUOTES, 'utf-8');
}
?>
