<?php
session_start();
require_once 'security_headers.php';
session_destroy();
header("Location: index.php");
exit();
?>

