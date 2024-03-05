<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" type="text/css" href="/style.css">
    <title>Установить администратора</title>
</head>
<body>
<h2>Установить администратора</h2>

<form class="adminform" action="" method="post"><p>Введите учетные данные Администратора</p>
    <div class="label"><label for="name">Логин:<input type="text" name="login" id="login"></label></div>
    <hr>
    <div class="label"><label for="password">Пароль:<input type="password" name="password" id="password"></label></div>
    <hr>
    <p><i>Для запуска базы данных введите данные,в дальнейшем вы сможете поменять их в разделе администрирования</i></p>
    <div class="runcreateadmin"><input type="hidden" name="action" value="start"><input type="submit" value="Отправить">
    </div>
</form>



</body>
</html>