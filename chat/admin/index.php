<?php /* Проверяем уровень доступа к странице */
include_once $_SERVER['DOCUMENT_ROOT'] . '/chat/admin/access.php';

if (!userIsLoggedIn()) {
    include $_SERVER['DOCUMENT_ROOT'] . '/chat/admin/form_login.php';
    exit();
}
if (!userHasRole('admin') and !userHasRole('user')) {
    $error = 'Вход только для администратора';
    include $_SERVER['DOCUMENT_ROOT'] . '/chat/admin/accessdenied.html.php';
    exit();
} ?>
<html lang="en">
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href="/style.css"/>
    <title>Панель управления</title></head>
<body class="chatbody">
<?php include_once $_SERVER['DOCUMENT_ROOT'] . '/chat/admin/button_logout.html' ?>
<div class="wrap_apanel">
    <div class="blockapanel"><h2>Панель управления</h2>
        <ul>
            <li><a href="/chat/admin/comment.php" class="apanel">Комментарии</a></li>
            <li><a href="/chat/admin/users/index.php" class="apanel">Пользователи</a></li>
        </ul>
    </div>
    <p><a href="/" class="apreturn">Вернуться на главную страницу</a></p></div>
</body>
</html>



