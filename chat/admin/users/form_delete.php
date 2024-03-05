<?php include_once $_SERVER['DOCUMENT_ROOT'] . '/chat/admin/clean.php';?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href="/chat/style.css"/>
    <title>Подтверждение удаления</title></head>
<body class="chatbody"><h1>Удаление</h1>
<ul><?php foreach ($authors as $author) : ?>
        <li style="list-style-type: none">
        <form action="" method="post" class="chatform">
            <div><p>Вы действительно хотите удалить этого пользователя?</p>
                <hr>
                <p><input type="hidden" name="id" value="<?php echo $author['id']; ?>">
                    <input type="submit"
                           name="action"
                           value="ДА">
                    <input type="submit" name="action" value="НЕТ">
                </p><?php $ava = $author['img'];
                echo '<img src="' . $ava . ' ">' . '<p>';
                htmlout($author['login']);
                echo ' ';
                htmlout($author['roleid']);
                echo '<p>'; ?>
            </div>
        </form></li><?php endforeach; ?></ul>
<p><a href="..">Вернуться</a></p></body>
</html>”

