<?php if (session_id() == '') {
    session_start();
}
$string = "";
for ($i = 0; $i < 5; $i++) {
    $string .= chr(rand(97, 122));
}
$_SESSION['rand_code'] = $string;
$dir = $_SERVER['DOCUMENT_ROOT'] . '/chat/capcha/fonts/verdana.ttf';
$image = @imagecreatetruecolor(170, 60) or die('Невозможно инициализировать GD поток');
$black = imagecolorallocate($image, 0, 0, 0);
$color = imagecolorallocate($image, 200, 100, 90);
$white = imagecolorallocate($image, 255, 255, 255);
imagefilledrectangle($image, 0, 0, 399, 99, $white);
imagettftext($image, 30, 0, 10, 40, $color, $dir, $_SESSION['rand_code']);
header("Content-type: image/png");
imagepng($image);

