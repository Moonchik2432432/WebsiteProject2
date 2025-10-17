<?php
session_start();
// Проверяем, есть ли у пользователя активная сессия
if (!isset($_SESSION['user_id'])) {
    // Если нет, перенаправляем на страницу логина
    header("Location: ../index.php"); 
    exit();
}
?>