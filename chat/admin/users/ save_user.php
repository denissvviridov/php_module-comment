<?php error_reporting(E_ALL);
if (session_id() == '') {
    session_start();
}/* заносим введенный пользователем логин в переменную $login, если он пустой, то уничтожаем переменную */
if (isset($_POST['login'])) {
    $login = $_POST['login'];
    if (mb_strlen($login) < 2 or mb_strlen($login) > 15) {
        exit("Логин должен состоять не менее чем из 3 символов и не более чем из 15.");
    }
    if ($login == '') {
        unset($login);
    }
}/* заносим введенный пользователем пароль в переменную $password, если он пустой, то уничтожаем переменную */
if (isset($_POST['password'])) {
    $password = $_POST['password'];
    if (mb_strlen($password) < 3 or mb_strlen($password) > 15) {
        exit("Пароль должен состоять не менее чем из 3 символов и не более чем из 15.");
    }
    if ($password == '') {
        unset($password);
    }
}/* заносим введенный пользователем код в переменную $capcha, если он пустой, то уничтожаем переменную */
if (isset($_POST['capcha'])) {
    $capcha = $_POST['capcha'];
    if ($capcha == '') {
        unset($capcha);
    }
}/* заносим введенный пользователем e-mail, если он пустой, то уничтожаем переменную */
if (isset($_POST['email'])) {
    $email = $_POST['email'];
    if ($email == '') {
        unset($email);
    }
}
$capcha = stripslashes($capcha);
$capcha = htmlspecialchars($capcha);
$capcha = trim($capcha);
if (($capcha == $_SESSION["rand_code"]) && ($capcha != "")) {

} else {
    exit("Капча введена неправильно");
}
if (empty($login) or empty($password) or empty($email) or empty($capcha)) {
    exit("Вы ввели не всю информацию, вернитесь назад и заполните все поля!");
}
if (!preg_match("/[0-9a-z_]+@[0-9a-z_^\.]+\.[a-z]{2,3}/i", $email)) {
    exit("Неверно введен е-mail!");
}
$login = stripslashes($login);
$login = htmlspecialchars($login);
$password = stripslashes($password);
$password = htmlspecialchars($password);
$login = trim($login);
$password = trim($password);

$password = md5($_POST['password'] . 'swl');
include $_SERVER['DOCUMENT_ROOT'] . '/chat/dsn.php';
$sth = $dsn->prepare("SELECT id FROM users WHERE login=:login");
$sth->execute(array(':login' => $login));
if (!empty($sth->fetch(PDO::FETCH_ASSOC))) {
    exit("Извините, введённый вами логин уже зарегистрирован. Введите другой логин.");
}/*....................avatar................................*/
if (empty($_FILES['fupload']['name'])) {
    $avatar = "avatars/noavatar.png";
} else {
    $path_to_90_directory = 'avatars/';
    if (preg_match('/[.](JPG)|(jpg)|(gif)|(GIF)|(png)|(PNG)$/', $_FILES['fupload']['name'])) {
        $filename = $_FILES['fupload']['name'];
        $source = $_FILES['fupload']['tmp_name'];
        $target = $path_to_90_directory . $filename;
        move_uploaded_file($source, $target);
        if (preg_match('/[.](GIF)|(gif)$/', $filename)) {
            $im = imagecreatefromgif($path_to_90_directory . $filename);
            //если оригинал был в формате gif, то создаем изображение в этом же формате. Необходимо для последующего сжатия”

        }
        if (preg_match('/[.](PNG)|(png)$/', $filename)) {
            $im = imagecreatefrompng($path_to_90_directory . $filename);
        }
        if (preg_match('/[.](JPG)|(jpg)|(jpeg)|(JPEG)$/', $filename)) {
            $im = imagecreatefromjpeg($path_to_90_directory . $filename);
        }
        $w = 90;
        $h = 90;
        $w_src = imagesx($im);
        $h_src = imagesy($im);
        if ($w_src !== $h_src) {
            exit('<h4>Стороны изображения для загрузки должны быть равны. Например 256*256.<br>Рекомендую использовать готовые аватары со специализированных сайтов.<br>Или подготовьте картинку в графическом редакторе</h4><br><i>p.s. грузим аватары, а не картины</i>');
        }
        $dest = imagecreatetruecolor($w, $w);
        $white = imagecolorallocate($dest, 255, 255, 255);

        imagefill($dest, 0, 0, $white);
        imagecopyresampled($dest, $im, 0, 0, 0, 0, $w, $w, $w_src, $h_src);
        $date = time();
        imagejpeg($dest, $path_to_90_directory . $date . ".jpg");
        $avatar = $path_to_90_directory . $date . ".jpg";
        $delfull = $path_to_90_directory . $filename;
        unlink($delfull);
    } else {
        exit("Аватар должен быть в формате <strong>JPG,GIF или PNG</strong>");
    }
}
if (!isset($date)) {
    $date = time();
}
try {
    $sql = "INSERT INTO users (login,password,email,img,date) VALUES(:login,:password,:email,:img, :date)";
    $result2 = $dsn->prepare($sql);
    $result2->execute(['login' => $login, 'password' => $password, 'email' => $email, 'img' => $avatar, 'date' => $date]);
    $role = 'user';
    $authorid = $dsn->lastInsertId();
    $sql = "INSERT INTO authorrole (authorid,roleid) VALUE (:authorid,:roleid)";
    $resultrole = $dsn->prepare($sql);
    $resultrole->execute(['authorid' => $authorid, 'roleid' => $role]);
    echo '<img src="' . $avatar . '">';
    echo ' ' . '<h3>' . $login . '</h3>' . ' ' . "Вы успешно зарегистрированы! <a href='/index.html'>Главная страница</a>";
} catch (PDOException $e) {
    echo "You have an error: " . $e->getMessage() . "<br>";
    echo "On line: " . $e->getLine();
}
$activation = md5($authorid) . md5($login);
$subject = "Подтверждение регистрации";
$message = "Здравствуйте! Спасибо за регистрацию в модуле комментариев chat\nВаш логин: " . $login . "\nПерейдите по ссылке, чтобы активировать ваш аккаунт:\nhttp://" . $_SERVER['HTTP_HOST'] . "/chat/admin/users/activation.php?login=" . $login . "&code=" . $activation . "\nС уважением,\nАдминистратор модуля";
еmail($email, $subject, $message, "Content-type:text/plane; Charset=utf-8\r\n");
echo "<hr><h3>Вам на E-mail выслано письмо с cсылкой, для подтверждения регистрации.</h3> <br><b>Внимание! Ссылка действительна 1 час.</b>";






