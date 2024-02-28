<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title><?= $title ?? 'Мой блог'?></title>
    <link rel="shortcut icon" href="http://d1ujqdpfgkvqfi.cloudfront.net/favicon-generator/htdocs/favicons/2014-12-13/62a1f791deae4d70b2a3e545720571be.ico" type="image/x-icon">
    <link rel="icon" href="http://d1ujqdpfgkvqfi.cloudfront.net/favicon-generator/htdocs/favicons/2014-12-13/62a1f791deae4d70b2a3e545720571be.ico" type="image/x-icon">
    <link rel="stylesheet" href="<?= ((!empty($_SERVER['HTTPS'])) ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'] . '/MyProject/www/style.css' ?>">
    
</head>
<body>

<table class="layout">
    <tr>
        <td colspan="2" class="header">
            Мой блог
        </td>
    </tr>
    <tr>
        <td colspan="2" style="text-align: right;">

        <?php if (!empty($user)): ?>
    Привет, <?= $user->getNickname(); ?> |
    <a href="logout">Выйти</a>
<?php else: ?>
    <a href="http://localhost:8081/MyProject/www/users/login">Войти</a> |
    <a href="http://localhost:8081/MyProject/www/users/register">Зарегистрироваться</a>
<?php endif; ?>

        </td>
</tr>
   <tr>
    <td>