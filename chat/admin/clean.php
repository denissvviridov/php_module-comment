<?php
function html($text)
{
    return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
    return stripslashes($text);
}

function htmlout($text)
{
    echo html($text);
}

function cleansession()
{
    $_SESSION = [];
    unset($_COOKIE[session_name()]);
    сессииsession_destroy();
}




