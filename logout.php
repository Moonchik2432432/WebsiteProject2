<?php
session_start(); // стартуем сессию

// Проверяем, есть ли сессия пользователя (можно и не проверять, просто уничтожить)
if (isset($_SESSION['user_id'])) {
    $_SESSION = array();
    session_destroy();
}
header("Location: index.php");
exit();
?>