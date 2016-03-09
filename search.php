<!DOCTYPE html>
<head>
    <meta charset="utf-8" />
    <script type="text/javascript" src="js/jquery-2.1.1.js"></script>
    <script type="text/javascript" src="js/jquery.damnUploader.js"></script>
    <script type="text/javascript" src="js/handler.js"></script>
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
                <a id="show_sign_up">Зарегистрироваться</a> |
                <a id="show_sign_in">Войти</a>
                <a id="sign_out">Выйти</a>
            </div>

        </div>
    </div>
</div>
<hr>
<div id="overlay"></div>
<div class="body_container">
    <div class="main_case">
    <form id="find_form"  method="GET" action="search.php">
        <br><br>
        <table id="find_table">
            <tr>
                <th style="width:30%"></th>
                <th  style="width:5%"></th>
                <th  style="width:30%"></th>
            </tr>
            <tr>
                <td>
                    <label>Меняю</label>
                    <select id="from_topics_of_barter_find" name="from">
                        <?php include_once("main.php");
                        barter_topics(); ?>
                    </select>
                </td>
                <td></td>
                <td>
                    <label for="to_topics_of_barter_find">На</label>
                    <select id="to_topics_of_barter_find" name="to">
                        <?php barter_topics(); ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td colspan="3">
                    <label for="description_find">Ключевые слова</label>
                    <input name="keywords" type="text" id="description_find" autocomplete="off"/>
                </td>
            </tr>
            <tr>
                <td>
                    <label>Регион обмена</label>
                    <select id="region_find" name="region">
                        <option value="0">Вся Россия</option>
                        <?php region_selection() ?>
                    </select>
                </td>
                <td></td>
                <td>
                    <label>Город</label>
                    <input name="city" list="city_find" id="city_selected_find" autocomplete="off">
                    <datalist id="city_find">
                    </datalist>
                </td>
            </tr>
        </table>
        <br>
        <br><br>
        <div class="button_box">
            <input type="submit" class="functional_button" value="Искать"/>
        </div>
    </form>
    <div id="search_area">
    <?php
    $find_db = new mysqli("barter", "root", "", "barter_main");
    if ($find_db->connect_errno) {
        exit();
    }
    $find_query = "SELECT `id`,`media`,`title`,`publish_date`,`city`,`region`  FROM `advertisements` WHERE `suggest_from`='$_GET[from]' AND `suggest_to`='$_GET[to]'";

    $from_topic_req = $find_db->query("SELECT `name` FROM `topics` WHERE `id`='$_GET[from]' LIMIT  0,1");
    $from_topic = $from_topic_req->fetch_all(MYSQLI_ASSOC);
    $to_topic_req = $find_db->query("SELECT `name` FROM `topics` WHERE `id`='$_GET[to]' LIMIT  0,1");
    $to_topic = $to_topic_req->fetch_all(MYSQLI_ASSOC);

    echo "<script>
                $('#from_topics_of_barter_find').val('$_GET[from]');
                $('#to_topics_of_barter_find').val('$_GET[to]');
          </script>";
    if(strnatcasecmp("", $_GET['keywords']) != 0) {
        $find_query .= " AND LOWER(title) LIKE '%" . strtolower($_GET['keywords']) . "%'";
        echo "<script>$('#description_find').val('$_GET[keywords]')</script>";
    }
    if($_GET['region'] != 0) {
        $find_query .= " AND `region`='$_GET[region]'";
        $region_query = $find_db->query("SELECT `name` FROM `regions` WHERE `id`='$_GET[region]' LIMIT 0,1");
        $region_array = $region_query->fetch_all(MYSQLI_ASSOC);
        $region_name = $region_array[0][name];
        echo "<script>$('#region_find').val('$_GET[region]')</script>";
        if(strnatcasecmp("", $_GET['city']) != 0) {
            echo "<script>$('#city_selected_find').val('$_GET[city]')</script>";
            $city_query = $find_db->query("SELECT `id` FROM `cities` WHERE `name`='$_GET[city]' AND `region_id`='$_GET[region]' LIMIT 0,1");
            $city_array = $city_query->fetch_all(MYSQLI_ASSOC);
            $city_id = $city_array[0][id];
            $find_query .= " AND city='$city_id'";
            $city_name = $_GET[city];
        }
        else
            $city_name = null;
    }
    else {
        $region_name = null;
        $city_name = null;
    }
    $find_query .= "ORDER BY `publish_date` DESC";
    $find_result = $find_db->query($find_query);
    $find_result_array = $find_result->fetch_all(MYSQLI_ASSOC);
    setlocale(LC_ALL, "Russian");
    foreach ($find_result_array as $val) {
        if($region_name == null) {
            $current_region_req = $find_db->query("SELECT `name` FROM `regions` WHERE `id`='$val[region]' LIMIT 0,1");
            $current_region_arr = $current_region_req->fetch_all(MYSQLI_ASSOC);
            $current_region = $current_region_arr[0][name];
        }
        else
            $current_region = $region_name;
        if($city_name == null) {
            $current_city_req = $find_db->query("SELECT `name` FROM `cities` WHERE `id`='$val[city]' LIMIT 0,1");
            $current_city_arr = $current_city_req->fetch_all(MYSQLI_ASSOC);
            $current_city = $current_city_arr[0][name];
        }
        else
            $current_city = $city_name;
        echo "<div>
                <a href=advertisement_page.php?id=" . $val[id] . "><h3>" . $val[title] . "</h3></a>
                <p>Меняю: " . $from_topic[0][name] . "<br>
                На: " . $to_topic[0][name] . "<br>
                Регион: " . $current_region . "<br>
                Город: " . $current_city . "<br>
                Дата: " . strftime("%d.%m.%Y %H:%M", $val[publish_date]) . "<br></p>
                <div class='pictures_box'>";
        $media = preg_split("/[,]+/",$val[media]);
        echo "<img src='$media[0]'/></div></div><hr>";
    }
    $find_db->close();
    ?>
    </div>
    <!--ФОРМА АВТОРИЗАЦИИ В ОКНЕ-->
    <div id="hidden_sign_in_form" class="modal_div" style="">
        <span class="modal_close">x</span>
        <h2>Вход</h2>
        <form id="sign_in_form" method="post">
            <p>
                введите данные, указанные при регистрации в поля, расположенные ниже
            </p>
            <label for="login">Логин</label><br>
            <input type="text" name="login"  id='login' autocomplete="off" required>
            <br><br>
            <label for="password">Пароль</label><br>
            <input type="password" name="password" id='password' autocomplete="off" required>
            <br><br>
            <br>
            <input type="submit" class="agree_button_mini" style=""  name="hidden_sign_in_form" value="Авторизоваться">
        </form>
    </div>




    <!--ФОРМА РЕГИСТРАЦИИ В ОКНЕ-->
    <div id="hidden_sign_up_form"  class="modal_div" style="top: 15%; width:270px; ">
        <span class="modal_close" style="right: 20px;">x</span>
        <h2>Регистрация</h2>
        <form id="sign_up_form">
            <label for="username">Имя</label><br>
            <input type="text" name="username" id="username" placeholder="как к вам обращаться? :)" autocomplete="off" required>
            <br><br>
            <label for="login">Логин</label><br>
            <input type="text" name="login"  id='reg_login' autocomplete="off" required>
            <br><br>
            <label for="email">Ваш email | электронная почта</label><br>
            <input type="email" name="email"  id='email' autocomplete="off" placeholder="" required>
            <br><br>
            <label for="password">Пароль</label><br>
            <input type="password" name="password" id='reg_password' autocomplete="off" required>
            <br><br>
            <label for="password_check">И ещё раз пароль</label><br>
            <input type="password" name="password_check" id="password_check" autocomplete="off" required>
            <br><br>
            <br>
            <input type="submit" class="agree_button_mini" name="submit_sign_up" value="Зарегистрироваться">
            <span class='msg'><?php echo $message; ?></span>
        </form>
    </div>
</div>
    </div>
</body>