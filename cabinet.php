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
    <title>Ваш кабинет</title>
    <script type="text/javascript" src="js/jquery-2.1.1.js"></script>
    <script type="text/javascript" src="js/handler.js"></script>
    <link rel="stylesheet" type="text/css" href="css/mainstyle.css" media="all">
</head>
<body>
<div id="header">
    <div class="header_case">
        <div id="center">

            <div id="logotype">

            </div>

            <div id="tools">
            </div>

        </div>
    </div>
</div>
<hr>

    <h2>Ваш кабинет</h2>
    <br>
    <a href="?act=logout">
        <button>Выйти</button>
    </a>


</body>
</html>
