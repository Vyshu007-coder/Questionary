<?php
// The questionnaire. A new set of questions is created the first time this page
// loads, and kept in the session so a page refresh shows the same questions.
require __DIR__ . '/includes/functions.php';
require_login();

if (empty($_SESSION['attempt'])) {
    try {
        $_SESSION['attempt'] = [
            'id'        => bin2hex(random_bytes(8)),
            'questions' => build_attempt(),
        ];
    } catch (Exception $e) {
        include __DIR__ . '/includes/header.php';
        echo '<div class="box"><h1>Setup problem</h1><p class="error">' . h($e->getMessage()) . '</p></div>';
        include __DIR__ . '/includes/footer.php';
        exit;
    }
}

$questions = $_SESSION['attempt']['questions'];

// If submit.php sent the user back (some questions unanswered), show a message
// and tick the answers they already gave.
$error    = $_SESSION['error'] ?? '';
$selected = $_SESSION['selected'] ?? [];
unset($_SESSION['error'], $_SESSION['selected']);

include __DIR__ . '/includes/header.php';
?>

<h1>Questions</h1>
<p>Please answer all <?= count($questions) ?> questions, then press Submit.</p>

<?php if ($error): ?>
    <p class="error"><?= h($error) ?></p>
<?php endif; ?>

<form method="post" action="submit.php" id="quiz-form">

    <?php foreach ($questions as $i => $q): ?>
        <div class="question">
            <div class="question-title">
                <strong>Question <?= $i + 1 ?></strong>
                <span class="level <?= h($q['complexity']) ?>"><?= h($q['complexity']) ?></span>
            </div>
            <p><?= h($q['text']) ?></p>

            <?php foreach ($q['options'] as $opt): ?>
                <label class="option">
                    <input type="radio"
                           name="answer[<?= h($q['id']) ?>]"
                           value="<?= (int) $opt['number'] ?>"
                           <?= (isset($selected[$q['id']]) && (string) $selected[$q['id']] === (string) $opt['number']) ? 'checked' : '' ?>
                           required>
                    <?= h($opt['text']) ?>
                </label>
            <?php endforeach; ?>
        </div>
    <?php endforeach; ?>

    <p id="progress"></p>
    <button type="submit" id="submit-btn">Submit</button>
</form>

<script src="assets/quiz.js"></script>

<?php include __DIR__ . '/includes/footer.php'; ?>
