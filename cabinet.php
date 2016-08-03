<?php
require_once "api/db_connection.php";
if(!isset($_COOKIE['id'])) {
    echo("<html><script>window.location = 'index.php'</script></html>");
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <script type="text/javascript" src="js/jquery-2.1.1.js"></script>
    <script type="text/javascript" src="js/jquery.damnUploader.js"></script>
    <script type="text/javascript" src="js/handler.js"></script>
    <script type="text/javascript" src="js/jquery.damnUploader.js"></script>
    <link rel="stylesheet" type="text/css" href="css/mainstyle.css" media="all">
    <link rel="stylesheet" type="text/css" href="css/modal.css" media="all">
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
<div class="body_container">
    <div class="main_case">
        <h2>Ваш кабинет</h2>
        <br>
        <?php
        $find_query = "SELECT `id`,`media`,`title`,`publish_date`,`city`,`region`  FROM `advertisements` WHERE `user_id`='$_COOKIE[id]' ORDER BY `publish_date` DESC";

        $find_result = $db->query($find_query);
        $find_result_array = $find_result->fetch_all(MYSQLI_ASSOC);
        if(count($find_result_array) == 0)
            echo "<span>У вас нет объявлений</span>";
        else {
            setlocale(LC_ALL, "Russian");
            foreach ($find_result_array as $val) {
                $media = preg_split("/[,]+/", $val[media]);

                echo "
            <a href=advertisement_page.php?id=" . $val[id] . "><h3>" . $val[title] . "</h3></a>
        <div class='pictures_box'>

        <img src='$media[0]'/></div>
        <div style='
        width: 400px;
        height: 200px;'>
             <p>
                Дата: " . strftime("%d.%m.%Y %H:%M", $val[publish_date]) . "<br></p>
        </div>
        <hr>";
            }
        }
        ?>
    </div>
</div>
</body>
</html>
