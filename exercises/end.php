<?php

include "../templates/func.php";
include '../templates/settings.php';

$conn = new mysqli(HOSTNAME, HOSTUSER, HOSTPASSWORD, HOSTDB);
conn_check($conn);

check_the_login('../');
$login = $_COOKIE['login'];

$update_sql = "UPDATE users SET program=0, program_duration=0, calendar='[]', start_program=0, completed_program=0 WHERE login='".$login."'";
if ($conn->query($update_sql)){
    $insert_sql = "INSERT INTO news(new_id, user, date, personal) VALUES (3, '".$login."', '".time()."', 0)";
    $conn->query($insert_sql);
    header('Location: ../account.php');
}

?>