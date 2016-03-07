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
<!--ОВЕРЛЕЙ БЕГИн-->
<div id="overlay"></div>
<!--ОВЕРЛЕЙ ЭНД-->

<div class="body_container">
 <div class="main_case">
     <br>
     <div id="text_box">
        <h3>Бесплатная доска бартерных объявлений</h3>
        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum...</p>
     </div>
     <br>
     <div class="button_box">
         <button class='agree_button' id="suggest_btn">Разместить объявление</button>
         <button class='agree_button' id="find_btn">Поиск по объявлениям</button>
      </div>
     <div id="find_area">
      <!--  <button class='agree_button' id="find_btn">Поиск по объявлениям</button>-->
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
                <br>
                <label>Keywords</label>
                <input type="text" id="description_find" autocomplete="off"/>
                <br>
                <label>Region</label>
                <select id="region_find">
                    <option value="0">Вся Россия</option>
                    <?php region_selection() ?>
                </select>
                <br>
                <label>City</label>
                <input list="city_find" id="city_selected_find" autocomplete="off">
                <datalist id="city_find">
                </datalist>
                <br>
                <div class="button_box">
                    <input type="submit" class="functional_button" value="Искать"/>
                    <input type="button" class="functional_button" id="close_find" value="Прекратить поиск" style="display: none"/>
                 </div>
            </form>
        </div>
        <br>
    </div>
     <br>
    <div id="suggest_area">
        <!--ФОРМА ДОБАВЛЕНИЯ ОБЪЯВЛЕНИЯ В ОКНЕ-->
        <div id="suggest_div" class="modal_div" style="width:900px">
            <span class="modal_close">x</span>
            <h2>Ваше объявление</h2>
            <form id="suggest_form">
                <table id="suggest_table">
                    <!--<tr>
                        <th></th>
                        <th></th>
                        <th></th>
                    </tr>-->
                    <!--Первая Строка-->
                    <tr>
                        <td style="width:33%"><label>Хочу обменять</label><br>
                            <select id="from_topics_of_barter_suggest">
                                <?php include_once("../main.php"); barter_topics(); ?>
                            </select>
                        </td>
                        <td style="width:33%"></td>
                        <td style="width:33%">
                            <label>На</label><br>
                            <select id="to_topics_of_barter_suggest">
                                <?php barter_topics(); ?>
                            </select>
                        </td>
                    </tr>
                    <!--Вторая, третья, четвертая Строки-->
                    <tr>
                        <td>
                            <label>Заголовок объявления</label>
                            <input type="text" id="title_suggest" required autocomplete="off"/>
                        </td>
                        <td colspan="2" rowspan="3">
                            <label>Описание</label><br>
                           <!-- <input type="text" id="description_suggest" autocomplete="off" required/>-->
                            <textarea id="description_suggest"></textarea>
                        </td>
                        <!--<td></td>-->
                    </tr>
                    <tr>
                        <td>
                            <label for="contacts_suggest">Контакты</label><br>
                            <input type="text" id="contacts_suggest" autocomplete="off" required/>
                        </td>
                        <!--<td></td>
                        <td></td>-->
                    </tr>
                    <tr>
                        <td><label for="price_suggest">Цена</label><br>
                            <input type="number" id="price_suggest" autocomplete="off" required/></td>
                        <!--<td></td>
                        <td></td>-->
                    </tr>
                    <!--Пятая строка-->
                    <tr>
                        <td>
                            <label for="region_suggest">Регион</label><br>
                            <select id="region_suggest">
                                <?php region_selection()?>
                            </select>
                        </td>
                        <td>
                            <label>Город</label><br>
                            <input list="city_suggest" id="city_selected_suggest" required autocomplete="off">
                            <datalist id="city_suggest" autocomplete="off">
                            </datalist>
                        </td>
                        <td>
                            <div class="functional_button" style="height: 24px;  border-radius:4px;   width: 290px; margin-top: 18px;overflow: hidden;"><input type="file"   id="file_input" name="my-file"/></div>

                        </td>

                    </tr>

                </table>
                <div id="upload_pic" class="pictures_box">

                </div>
                <br><br>
                <input class="agree_button" style="margin-left: 0; border-radius:4px;  height:30px; width: 360px;" type="submit" value="Разместить объявление"/>
                <input type="button" id="clear_btn"  class="functional_button" style="height:30px; border-radius:4px;" value="Убрать фотографии"/>
            </form>
        </div>
    </div>
    <div id="search_area"></div>


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
