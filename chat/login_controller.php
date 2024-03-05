<?php error_reporting(E_ALL);
include_once $_SERVER['DOCUMENT_ROOT'] . '/chat/admin/access.php';

userIsLoggedIn();
userId();

if (isset($_SESSION['login'])) {
    echo '<div class="welcom">Welcome' . $_SESSION['login'] . '</div>';
    include_once $_SERVER['DOCUMENT_ROOT'] . '/chat/admin/button_cabinet.html';
    include_once $_SERVER['DOCUMENT_ROOT'] . '/chat/admin/button_logout.html';
} else {
    include_once $_SERVER['DOCUMENT_ROOT'] . '/chat/admin/users/input_button_block.html';
    if (!empty($_GET['name'])) {
        include_once $_SERVER['DOCUMENT_ROOT'] . '/chat/admin/form_login.php';
    }
}


