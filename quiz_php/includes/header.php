<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= h(config('site_title')) ?></title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>

<div class="top">
    <div class="wrap">
        <span class="site-name"><?= h(config('site_title')) ?></span>
        <?php if (!empty($_SESSION['email'])): ?>
            <span class="user">
                <?= h($_SESSION['email']) ?> &middot; <a href="logout.php">Log out</a>
            </span>
        <?php endif; ?>
    </div>
</div>

<div class="wrap">
