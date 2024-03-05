<?php include_once $_SERVER['DOCUMENT_ROOT'] . '/chat/admin/clean.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href="/chat/style.css"/>
    <title><?php htmlout($pageTitle); ?></title></head>
<body><h1><?php htmlout($pageTitle); ?></h1>
<form action="?<?php htmlout($action); ?>" method="post" enctype="multipart/form-data">
    <fieldset class="usernames">
        <div class="username">
            <label for="name">Имя: &nbsp &nbsp &nbsp
                <input type="text" name="login" id="login"
                       value="<?php if (isset($login)) {
                           htmlout($login);
                       } ?>">
            </label>
        </div>
        <div class="username"><label for="email">Email: &nbsp &nbsp<input type="email" name="email" id="email"
                                                                          value="<?php htmlout($email); ?>"></label>
        </div>
        <!– end.username –>
        <div class="username"><label for="password">Пароль:&nbsp<input type="password" name="password"
                                                                       id="password"></label></div>
        <!– end.username –>
    </fieldset>
    <!– end.fieldset usernames–><br/>
    <fieldset class="ava">
        <legend>Загружаем аватар:</legend>
        <p><label><p>Выберите аватар. Изображение должно быть формата jpg, gif или png:</p>
                <p>Загружаем готовые аватары или картинки только квадратной формы</p><br> </label><input
                    type="FILE" name="fupload"></p>
    </fieldset>

    <fieldset class="level">
        <legend>Уровень доступа пользователя:</legend><?php for ($i = 0; $i < count($roles); $i++): ?>
            <div><label for="role<?php echo $i; ?>">
                <input type="checkbox" name="roles[]" id="role<?php echo $i; ?>" value="<?php htmlout($roles[$i]['id']); ?>"
                    <?php if ($roles[$i]['selected']) {
                        echo ' checked';
                    } ?>
                > <?php htmlout($roles[$i]['id']); ?>
            </label>:<?php htmlout($roles[$i]['description']); ?><?php endfor; ?></fieldset>

    <div><input type="hidden" name="id" value="<? phphtmlout($id); ?>"><input type="submit"
                                                                              value="<?php htmlout($button); ?>"></div>
</form>
<a href="/chat/admin/">Вернуться</a></body>
</html>”


