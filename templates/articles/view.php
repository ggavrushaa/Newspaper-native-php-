<?php include __DIR__ . '/../header.php'; ?>

<head>
<link rel="stylesheet" href="../../www/style.css">
</head>

<h1><?= $article->getName(); ?></h1>
<p><?= $article->getParsedText(); ?></p>
<p>Имя автора: <?= $article->getAuthor()->getNickname(); ?></p>

<?php if ($user !== null && $user->isAdmin()): ?>
    <a href="http://localhost:8081/MyProject/www/articles/<?= $article->getId() ?>/edit">Редактировать</a>
<?php endif; ?>
<br>
<?php if ($user !== null && $user->isAdmin()): ?>
    <a href="http://localhost:8081/MyProject/www/articles/<?= $article->getId() ?>/delete">Удалить</a>
<?php endif; ?>


<?php include __DIR__ . '/../footer.php'; ?>   