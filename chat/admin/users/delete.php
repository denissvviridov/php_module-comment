<?php error_reporting(E_ALL);
require_once $_SERVER['DOCUMENT_ROOT'] . '/admin/access.php';
include $_SERVER['DOCUMENT_ROOT'] . '/chat/dsn.php';

try {
    $sql = 'SELECT users.id,users.login, users.img, authorrole.roleid FROM `users` INNER JOIN authorrole ON users.id = authorrole.authorid WHERE users.id = :id';
    $s = $dsn->prepare($sql);
    $s->execute(array('id' => $_POST['id']));
    $result = $s->Fetchall();
} catch (pdoException $e) {
    $error = 'Ошибка получения пользователя из БД';
    include $_SERVER['DOCUMENT_ROOT'] . '/chat/admin/users/error.html.php';
    exit();
}
foreach ($result as $row) {
    $authors[] = array('id' => $row['id'], 'login' => $row['login'], 'roleid' => $row['roleid'], 'img' => $row['img']);
}/* Вставляем форму согласия на удаление */
include $_SERVER['DOCUMENT_ROOT'] . '/chat/admin/users/form_delete.php';


