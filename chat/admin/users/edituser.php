<?php error_reporting(E_ALL);
require_once $_SERVER['DOCUMENT_ROOT'] . '/chat/admin/access.php';/*.................Редактирование пользователя....................*/
if (isset($_POST['action']) and $_POST['action'] == 'Редактировать') {
    include $_SERVER['DOCUMENT_ROOT'] . '/chat/dsn.php';
    try {
        $sql = 'SELECT id, login, email, img FROM users WHERE id = :id';
        $s = $dsn->prepare($sql);
        $s->bindValue(':id', $_POST['id']);
        $s->execute();
    } catch (pdoException $e) {
        $error = 'Ошибка при получении сведений об авторе.';
        include $_SERVER['DOCUMENT_ROOT'] . '/chat/admin/users/error.html.php';
        exit();
    }
    $row = $s->fetch();
    $pageTitle = 'Редактирование профиля';
    $action = 'editform';
    $login = $row['login'];
    $email = $row['email'];
    $id = $row['id'];
    $avatar = $row['img'];
    $button = 'Обновить профиль';
    try {
        $sql = 'SELECT roleid FROM authorrole WHERE authorid = :id';
        $s = $dsn->prepare($sql);
        $s->bindValue(':id', $id);
        $s->execute();
    } catch (pdoException $e) {
        $error = 'Ошибка выборки прав юзера.';
        include $_SERVER['DOCUMENT_ROOT'] . '/chat/admin/users/error.html.php';
        exit();
    }
    $selectedRoles = array();
    foreach ($s as $row) {
        $selectedRoles[] = $row['roleid'];
    }
    try {
        $result = $dsn->query('SELECT id, description FROM role');
    } catch (pdoException $e) {
        $error = 'Ошибка построения списка ролей юзера.';

        include $_SERVER['DOCUMENT_ROOT'] . '/chat/admin/users/error.html.php';
        exit();
    }
    foreach ($result as $row) {
        $roles[] = array('id' => $row['id'], 'description' => $row['description'], 'selected' => in_array($row['id'], $selectedRoles));
    }
    include $_SERVER['DOCUMENT_ROOT'] . '/chat/admin/users/form_edit_user.php';
    exit();
}
if (isset($_GET['editform'])) {
    include $_SERVER['DOCUMENT_ROOT'] . '/chat/dsn.php';
    include $_SERVER['DOCUMENT_ROOT'] . '/chat/admin/users/avatar.php';/* 2.1 Проверяем все ли поля формы заполнены */
    if (empty($_POST['login']) or empty($_POST['password']) or empty($_POST['email'])) {
        exit("Вы ввели не всю информацию, вернитесь назад и заполните все поля!");
    }/* 2.2 Проверка корректности email */
    if (!preg_match("/[0-9a-z_]+@[0-9a-z_^\.]+\.[a-z]{2,3}/i", $_POST['email'])) {
        exit("Неверно введен е-mail!");
    }
    try {
        $sql = 'UPDATE users SET login = :login,email = :email,img = :img WHERE id = :id';
        $s = $dsn->prepare($sql);
        $s->bindValue(':id', $_POST['id']);
        $s->bindValue(':login', $_POST['login']);
        $s->bindValue(':email', $_POST['email']);
        $s->bindValue(':img', $avatar);
        $s->execute();
    } catch (pdoException $e) {
        $error = 'Ошибка добавления юзера.';
        include $_SERVER['DOCUMENT_ROOT'] . '/chat/admin/users/error.html.php';
        exit();
    }
    if ($_POST['password'] != '') {
        $password = md5($_POST['password'] . 'swl');
        try {
            $sql = 'UPDATE users SETpassword = :passwordWHERE id = :id';
            $s = $dsn->prepare($sql);
            $s->bindValue(':password', $password);
            $s->bindValue(':id', $_POST['id']);
            $s->execute();
        } catch (pdoException $e) {
            $error = 'Ошибка обновления пароля.';
            include $_SERVER['DOCUMENT_ROOT'] . '/chat/admin/users/error.html.php';
            exit();


        }
    }
    header('Location: .');
    exit();
}

if (isset($_POST['action']) and $_POST['action'] == 'ДА') {
    include $_SERVER['DOCUMENT_ROOT'] . '/chat/dsn.php';
    try {
        $sql = 'DELETE FROM authorrole WHERE authorid = :id';
        $s = $dsn->prepare($sql);
        $s->bindValue(':id', $_POST['id']);
        $s->execute();
    } catch (pdoException $e) {
        $error = 'Ошибка удаления роли юзера.';
        include $_SERVER['DOCUMENT_ROOT'] . '/chat/admin/users/error.html.php';
        exit();
    }
    try {
        $sql = 'DELETE FROM users WHERE id = :id';
        $s = $dsn->prepare($sql);
        $s->bindValue(':id', $_POST['id']);
        $s->execute();
    } catch (pdoException $e) {
        $error = 'Ошибка удаления юзера.';
        $e->getMessage();
        $e->getLine();
        include $_SERVER['DOCUMENT_ROOT'] . '/chat/admin/users/error.html.php';
        exit();
    }
    header('Location: .');
    exit();
}/* Первичный запрос на удаление. Выводит форму…ДА или НЕТ (Удаление 1) */
if (isset($_POST['action']) and $_POST['action'] == 'Удалить') {
    include $_SERVER['DOCUMENT_ROOT'] . '/chat/admin/users/delete.php';
    exit();
}
include $_SERVER['DOCUMENT_ROOT'] . '/chat/dsn.php';
try {
    $sql = 'SELECT users.id,users.login, users.img, authorrole.roleid FROM `users` INNER JOIN authorrole ON users.id =authorrole.authoridWHERE users.login = :login';
    $s = $dsn->prepare($sql);
    $s->execute(array('login' => $_SESSION['login']));
    $result = $s->Fetchall();
} catch (pdoException $e) {
    $error = 'Ошибка извлечения данных юзера из БД!';
    include $_SERVER['DOCUMENT_ROOT'] . '/chat/admin/users/error.html.php';
    exit();
}
foreach ($result as $row) {
    $authors[] = array('id' => $row['id'], 'login' => $row['login'], 'roleid' => $row['roleid'], 'img' => $row['img']);
}
include $_SERVER['DOCUMENT_ROOT'] . '/chat/admin/users/list_edit_user.php';







