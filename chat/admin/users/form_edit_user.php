<?php include_once $_SERVER['DOCUMENT_ROOT'] . '/chat/admin/clean.php'; ?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href="/chat/style.css"/>
    <title><?php htmlout($pageTitle); ?></title></head>
<body class="chatbody"><h1><?php htmlout($pageTitle); ?></h1>
<p style='font-style:italic'>Здесь вы можете поменять аватар, пароль и email</p>
<form action="?<?php htmlout($action); ?>" method="post" enctype="multipart/form-data" class="chatform">
    <div>
        <label for="name">Имя: &nbsp &nbsp &nbsp
            <input type="text " READONLY name="login" id="login" value="<?php if (isset($login)) {htmlout($login);} ?>"></label>”
    </div>
    <br/>
    <div><label for="email">Email: &nbsp &nbsp<input type="email" name="email" id="email"
                                                     value="<?php htmlout($email); ?>"></label></div>
    <br/>
    <div><label for="password">Пароль: <input type="password" name="password" id="password"></label></div>
    <p><label>Выберите аватар. Изображение должно быть формата jpg, gif или png:<br></label><input type="FILE"
                                                                                                   name="fupload"></p>
    <div>
        <input type="hidden" name="id" value="<? phphtmlout($id); ?>">
        <input type="submit" value="<?php htmlout($button); ?>">
    </div>
</form>
<a href=".." class="apreturn">Вернуться</a></body>
</html>”
