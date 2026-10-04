<?php
// Helper functions used by the pages.

session_start();

function config($key)
{
    static $settings = null;
    if ($settings === null) {
        $settings = require __DIR__ . '/../config.php';
    }
    return $settings[$key];
}

date_default_timezone_set(config('timezone'));

// Short name for escaping text before printing it in HTML
function h($text)
{
    return htmlspecialchars((string) $text, ENT_QUOTES, 'UTF-8');
}

function redirect($page)
{
    header('Location: ' . $page);
    exit;
}


/* ---------- login ---------- */

function clean_email($email)
{
    return strtolower(trim($email));
}

// The list is read every time, so changing the file takes effect straight away
function is_allowed_email($email)
{
    $file = config('allowed_emails_file');
    if ($email === '' || !is_file($file)) {
        return false;
    }

    foreach (file($file, FILE_IGNORE_NEW_LINES) as $line) {
        // ignore comments (anything after #) and blank lines
        $line = strtolower(trim(explode('#', $line)[0]));
        if ($line !== '' && $line === $email) {
            return true;
        }
    }
    return false;
}

// Call this at the top of any page that needs a logged in user
function require_login()
{
    if (empty($_SESSION['email']) || !is_allowed_email($_SESSION['email'])) {
        session_unset();
        redirect('index.php');
    }
}


/* ---------- questions ---------- */

function load_questions()
{
    $file = config('questions_file');
    $questions = json_decode(file_get_contents($file), true);

    if (!is_array($questions)) {
        throw new Exception('questions.json could not be read. Check for a missing comma or quote.');
    }
    return $questions;
}

// Builds a new attempt: picks the questions for each level, then shuffles
function build_attempt()
{
    $all = load_questions();
    $picked = [];

    foreach (config('questions_per_level') as $level => $howMany) {
        $pool = array_values(array_filter($all, function ($q) use ($level) {
            return $q['complexity'] === $level;
        }));

        if (count($pool) < $howMany) {
            throw new Exception("Not enough $level questions: need $howMany, found " . count($pool) . '.');
        }

        shuffle($pool);
        $picked = array_merge($picked, array_slice($pool, 0, $howMany));
    }

    if (config('shuffle_questions')) {
        shuffle($picked);
    }

    // Keep the original option number so we can check the answer later
    $attempt = [];
    foreach ($picked as $q) {
        $options = [];
        foreach ($q['options'] as $number => $text) {
            $options[] = ['number' => $number, 'text' => $text];
        }
        if (config('shuffle_options')) {
            shuffle($options);
        }

        $attempt[] = [
            'id'         => $q['id'],
            'complexity' => $q['complexity'],
            'text'       => $q['text'],
            'options'    => $options,
            'answer'     => isset($q['answer']) ? $q['answer'] : null,
        ];
    }
    return $attempt;
}


/* ---------- saving ---------- */

// Adds rows to the CSV file. Writes the header line if the file is new.
function save_responses($rows)
{
    $file = config('responses_file');
    $isNew = !is_file($file) || filesize($file) === 0;

    $handle = fopen($file, 'a');
    flock($handle, LOCK_EX);   // stops two people writing at the same moment

    if ($isNew) {
        fputcsv($handle, ['timestamp', 'attempt_id', 'email', 'question_id',
                          'complexity', 'selected_answer', 'is_correct']);
    }
    foreach ($rows as $row) {
        fputcsv($handle, $row);
    }

    flock($handle, LOCK_UN);
    fclose($handle);
}
