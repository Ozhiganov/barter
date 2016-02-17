<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <title></title>
</head>
<body>
<div align="center">

    <h2>Личный кабинет</h2>
    <br>
    <a href="sign_out.php">
        <button>Выйти</button>
    </a>

</div>
</body>
</html>

<?php
if(!isset($_COOKIE['id']))
    echo("<html><script>window.location = 'sign_in.php'</script></html>");

$connect_user_db = mysqli_connect('barter', 'root', '', 'main');
if (!$connect_user_db){
    die('Ошибка подключения (' . mysqli_connect_errno() . ') '
        . mysqli_connect_error());
}
else{

}
?>