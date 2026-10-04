<?php
// Login page: the user types an email and we check it against the allowed list.
require __DIR__ . '/includes/functions.php';

// already logged in, go straight to the questions
if (!empty($_SESSION['email']) && is_allowed_email($_SESSION['email'])) {
    redirect('quiz.php');
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = clean_email($_POST['email'] ?? '');

    if (is_allowed_email($email)) {
        session_regenerate_id(true);
        $_SESSION['email'] = $email;
        redirect('quiz.php');
    }
    $error = 'Sorry, this email address does not have access.';
}

include __DIR__ . '/includes/header.php';
?>

<div class="box">
    <h1>Welcome</h1>
    <p>Please enter your email address to start.</p>

    <?php if ($error): ?>
        <p class="error"><?= h($error) ?></p>
    <?php endif; ?>

    <form method="post" action="index.php">
        <label for="email">Email address</label>
        <input type="email" id="email" name="email" required autofocus>
        <button type="submit">Continue</button>
    </form>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
