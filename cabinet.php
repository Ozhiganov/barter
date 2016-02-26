<?php
if(!isset($_COOKIE['id']))
    echo("<html><script>window.location = 'index.php'</script></html>");

if($_GET['act']) {
    $action = $_GET['act'];

    switch($action){
        case 'logout':{
            setcookie("id", "", time() -60*60*12, "/");
            echo("<html><script>window.location = 'index.php'</script></html>"); exit();
            break;}

        default: echo("<html><script>window.location = 'cabinet.php'</script></html>");
    }
}
?>

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
    <a href="?act=logout">
        <button>Выйти</button>
    </a>

</div>
</body>
</html>
