<!DOCTYPE html>
<head>
	<meta charset="utf-8" />
	<script type="text/javascript" src="js/jquery-2.1.1.js"></script>
    <script type="text/javascript" src="js/jquery.the-modal.js"></script>
    <script type="text/javascript" src="js/handler.js"></script>
    <link rel="stylesheet" type="text/css" href="css/the-modal.css" media="all">
    <link rel="stylesheet" type="text/css" href="css/style.css" media="all">
</head>
<body>
    <div id="find_area">
        <button id="find_btn">find an advertisement</button>
        <div id="find_form_div" style="display: none">
            <form id="find_form">
                <br>From
                <select id="from_topics_of_barter_find">
                    <?php
                        include("main.php");
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
                <input type="submit" id="submit_find"/>
            </form>
        </div>
        <br>
    </div>
    <div id="suggest_area">
        <button id="suggest_btn">Suggest an advertisment</button>
        <div id="suggest_div" style="display: none">
            <form id="suggest_form">
                <br>From
                <select id="from_topics_of_barter_suggest">
                    <?php barter_topics(); ?>
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
                <br>Name
                <input type="text" id="name_suggest" autocomplete="off" required/>
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
                <input type="submit" id="submit_suggest"/>
            </form>
        </div>
    </div>

    <!--sign in && sign up-->
    <div>
        <!--<button id="show_sign_in"></button>-->
        <div id="hidden_sign_in_form" style="">
            <h2>Авторизация</h2>
            <form action="sign_in.php" method="post">
                <label for="login">Логин</label>
                <input type="text" name="login"  id='login' autocomplete="off" required>
                <br><br>
                <label for="password">Пароль</label>
                <input type="password" name="password" id='password' autocomplete="off" required>
                <br><br>

                <input type="submit" name="hidden_sign_in_form" value="Авторизоваться">
            </form>
        </div>
    </div>

    <div>
        <!--<button id="show_sign_up"></button>-->
        <div id="hidden_sign_up_form" style="">
            <h2>Регистрация</h2>
            <form action="sign_up.php" method="post">
                <label for="username">Имя</label>
                <input type="text" name="username" id="username" placeholder="" required>
                <br><br>
                <label for="login">Логин</label>
                <input type="text" name="login"  id='login' autocomplete="off" required>
                <br><br>
                <label for="email">E-mail</label>
                <input type="email" name="email"  id='email' autocomplete="off" required>
                <br><br>
                <label for="password">Пароль</label>
                <input type="password" name="password" id='password' autocomplete="off" required>
                <br><br>
                <label for="password_check">Повторите пароль</label>
                <input type="password" name="password_check" id="password_check" autocomplete="off" required>
                <br><br>

                <input type="submit" name="submit_sign_up" value="Зарегистрироваться">
            </form>
        </div>
    </div>
   <!-- <button id="advertisment-open">place an advertisement</button>

    <div class="modal" id="advertisment" style="display: none">
        <a href="#" class="close">&times;</a>
        test your luck and might
    </div>-->
</body>
