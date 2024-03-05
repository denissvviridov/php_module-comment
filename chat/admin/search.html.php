<form action="" method="post" class="stat">
    <center><p>Панель информации:</p></center>
    <fieldset>
        <div><label for="author">Пользователь:</label><select name="author" id="author">
                <option value="">Все пользователи</option>
                <?php foreach ($users as $user) : ?>
                    <optionvalue="<?php htmlout($user['id']); ?>
                    <? phphtmlout($user['login']); ?></option>
                <?php endforeach; ?>
            </select></div>
        <div>
            <p>
                <label for="category">Раздел:</label><select name="category" id="category">
                    <option value="all">Все разделы</option>
                    <option value="say">Комментарии</option>
                    <option value="reply">Ответы</option>
                </select>
            </p>
        </div>
        <div class="textsearch"><label for="text">Содержит текст:</label><input type="text" name="text" id="text"></div>
        <div class="textsearch_button"><input type="hidden" name="action" value="search"><input type="submit" value="Искать"></div>
    </fieldset>
    <!– END fieldset –>
    <fieldset>
        <legend>Выборка для:</legend>
        <h4 class="user"><?= $legend ?></h4></fieldset>
    <fieldset>
        <legend>Список пользователей:</legend>
        <ul style="list-style-type:none;padding:0px;margin:5px;"><?php foreach ($users as $user): ?>
                <li style="border-bottom:1px solid lightgray; margin-bottom:5px;"><?php
                htmlout($user['id']);
                echo '. &nbsp ';
                htmlout($user['login']);
                ?></li><?php endforeach; ?></ul>
    </fieldset>
</form>

