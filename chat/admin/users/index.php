<? phperror_reporting(E_ALL);
include_once $_SERVER['DOCUMENT_ROOT'] . '/chat/admin/access.php';/*.........Проверяем полномочия пользователя..............*/
if (!userIsLoggedIn()) {
    include $_SERVER['DOCUMENT_ROOT'] . '/chat/admin/form_login.php';
    exit();
}
if (!userHasRole('admin') and !userHasRole('user')) {
    exit('Доступ только для Администратора');
}/*..........Если admin разрешаем все. Start admin..........*///Начинаем основной цикл для администратора */
if (userHasRole('admin')) {
    if (isset($_GET['add'])) {
        include $_SERVER['DOCUMENT_ROOT'] . '/chat/dsn.php';

        $pageTitle = 'Подключение нового пользователя';
        $action = 'addform';
        $name = '';
        $email = '';
        $id = '';
        $button = 'Добавить пользователя';
        try {
            $result = $dsn->query('SELECT id, description FROM role');
        } catch (pdoException $e) {
            $error = 'Ошибка при получении списка ролей.';
            include $_SERVER['DO CUMENT_ROOT'] . '/chat/admin/users/error.html.php';
            exit();
        }
        foreach ($result as $row) {
            $roles[] = array('id' => $row['id'], 'description' => $row['description'], 'selected' => false);
        }
        include $_SERVER['DOCUMENT_ROOT'] . '/chat/admin/users/form_add_user.php';
        exit();
    }/*....... 1. Добавление нового пользователя..............*/
    if (isset($_GET['addform'])) {
        include $_SERVER['DOCUMENT_ROOT'] . '/chat/dsn.php';
        include $_SERVER['DOCUMENT_ROOT'] . '/chat/admin/users/avatar.php';/*.... 1.1 Проверяем все ли поля формы заполнены.....*/
        if (empty($_POST['login']) or empty($_POST['password']) or empty($_POST['email']) or empty($_POST['roles'])) {
            exit("Вы ввели не всю информацию, вернитесь назад и заполните все поля!");
        }
        if (mb_strlen($_POST['login']) < 3 or mb_strlen($_POST['login']) > 15) {
            exit("Логин должен состоять не менее чем из 3 символов и не более чем из 15.");
        }
        if (!preg_match("/[0-9a-z_]+@[0-9a-z_^\.]+\.[a-z]{2,3}/i", $_POST['email'])) {
            exit("Неверно введен е-mail!");
        }/*.... 1.3 Проверка существования логина..............*/
        try {
            $logresult = $dsn->query('SELECT login FROM users');
        } catch (pdoException $e) {
            $error = 'Логин не существует.';
            include $_SERVER['DOCUMENT_ROOT'] . '/chat/admin/users/error.html.php';
            exit();
        }
        foreach ($logresult as $row) {
            $logins[] = array('login' => $row['login']);
            if ($row['login'] == $_POST['login']) {
                $error = 'Логин занят.';
                include $_SERVER['DOCUMENT_ROOT'] . '/chat/admin/users/error.html.php';
                exit();
            }
        }
    }
    try {
        $sql = 'INSERT INTO users SET login = :login,email = :email,activation = :activation, date = :date';
        $s = $dsn->prepare($sql);
        $s->bindValue(':login', $_POST['login']);
        $s->bindValue(':email', $_POST['email']);
        $s->bindValue(':activation', "1");
        $s->bindValue(':date', time());
        $s->execute();
    } catch (pdoException $e) {
        $error = 'Ошибка добавления отправленного пользователя.';
        include $_SERVER['DOCUMENT_ROOT'] . '/chat/admin/users/error.html.php';
        exit();
    }
    if ($_POST['password'] != '') {
        $password = md5($_POST['password'] . 'swl');
        $authorid = $dsn->lastInsertId();
        try {
            $sql = 'UPDATE users SETpassword = :passwordWHERE id = :id';
            $s = $dsn->prepare($sql);
            $s->bindValue(':password', $password);
            $s->bindValue(':id', $authorid);
            $s->execute();
        } catch (pdoException $e) {
            $error = 'Ошибка 1 установки пароля.';
            include $_SERVER['DOCUMENT_ROOT'] . '/chat/admin/users/error.html.php';
            exit();
        }
    }
    try {
        $sql = 'UPDATE users SET img = :img WHERE id = :id';
        $s = $dsn->prepare($sql);
        $s->bindValue(':img', $avatar);
        $s->bindValue(':id', $authorid);
        $s->execute();
    } catch (pdoException $e) {
        $error = 'Ошибка 2 установки пароля.';
        include $_SERVER['DOCUMENT_ROOT'] . '/chat/admin/users/error.html.php';
        exit();
    }
    if (empty($_POST['roles'])) {
        $error = 'Вы не отметили полномочия нового пользователя';
        include $_SERVER['DOCUMENT_ROOT'] . '/chat/admin/users/error.html.php';
        exit();
    }
    if (isset($_POST['roles'])) {
        foreach ($_POST['roles'] as $role) {
            try {
                $sql = 'INSERT INTO authorrole SET authorid = :authorid,roleid = :roleid';
                $s = $dsn->prepare($sql);
                $s->bindValue(':authorid', $authorid);
                $s->bindValue(':roleid', $role);
                $s->execute();
            } catch (pdoException $e) {
                $error = 'Ошибка при назначении выбранной роли пользователю.';
                include $_SERVER['DOCUMENT_ROOT'] . '/chat/admin/users/error.html.php';
                exit();
            }
        }
    }
    header('Location: .');
    exit();
}
if (isset($_POST['action']) and $_POST['action'] == 'Редактировать') {
    include $_SERVER['DOCUMENT_ROOT'] . '/chat/dsn.php';
    try {
        $sql = 'SELECT id, login, email, img FROM users WHERE id = :id';
        $s = $dsn->prepare($sql);
        $s->bindValue(':id', $_POST['id']);
        $s->execute();
    } catch (pdoException $e) {
        $error = 'Ошибка при получении сведений об авторе.';
        include $_SERVER['DOCUMENT_ROOT'] . '/chat/admin/users/error.html.php';
        exit();
    }
    $row = $s->fetch();
    $pageTitle = 'Редактирование профиля';
    $action = 'editform';
    $login = $row['login'];
    $email = $row['email'];
    $id = $row['id'];
    $avatar = $row['img'];
    $button = 'Отправить';
    try {
        $sql = 'SELECT roleid FROM authorrole WHERE authorid = :id';
        $s = $dsn->prepare($sql);
        $s->bindValue(':id', $id);
        $s->execute();
    } catch
    (pdoException $e) {
        $error = 'Ошибка при получении назначенной роли пользователя.';
        include $_SERVER['DOCUMENT_ROOT'] . '/chat/admin/users/error.html.php';
        exit();
    }
    $selectedRoles = array();
    foreach ($s as $row) {
        $selectedRoles[] = $row['roleid'];
    }
    try {
        $result = $dsn->query('SELECT id, description FROM role');
    } catch (pdoException $e) {
        $error = 'Ошибка при получении списка ролей.';
        include $_SERVER['DOCUMENT_ROOT'] . '/chat/admin/users/error.html.php';
        exit();
    }

    foreach ($result as $row) {
        $roles[] = array('id' => $row['id'], 'description' => $row['description'], 'selected' => in_array($row['id'], $selectedRoles));
    }
    include $_SERVER['DOCUMENT_ROOT'] . '/chat/admin/users/form_add_user.php';
    exit();
}
if (isset($_GET['editform'])) {
    include $_SERVER['DOCUMENT_ROOT'] . '/chat/dsn.php';
    include $_SERVER['DOCUMENT_ROOT'] . '/chat/admin/users/avatar.php';
    if (empty($_POST['login']) or empty($_POST['password']) or empty($_POST['email']) or empty($_POST['roles'])) {
        exit("Вы ввели не всю информацию, вернитесь назад и заполните все поля!");
    }
    if (mb_strlen($_POST['login']) < 3 or mb_strlen($_POST['login']) > 15) {
        exit("Логин должен состоять не менее чем из 3 символов и не более чем из 15.");
    }
    if (!preg_match("/[0-9a-z_]+@[0-9a-z_^\.]+\.[a-z]{2,3}/i", $_POST['email'])) {
        exit("Неверно введен е-mail!");
    }
    try {
        if (!isset($date)) {
            $date = time();
        }
        $sql = 'UPDATE users SET login = :login,email = :email,img = :img,date = :date WHERE id = :id';
        $s = $dsn->prepare($sql);
        $s->bindValue(':id', $_POST['id']);
        $s->bindValue(':login', $_POST['login']);
        $s->bindValue(':email', $_POST['email']);
        $s->bindValue(':date', $date);
        $s->bindValue(':img', $avatar);
        $s->execute();
    } catch (pdoException $e) {
        $error = 'Ошибка при обновлении пользователя.';
        include $_SERVER['DOCUMENT_ROOT'] . '/chat/admin/users/error.html.php';
        exit();
    }
    if ($_POST['password'] != '') {
        $password = md5($_POST['password'] . 'swl');

        try {
            $sql = 'UPDATE users SETpassword = :passwordWHERE id = :id';
            $s = $dsn->prepare($sql);
            $s->bindValue(':password', $password);
            $s->bindValue(':id', $_POST['id']);
            $s->execute();
        } catch (pdoException $e) {
            $error = 'Ошибка установки пароля автора.';
            include $_SERVER['DOCUMENT_ROOT'] . '/chat/admin/users/error.html.php';
            exit();
        }
    }
    if (empty($_POST['roles'])) {
        $error = 'Вы не отметили полномочия нового пользователя';
        include $_SERVER['DOCUMENT_ROOT'] . '/chat/admin/users/error.html.php';
        exit();
    }
    try {
        $sql = 'DELETE FROM authorrole WHERE authorid = :id';
        $s = $dsn->prepare($sql);
        $s->bindValue(':id', $_POST['id']);
        $s->execute();
    } catch (pdoException $e) {
        $error = 'Ошибка удаления устаревших записей роли пользователя.';
        include $_SERVER['DOCUMENT_ROOT'] . '/chat/admin/users/error.html.php';
        exit();
    }
    if (isset($_POST['roles'])) {
        foreach ($_POST['roles'] as $role) {
            try {
                $sql = 'INSERT INTO authorrole SETauthorid = :authorid,roleid = :roleid';
                $s = $dsn->prepare($sql);
                $s->bindValue(':authorid', $_POST['id']);
                $s->bindValue(':roleid', $role);
                $s->execute();
            } catch (pdoException $e) {
                $error = 'Ошибка при назначении выбранной роли автору.';
                include $_SERVER['DOCUMENT_ROOT'] . '/chat/admin/users/error.html.php';
                exit();
            }
        }
    }
    header('Location: .');
    exit();
}/*....Удаление 2.Результат работы формы, вызываемой кодом ниже(Удаление 1)…*/
if (isset($_POST['action']) and $_POST['action'] == 'ДА') {
    include $_SERVER['DOCUMENT_ROOT'] . '/chat/dsn.php';
    try {
        $sql = 'DELETE FROM authorrole WHERE authorid = :id';
        $s = $dsn->prepare($sql);
        $s->bindValue(':id', $_POST['id']);
        $s->execute();
    } catch (pdoException $e) {
        $error = 'Ошибка удаления роли пользователя.';
        include $_SERVER['DOCUMENT_ROOT'] . '/chat/admin/users/error.html.php';
        exit();
    }
    try {
        $sql = 'DELETE FROM users WHERE id = :id';
        $s = $dsn->prepare($sql);
        $s->bindValue(':id', $_POST['id']);
        $s->execute();
    } catch (pdoException $e) {
        $error = 'Ошибка удаления пользователя индекс.';
        $e->getMessage();
        $e->getLine();
        include $_SERVER['DOCUMENT_ROOT'] . '/chat/admin/users/error.html.php';
        exit();
    }
    header('Location: .');
    exit();
}
if (isset($_POST['action']) and $_POST['action'] == 'Удалить') {
    include $_SERVER['DOCUMENT_ROOT'] . '/chat/admin/users/delete.php';
    exit();
}
include $_SERVER['DOCUMENT_ROOT'] . '/chat/dsn.php';
try {
    $result = $dsn->query('SELECT users.id,users.login, users.img, authorrole.roleid FROM `users` INNER JOIN authorrole ON users.id =authorrole.authorid');
} catch (pdoException $e) {
    $error = 'Ошибка при получении пользователей из базы данных!';
    include $_SERVER['DOCUMENT_ROOT'] . '/chat/admin/users/error.html.php';
    exit();
}
foreach ($result as $row) {
    $authors[] = array('id' => $row['id'], 'login' => $row['login'], 'roleid' => $row['roleid'], 'img' => $row['img']);
}
include $_SERVER['DOCUMENT_ROOT'] . '/chat/admin/users/authors.html.php';

