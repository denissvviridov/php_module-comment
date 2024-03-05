<?php

include_once $_SERVER['DOCUMENT_ROOT'] . '/chat/dsn.php';

try {
    $sql = 'DROP DATABASE beseder';
    $s = $dsn->exec($sql);
} catch (PDOException $e) {
    echo $e->getMessage();
    exit('Ошибка подключения к базе данных');
}
header('Location: /');

