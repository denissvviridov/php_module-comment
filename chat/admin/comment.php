<?php error_reporting(E_ALL);
include_once $_SERVER['DOCUMENT_ROOT'] . '/chat/dsn.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/chat/admin/clean.php';
try {
    $result = $dsn->query('SELECT id, login FROM users');
} catch (PDOException $e) {
    echo $e->getMessage();
    echo $e->getLine();
    exit('Ошибка поиска пользователя в базе комментариев');
}
foreach ($result as $row) {
    $users[] = array('id' => $row['id'], 'login' => $row['login']);
}/* Получаем логин пользователя при запросе */
if (isset($_POST['author']) and $_POST['author'] != '') {
    try {
        $sql = 'SELECT login FROM users WHERE id = :id';
        $s = $dsn->prepare($sql);
        $s->bindValue(':id', $_POST['author']);
        $s->execute();
        $legend = $s->fetchColumn(0);
    } catch (PDOException $e) {
        echo $e->getMessage();
        echo $e->getLine();
        exit('Ошибка поиска логина в базе комментариев');
    }
} else {
    $legend = '';
}
if (isset($_POST['category']) and $_POST['category'] == 'reply') {

} elseif (isset($_POST['action']) and $_POST['action'] == 'search' or !isset($_POST['action'])) {
    include $_SERVER['DOCUMENT_ROOT'] . '/chat/dsn.php';
    $select = 'SELECT say.id, say.userid, say.saytext, say.saydate';
    $from = ' FROM say INNER JOIN users ON say.userid = users.id';
    $where = ' WHERE TRUE';
    $sequence = array();
    if (isset($_POST['author']) and $_POST['author'] != '') {
        $where .= " AND userid = :userid";
        $sequence[':userid'] = $_POST['author'];
    }
    if (isset($_POST['text']) and $_POST['text'] != '') {
        $where .= " AND saytext LIKE :saytext";
        $sequence[':saytext'] = '%' . $_POST['text'] . '%';
    }
    try {
        $sql = $select . $from . $where;
        $s = $dsn->prepare($sql);
        $s->execute($sequence);
    } catch (PDOException $e) {
        echo $e->getMessage();
        echo $e->getLine();
        exit('Ошибка при извлечении комментариев');
    }
    foreach ($s as $row) {
        $says[] = array('id' => $row['id'], 'userid' => $row['userid'], 'text' => $row['saytext'], 'saydate' => $row['saydate']);
    }
}

if (isset($_POST['category']) and $_POST['category'] == 'say') {
}/* иначе формируем запрос к базе */ elseif (isset($_POST['action']) and $_POST['action'] == 'search' or !isset($_POST['action'])) {
    include $_SERVER['DOCUMENT_ROOT'] . '/chat/dsn.php';
    $select = 'SELECT reply.userid, reply.replytext,reply.replyid, reply.replydate ';
    $from = ' FROM reply INNER JOIN users ON reply.userid = users.id ';
    $where = ' WHERE TRUE';
    $order = ' ORDER BY reply.replyid';
    $sequencer = array();
    if (isset($_POST['author']) and $_POST['author'] != '') {
        $where .= " AND userid = :userid";
        $sequencer[':userid'] = $_POST['author'];
    }
    if (isset($_POST['text']) and $_POST['text'] != '') {
        $where .= " AND replytext LIKE :replytext";
        $sequencer[':replytext'] = '%' . $_POST['text'] . '%';
    }
    try {
        $sql = $select . $from . $where . $order;
        $s = $dsn->prepare($sql);
        $s->execute($sequencer);
    } catch (PDOException $e) {
        echo $e->getMessage();
        echo $e->getLine();
        exit('Ошибка при извлечении ответов на комментарии');
    }
    foreach ($s as $row) {
        $replys[] = array('replyid' => $row['replyid'], 'userid' => $row['userid'], 'replytext' => $row['replytext'], 'replydate' => $row['replydate']);
    }
}
include_once $_SERVER['DOCUMENT_ROOT'] . '/chat/admin/comment.html.php';







