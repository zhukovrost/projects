<?php

# Host settings

define("HOSTNAME", "localhost");
define("HOSTUSER", "root");
define("HOSTPASSWORD", "");
define("HOSTDB", "english");


date_default_timezone_set("Europe/Moscow");
$title = "Roman/24";

$conn = new mysqli(HOSTNAME, HOSTUSER, HOSTPASSWORD, HOSTDB);
conn_check($conn);

session_start();

if (isset($_SESSION["login"])){
    $user_data = get_user_data($conn, $_SESSION['login']);
}
else{
    $user_data = array(
        "auth" => false
    );
}

?>