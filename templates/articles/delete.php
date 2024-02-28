<?php include __DIR__ . '/../header.php'; ?>

    <h1>Cтатья удалена!</h1>
    <?php  if(!empty($error)): ?>
        <div style="color: red;"><?= $error ?></div>
    <?php endif; ?>



<?php include __DIR__ . '/../footer.php'; ?>