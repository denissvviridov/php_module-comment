<?php error_reporting(E_ALL);
if (session_id() == '') {
    session_start();
}/* Проверяем наличие базы данных и наличие в ней админа */
try {
    $dsn = new PDO('mysql:host=localhost;dbname=beseder', 'root', 'qwerty12345');
    $dsn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $dsn->exec('SET NAMES "utf8"');
} catch (PDOException $e) {/* Мы здесь т.к. базы нет поэтому Создаем базу */
    include_once $_SERVER['DOCUMENT_ROOT'] . '/chat/admin/createbase.php';
}/* Создаем админа *//* Проверяем передавались ли данные формы на установку админа *//* Если ДА создаем админа */
if (isset($_POST['action']) and $_POST['action'] == 'start') {
    include_once $_SERVER['DOCUMENT_ROOT'] . '/chat/admin/users/createadmin.php';
    header('Location:.');
    exit('контроллер 1 очистите кэш');
}
try {
    $count = $dsn->query("SELECT count(1) FROM users")->fetchColumn();
    if ($count <= 0) {
        include_once $_SERVER['DOCUMENT_ROOT'] . '/chat/admin/users/form_create_admin.php';
        exit('controller 1: Нет админа ');
    }
} catch (PDOException $e) {
    exit('Ошибка на первом входе в админку controller 1');
}


