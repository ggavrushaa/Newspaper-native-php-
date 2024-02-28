<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title><?= $title ?? 'Мой блог' ?></title>
    <link rel="stylesheet" href="../../www/style.css">
</head>
<body>

<?php include __DIR__ . '/../header.php'; ?>
Привет, <?= $name ?>!!!
<?php include __DIR__ . '/../footer.php'; ?>

</body>
</html>