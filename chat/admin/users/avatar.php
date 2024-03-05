<?php error_reporting(E_ALL);/*....................avatar................................*/
if (empty($_FILES['fupload']['name'])) {
    $avatar = 'avatars/noavatar.png';
} else {
    $path = 'avatars/';
    if (preg_match('/[.](JPG)|(jpg)|(gif)|(GIF)|(png)|(PNG)$/', $_FILES['fupload']['name'])) {
        $filename = $_FILES['fupload']['name'];
        $source = $_FILES['fupload']['tmp_name'];
        $target = $path . $filename;
        move_uploaded_file($source, $target);
        if (preg_match('/[.](GIF)|(gif)$/', $filename)) {
            $im = imagecreatefromgif($path . $filename);
        }
        if (preg_match('/[.](PNG)|(png)$/', $filename)) {
            $im = imagecreatefrompng($path . $filename);
        }
        if (preg_match('/[.](JPG)|(jpg)|(jpeg)|(JPEG)$/', $filename)) {
            $im = imagecreatefromjpeg($path . $filename);
        }

        $w = 90;
        $h = 90;
        $w_src = imagesx($im);
        $h_src = imagesy($im);

        if ($w_src !== $h_src) {
            exit ('<h4>Стороны изображения для загрузки должны быть равны. Квадрат. Например 256*256.<br>Рекомендую использовать готовые аватары со специализированных сайтов.<br>Или подготовьте картинку в графическом редакторе</h4><br><i>p.s. грузим аватары, а не картины</i>');
        }
        $dest = imagecreatetruecolor($w, $w);
        $white = imagecolorallocate($dest, 255, 255, 255);
        imagefill($dest, 0, 0, $white);
        imagecopyresampled($dest, $im, 0, 0, 0, 0, $w, $w, $w_src, $h_src);
        $date = time();
        imagejpeg($dest, $path . $date . ".jpg");
        $avatar = $path . $date . '.jpg';
        $delfull = $path . $filename;
        unlink($delfull);
    } else {
        exit ("Аватар должен быть в формате <strong>JPG,GIF или PNG</strong>");
    }
}



