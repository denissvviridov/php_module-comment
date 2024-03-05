<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href="/chat/style.css"/>
    <title>Листинг комментариев</title></head>
<body class="chatbody"><h1 class="user">Статистика комментариев</h1>
<div class="statwrap" id=""><!– Вставляем блок формы поиска –>
    <div class="statone" id=""><?php include_once $_SERVER['DOCUMENT_ROOT'] . '/chat/admin/search.html.php'; ?></div>

    <div class="stattwo" id=""><h5 class="user">Комментарии</h5>
        <?php
        if (isset($says)) {
            foreach ($says as $say) : ?>
                <p
                        class="stat"><?php echo '<span style="color:cadetblue;">' . ' ' . $say['id'] . ' ' . '</span>';
                $patterns = '/(:([^>]+):)/U';
                $replace = ' smile* ';
                $clean = $say['text'];
                $text = preg_replace($patterns, $replace, $clean);
                echo $text;
                $t = time($say['saydate']);
                echo '<span style="color:cadetblue;">' . ' ' . date("d.m.Y", "$t") . '</span>'; ?></p><?php endforeach;
        } ?>
    </div>

    <div class="statthree" id=""><h5 class="user">Ответы на комментарии</h5>
        <?php if (isset($replys)) {
            foreach ($replys as $reply) : ?>
                <p class="stat"><?php echo
                        '<span style="color:cadetblue;">' . ' ' . $reply['replyid'] . ' ' . '<-' . ' ' . '</span>';
                    $patterns = '/(:([^>]+):)/U';
                    $replace = ' smile*';
                    $clean = $reply['replytext'];
                    $text = preg_replace($patterns, $replace, $clean);
                    echo $text;
                    $t = time($reply['replydate']);
                    echo '<span style="color:cadetblue;">' . ' ' . date("d.m.Y", "$t") . '</span>'; ?>
                </p>
            <?php endforeach;
        } ?>
    </div>
    <!– END statthree –>
</div>
<!– END statwrap –><br/>
<div class="return"><a href="/chat/admin/">Вернуться</a></div>
</body>
</html>



