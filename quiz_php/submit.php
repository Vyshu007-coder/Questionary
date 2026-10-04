<?php
// Receives the answers, checks them and saves them to the responses file.
require __DIR__ . '/includes/functions.php';
require_login();

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_SESSION['attempt'])) {
    redirect('quiz.php');
}

$attempt = $_SESSION['attempt'];
$given   = $_POST['answer'] ?? [];
if (!is_array($given)) {
    $given = [];
}

$answers = [];      // question id => chosen option number
$missing = false;

foreach ($attempt['questions'] as $q) {
    $choice = $given[$q['id']] ?? null;

    // the answer must be one of the options we actually showed
    $valid = false;
    foreach ($q['options'] as $opt) {
        if ((string) $opt['number'] === (string) $choice) {
            $valid = true;
        }
    }

    if ($valid) {
        $answers[$q['id']] = (int) $choice;
    } else {
        $missing = true;
    }
}

if ($missing) {
    $_SESSION['error']    = 'Please answer every question before submitting.';
    $_SESSION['selected'] = $answers;
    redirect('quiz.php');
}

// Everything answered. Build one row per question.
$time = date('Y-m-d H:i:s');
$rows = [];

foreach ($attempt['questions'] as $q) {
    $number = $answers[$q['id']];

    // find the text of the chosen option
    $text = '';
    foreach ($q['options'] as $opt) {
        if ($opt['number'] === $number) {
            $text = $opt['text'];
        }
    }

    // is_correct stays empty when the question has no answer set
    $correct = ($q['answer'] === null) ? '' : (($number === $q['answer']) ? 1 : 0);

    $rows[] = [$time, $attempt['id'], $_SESSION['email'], $q['id'],
               $q['complexity'], $text, $correct];
}

save_responses($rows);

// this attempt is finished; the next visit to quiz.php starts a new one
unset($_SESSION['attempt']);
redirect('thanks.php');
