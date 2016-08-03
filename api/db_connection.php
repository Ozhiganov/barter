<?php
require_once "config.php";
$db  = new mysqli(HOST, DB_USER, DB_PASS, "barter_main");

if ($db->connect_errno) {
    trigger_error("DB connection error");
    //TODO: Handling the connection error
    exit();
}
?>