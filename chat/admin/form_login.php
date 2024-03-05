<?php include_once $_SERVER['DOCUMENT_ROOT'] . '/chat/admin/clean.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href="/chat/style.css"/>
    <title>Авторизация</title></head>

<style>
    .wrap{
        padding: 10px;
        width: 100%;
        display: flex;
        justify-content: start;
        align-items: center;
    }
</style>
<body>
<?php if (isset($loginError)): ?><p><?php htmlout($loginError); ?></p><?php endif; ?>
<div class="wrap">
    <form action="" method="post" class="chatform"><h4 class="formname">Авторизация</h4>
        <hr>
        <div class="login_input">
            <label for="email">Логин:<input type="text" name="login" id="login" class="inputs"></label>
        </div>
        <hr>
        <div><label for="password">Пароль:<input type="password" name="password" id="password"></label></div>
        <hr>
        <div><input type="hidden" name="action" value="out"><input type="submit" value="Отправить"></div>
    </form>
</div>

</body>
</html>


