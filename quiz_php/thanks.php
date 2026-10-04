<?php
require __DIR__ . '/includes/functions.php';
require_login();

include __DIR__ . '/includes/header.php';
?>

<div class="box">
    <h1>Thank you</h1>
    <p><?= h(config('thanks_message')) ?></p>
    <p><a class="button" href="quiz.php">Take another attempt</a></p>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
