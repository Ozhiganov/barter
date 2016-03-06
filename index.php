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

</div>
<hr>
<div id="overlay"></div>
<div class="body_container">
 <div class="main_case">
     <br>
    <div id="find_area">
        <button id="find_btn">find an advertisement</button>
        <div id="find_form_div" style="display: none">
            <form id="find_form">
                <br>From
                <select id="from_topics_of_barter_find">
                    <?php
                    include_once("main.php");
                    barter_topics();
                    ?>
                </select>
                <br>To
                <select id="to_topics_of_barter_find">
                    <?php barter_topics(); ?>
                </select>
                <br>Keywords
                <input type="text" id="description_find" autocomplete="off"/>
                <br>Region
                <select id="region_find">
                    <option value="0">Вся Россия</option>
                    <?php region_selection() ?>
                </select>
                <br>City
                <input list="city_find" id="city_selected_find" autocomplete="off">
                <datalist id="city_find" autocomplete="off">
                </datalist>
                <br>
                <input type="submit" value="search"/>
                <input type="button" id="close_find" value="close search" style="display: none"/>
            </form>
        </div>
        <br>
    </div>
    <div id="suggest_area">
        <button id="suggest_btn">Suggest an advertisment</button>
        <div id="suggest_div" class="modal_div">
            <span class="modal_close">X</span>
            <form id="suggest_form">
                <br>From
                <select id="from_topics_of_barter_suggest">
                    <?php include_once("../main.php"); barter_topics(); ?>
                </select>
                <br>To
                <select id="to_topics_of_barter_suggest">
                    <?php barter_topics(); ?>
                </select>
                <br>Title
                <input type="text" id="title_suggest" required autocomplete="off"/>
                <br>Description
                <input type="text" id="description_suggest" autocomplete="off" required/>
                <br>Contacts
                <input type="text" id="contacts_suggest" autocomplete="off" required/>
                <br>Price
                <input type="number" id="price_suggest" autocomplete="off" required/>
                <br>Region
                <select id="region_suggest">
                    <?php region_selection() ?>
                </select>
                <br>City
                <input list="city_suggest" id="city_selected_suggest" required autocomplete="off">
                <datalist id="city_suggest" autocomplete="off">
                </datalist>
                <br><br>
                <input type="file" id="file_input" name="my-file"/>
                <input type="button" id="clear_btn" value="clear"/>
                <br>
                <div id="upload_pic"></div>
                <br><br>
                <input type="submit"/>
            </form>
        </div>
    </div>
    <div id="search_area"></div>
    <!--sign in && sign up-->
    <div>
        <button id="show_sign_in">Войти</button>
        <div id="hidden_sign_in_form" class="modal_div" style="">
            <span class="modal_close">X</span>
            <h2>Авторизация</h2>
            <form id="sign_in_form" method="post">
                <label for="login">Логин</label>
                <input type="text" name="login"  id='login' autocomplete="off" required>
                <br><br>
                <label for="password">Пароль</label>
                <input type="password" name="password" id='password' autocomplete="off" required>
                <br><br>

                <input type="submit" name="hidden_sign_in_form" value="Авторизоваться">
            </form>
        </div>
        <button id="sign_out" style="display: none">Выйти</button>
    </div>

    <div>
        <button id="show_sign_up">Регистрация</button>
        <div id="hidden_sign_up_form" class="modal_div" style="">
            <span class="modal_close">X</span>
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

                    <input type="submit" name="submit_sign_up" value="Зарегистрироваться">
                    <span class='msg'><?php echo $message; ?></span>
                </form>
            </div>
        </div>
 </div>

</div>
</body>
