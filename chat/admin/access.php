<?php

error_reporting(E_ALL);

function userIsLoggedIn()
{
    if (isset($_POST['action']) and $_POST['action'] == 'out') {
        if (!isset($_POST['login']) or $_POST['login'] == '' or !isset($_POST['password']) or $_POST['password'] == '') {
            $GLOBALS['loginError'] = 'Пожалуйста, заполните оба поля';
            return FALSE;
        }
        $password = md5($_POST['password'] . 'swl');
        if (databaseContainsAuthor($_POST['login'], $password)) {
            try {
                include $_SERVER['DOCUMENT_ROOT'] . '/chat/dsn.php';
                $activation = '';
                $login = $_POST['login'];
                $password = md5($_POST['password'] . 'swl');
                $sql = 'SELECT activation FROM users WHERE login = :login AND password = :password';
                $s = $dsn->prepare($sql);
                $s->bindValue(':login', $login);
                $s->bindValue(':password', $password);
                $s->execute();
            } catch (PDOException $e) {
                echo $e->getMessage();
                exit();
            }

            $activation = $s->fetch(PDO::FETCH_COLUMN);
            if ($activation != 1) {
                include $_SERVER['DOCUMENT_ROOT'] . '/chat/admin/erroractivation.html';
                exit();
            }
            if (session_id() == '') {
                session_start();
            }

            $_SESSION['loggedIn'] = TRUE;
            $_SESSION['login'] = $_POST['login'];
            $_SESSION['password'] = $password;

            return TRUE;
        } else {
            if (session_id() == '') {
                session_start();
            }
            unset($_SESSION['loggedIn']);
            unset($_SESSION['login']);
            unset($_SESSION['password']);
            $GLOBALS['loginError'] = 'Указанный логин или password не совпадают.';
            return FALSE;
        }
    }
    if (isset($_POST['action']) and $_POST['action'] == 'logout') {
        if (session_id() == '') {
            session_start();
        }
        unset($_SESSION['loggedIn']);
        unset($_SESSION['login']);
        unset($_SESSION['password']);
        unset($_SESSION['userid']);
        header('Location: ' . $_POST['goto']);
        exit();
    }
    if (session_id() == '') {
        session_start();
    }
    if (isset($_SESSION['loggedIn'])) {
        return databaseContainsAuthor($_SESSION['login'], $_SESSION['password']);
    }
}/* Функция проверяет наличие в БД пользователя с переданной парой логин – пароль */
function databaseContainsAuthor($login, $password)
{
    include $_SERVER['DOCUMENT_ROOT'] . '/chat/dsn.php';

    try {
        $sql = 'SELECT COUNT(*) FROM users WHERE login = :login AND password = :password';
        $s = $dsn->prepare($sql);
        $s->bindValue(':login', $login);
        $s->bindValue(':password', $password);
        $s->execute();
    } catch (PDOException $e) {
        echo $e->getMessage();
        exit();
    }
    $row = $s->fetch();

    if ($row[0] > 0) {
        return TRUE;
    } else {
        return FALSE;

    }
}

function userHasRole($role)
{
    include $_SERVER['DOCUMENT_ROOT'] . '/chat/dsn.php';

    if (isset($_SESSION['login'])) {
        try {
            $sql = "SELECT COUNT(*) FROM users INNER JOIN authorrole ON users.id = authorid INNER JOIN role ON roleid = role.id WHERE login = :login AND role.id = :roleId";
            $s = $dsn->prepare($sql);
            $s->bindValue(':login', $_SESSION['login']);
            $s->bindValue(':roleId', $role);
            $s->execute();
        } catch (PDOException $e) {
            echo $e->getMessage();
            echo $e->getLine();
            exit('Ошибка поиска прав пользователя');
        }
        $row = $s->fetch();
        if ($row[0] > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }
}


function userId()
{
    if (isset($_SESSION['login'])) {
        try {
            include $_SERVER['DOCUMENT_ROOT'] . '/chat/dsn.php';
            $sql = 'SELECT id FROM users WHERE login = :login';
            $s = $dsn->prepare($sql);
            $s->bindValue(':login', $_SESSION['login']);
            $s->execute();
            $rowid = $s->fetch();
            $_SESSION['userid'] = $rowid['id'];
        } catch (PDOException $e) {
            echo $e->getMessage();
            echo $e->getLine();
            exit('Ошибка добавления пользователя');
        }
    }
}








