<?php
include '../templates/func.php';
include '../templates/settings.php';

$conn = new mysqli(HOSTNAME, HOSTUSER, HOSTPASSWORD, HOSTDB);
conn_check($conn);

check_the_login("../");

$login = $_COOKIE['login'];

if (empty($_POST['id'])){
  header("Location: my_tests.php");
}
?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <link rel="stylesheet" href="../css/style.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Comfortaa:wght@400;600;700&family=Montserrat+Alternates:ital,wght@0,200;0,400;0,500;0,600;0,700;0,800;0,900;1,300;1,400;1,800&display=swap" rel="stylesheet">
</head>
<body>
<main>

</main>
<?php include "../templates/footer.html"; ?>
</body>
</html>