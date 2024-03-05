<?php
if (session_id() == '') {
    session_start();
}/* Проверяем была ли нажата кнопка "Выйти" если 'Да' уничтожаем сессию */
if (isset($_POST['action']) and $_POST['action'] == 'Выйти') {
    unset($_SESSION['loggedIn']);
    unset($_SESSION['login']);
    unset($_SESSION['password']);
}
header("Location: " . $_SERVER["HTTP_REFERER"]);


