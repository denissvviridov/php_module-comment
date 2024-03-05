<?php include_once $_SERVER['DOCUMENT_ROOT'] . '/chat/admin/clean.php';?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href="/chat/style.css"/>
    <title>Редактировать профиль</title></head>
<body class="chatbody"><h2>Редактировать профиль</h2>
<ul><?php foreach ($authors

                   as $author) : ?>
        <li style="list-style-type: none">
        <form action="" method="post" class="chatform">
            <div class="list_edituser"><?php $ava = $author['img'];
                echo '<img src="' . $ava . '">';
                htmlout($author['login']);
                echo " ";
                htmlout($author['roleid']);
                ?>
                <input type="hidden" name="id" value="<?php echo $author['id']; ?>">
                <input type="submit" name="action" value="Редактировать"><input type="submit" name="action"
                                                                                value="Удалить"></div>
        </form></li><?php endforeach; ?></ul>
<p><a href=".." class="apreturn">Вернуться</a></p></body>
</html>”

