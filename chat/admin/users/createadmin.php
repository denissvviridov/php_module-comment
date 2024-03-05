<?php
error_reporting(E_ALL);

if (session_id() == '') {
    session_start();
}

include_once $_SERVER['DOCUMENT_ROOT'] . '/chat/dsn.php';

if (isset($_POST['login'])) {
    $login = $_POST['login'];
    if ($login == '') {
        unset($login);
    }
}
if (isset($_POST['password'])) {
    $password = $_POST['password'];
    if ($password == '') {
        unset($password);
    }
}


if (empty($login) or empty($password)) {
    exit("<h4>Вы ввели не всю информацию, заполните все поля!</h4>");
}
$login = stripslashes($login);
$login = htmlspecialchars($login);
$password = stripslashes($password);
$password = htmlspecialchars($password);
$login = trim($login);
$password = trim($password);



try {
    $sql = 'INSERT INTO users SET login = :login, activation = :activation';
    $activation = 1;
    $s = $dsn->prepare($sql);
    $s->bindValue(':login', $_POST['login']);
    $s->bindValue(':activation', $activation);
    $s->execute();
} catch (pdoException $e) {
    exit('Ошибка при добавлении пользователя: ' . $e->getMessage());
}

$authorid = $dsn->lastInsertId();
$password = md5($_POST['password'] . 'swl');

try {
    $sql = 'UPDATE users SET password = :password WHERE id = :id';
    $s = $dsn->prepare($sql);
    $s->bindValue(':password', $password);
    $s->bindValue(':id', $authorid);
    $s->execute();
} catch (pdoException $e) {
    exit('Ошибка установки пароля');
}
try {
    $role = 'admin';
    $sql = 'INSERT INTO authorrole SET authorid = :authorid,roleid = :roleid';
    $s = $dsn->prepare($sql);
    $authorid = $dsn->lastInsertId();
    $s->bindValue(':authorid', $authorid);
    $s->bindValue(':roleid', $role);
    $s->execute();
} catch (pdoException $e) {
    exit('Ошибка при назначении прав автору');
}

$_POST['action'] = '';






