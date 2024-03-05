<?php try {
    $dsn = new PDO('mysql:host=localhost;dbname=beseder', 'root', 'qwerty12345');
    $dsn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $dsn->exec('SET NAMES "utf8"');
} catch (PDOException $e) {
    echo $e->getMessage();
    echo $e->getLine();
    exit();
}


