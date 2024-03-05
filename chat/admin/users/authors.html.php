“<?php include_once $_SERVER['DOCUMENT_ROOT'] . '/chat/admin/clean.php'; ?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href="/chat/style.css"/>
    <title>Управление пользователями</title></head>
<body><h2 class="user">Управление пользователями</h2>
<div class="addusers"><a href="?add">Добавить нового пользователя</a></div>
<div class="ourwrapper" id="">
    <ul>
        <?php foreach ($authors as $author) : ?>
            <li style="list-style-type: none">–>
                <div class="formuser">
                    <form action="" method="post" class="formusers">
                        <div class="wrapuser">
                            <div class="topuser" id=""><p class="login_art"><span
                                            class="number"># <?php htmlout($author['id']); ?></span><?php htmlout($author['login']); ?>
                                </p></div>
                            <!– end topuser
                            –><?php $ava = $author['img'];
                            echo '<img class="imgava" src="' . $ava . '">' . ' ';
                            echo '<p>';
                            htmlout($author['roleid']); //выводим уровень доступа пользователяecho '</p>' . '<br>';?>
                            <input type="hidden" name="id" value="<?php echo $author['id']; ?>">
                            <input type="submit"
                                   name="action"
                                   value="Редактировать">
                            <input
                                    type="submit" name="action" value="Удалить" onclick=""></div>
                        <!– end wrapuser –>
                    </form>
                </div>
                <!– end formuser –><!–
            </li>–><?php endforeach; ?><!–
    </ul>
    –>
</div>
<!– end ourwrapper –><p><a href="..">Вернуться</a></p></body>
</html>

