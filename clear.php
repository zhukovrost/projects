<?php
setcookie("login", "");
$_SESSION = [];
header("Location: index.php");
?>