if (userHasRole('user')) {
    include_once $_SERVER['DOCUMENT_ROOT'] . '/chat/admin/users/edituser.php';
}


try {
    $sql = "SELECT * FROM users INNER JOIN authorrole ON users.id = authorrole.authorid WHERE users.activation IS NULL OR users.activation !='1' ";
    $s = $dsn->query($sql);
    $no_active = $s->Fetchall();
} catch (pdoException $e) {
    exit('Ошибка при выборке неактивных пользователей');
}
foreach ($no_active as $nouser) {
    $r = time() - $nouser["date"];
    $id = $nouser['id'];
    if ($r > 3600) {
        try {
            $sql = "DELETE FROM users WHERE id = :id AND activation IS NULL OR users.activation !='1'";
            $s = $dsn->prepare($sql);
            $s->bindValue(':id', $id);
            $s->execute();
        } catch (pdoException $e) {
            exit('Ошибка при выборке неактивных пользователей0');
        }
        try {
            $sql = "DELETE FROM authorrole WHERE authorid = :id";
            $s = $dsn->prepare($sql);
            foreach ($no_active as $nouser) {
                $authorid = $nouser['id'];
                $s->bindValue(':id', $authorid);
                $s->execute();
            }
        } catch (pdoException $e) {
            exit('Ошибка при выборке неактивных пользователей');
        }
    }
}


























