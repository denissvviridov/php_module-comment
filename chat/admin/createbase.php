<?php

error_reporting(E_ALL);

$host = "localhost";
$root = "root";
$root_password = "qwerty12345";
$db = "beseder";

try {
    $dsn = new PDO("mysql:host=$host", $root, $root_password);
    $dsn->exec("CREATE DATABASE IF NOT EXISTS `$db` CHARACTER SET utf8 COLLATE utf8_general_ci;");
    echo 'База создана (OK!)<br>' . ' Имя БД: ' . '<b>' . $db . '</b>' . '<br> Пользователь: ' . '<b>' . $root . '</b>';
} catch (PDOException $e) {
    echo $e->getMessage();
    echo $e->getLine();
    exit('Ошибка при создании базы');
}
$dsn = null;


try {
    $dsn = new PDO("mysql:host=$host;dbname=$db", $root, $root_password);
    $dsn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql_page = "CREATE TABLE IF NOT EXISTS page (id INT(11) AUTO_INCREMENT PRIMARY KEY NOT NULL,sayid int(11),userid int(11),pageid text)";
    $sql_users = "CREATE TABLE IF NOT EXISTS users (id int(11) AUTO_INCREMENT PRIMARY KEY NOT NULL,login VARCHAR(15) NOT NULL,password VARCHAR(256) NOT NULL,youtext text NOT NULL,email text NOT NULL,img text NOT NULL,activation int(11),date int(11))";
    $sql_role = "CREATE TABLE IF NOT EXISTS role (id VARCHAR(255) NOT NULL PRIMARY KEY,description VARCHAR(255))DEFAULT CHARACTER SET utf8 ENGINE=InnoDB";
    $sql_authorrole = "CREATE TABLE IF NOT EXISTS authorrole (authorid INT NOT NULL,roleid VARCHAR(255) NOT NULL,PRIMARY KEY (authorid, roleid))DEFAULT CHARACTER SET utf8 ENGINE=InnoDB";
    $sql_roledesc = "REPLACE INTO role (id, description) VALUES('user', 'Контроль своих комментариев'),('admin', 'Full control'),('Site Administrator', 'Контроль комментариев')";
    $sql_userole = "REPLACE INTO authorrole (authorid, roleid) VALUES(1, 'admin')";
    $sql_say = "CREATE TABLE IF NOT EXISTS say (id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,saytext TEXT,userid int(11),saydate int(11)) DEFAULT CHARACTER SET utf8 ENGINE=InnoDB";
    $sql_reply = "CREATE TABLE IF NOT EXISTS reply (id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,replytext TEXT,userid int(11),replyid int(11),replydate int(11)) DEFAULT CHARACTER SET utf8 ENGINE=InnoDB";

    $dsn->exec($sql_users);
    $dsn->exec($sql_authorrole);
    $dsn->exec($sql_role);
    $dsn->exec($sql_roledesc);
    $dsn->exec($sql_userole);
    $dsn->exec($sql_say);
    $dsn->exec($sql_page);
    $dsn->exec($sql_reply);
} catch (PDOException $e) {
    echo $e->getMessage();
}

try {
    $sql_smile = "CREATE TABLE IF NOT EXISTS smiles (id INT(11) AUTO_INCREMENT PRIMARY KEY NOT NULL,smile text,path text)";
    $dsn->exec($sql_smile);
} catch (PDOException $e) {
    echo $e->getMessage();
    echo $e->getLine();
    exit();
}


$dir = $_SERVER['DOCUMENT_ROOT'] . '/chat/say/smiles/';
$files1 = preg_grep('~\.(jpeg|jpg|png|gif)$~', scandir($dir));


try {
    $sql = 'INSERT INTO smiles SETsmile = :smile,path = :path';
    $s = $dsn->prepare($sql);
    foreach ($files1 as $val) {
        $smile = pathinfo($val, PATHINFO_FILENAME);
        $smile = str_replace($smile, ":$smile:", $smile);
        $path = '/chat/say/smiles/' . $val;
        $s->bindValue(':smile', $smile);
        $s->bindValue(':path', $path);
        $s->execute();
    }
} catch (PDOException $e) {
    echo $e->getMessage();
    echo $e->getLine();
    exit();
}
echo '<br>' . 'Все таблицы успешно созданы';

























