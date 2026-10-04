# Questionnaire (PHP + JavaScript)

A small questionnaire site. No database and no framework, just PHP, one
JavaScript file and a few plain files.

## What it does

1. **Login** - the user types their email. It is checked against `data/allowed_emails.txt`.
2. **Questions** - each attempt gets 1 low, 2 medium and 3 severe questions, picked at random
   from `data/questions.json`. Question order and answer options are shuffled. People can
   take as many attempts as they like.
3. **Submit** - all 6 must be answered. The answers are saved to `data/responses.csv`
   (open it in Excel) with: timestamp, attempt id, email, question id, complexity,
   selected answer, correct (1/0, blank if the question has no answer set).

## Files

| File | What it is for |
|------|----------------|
| `config.php` | Settings: questions per level, shuffling on/off, file locations, thank you text |
| `index.php` | Login page |
| `quiz.php` | Shows the 6 questions |
| `submit.php` | Checks the answers and saves them |
| `thanks.php` | Thank you page with a "Take another attempt" button |
| `logout.php` | Logs out |
| `includes/functions.php` | All the logic: email check, picking questions, saving |
| `includes/header.php`, `footer.php` | Page layout shared by all pages |
| `assets/style.css` | Look and feel |
| `assets/quiz.js` | Answer counter and button handling on the question page |
| `data/allowed_emails.txt` | Who may log in |
| `data/questions.json` | The question bank |
| `data/responses.csv` | Created automatically when the first answers come in |

## Running it

Needs PHP 7.4 or newer. Copy the folder to any PHP web host, or try it locally:

    php -S localhost:8000

then open http://localhost:8000

The `data` folder must be writable by the web server (so `responses.csv` can be created).

## Common changes

- **Add or remove a person:** edit `data/allowed_emails.txt`.
- **Add a question:** copy an existing block in `data/questions.json`, give it a new unique
  `id`, set `complexity` to `low`, `medium` or `severe`, list the `options`, and set `answer`
  to the position of the right option (first option is 0). Leave out the `answer` line if
  there is no right answer. Mind the commas between blocks.
- **Change how many questions per level:** `questions_per_level` in `config.php`.
- **Turn shuffling off:** `shuffle_questions` / `shuffle_options` in `config.php`.
- **Change the look:** `assets/style.css`.
- **Change the wording:** the text is written directly in `index.php`, `quiz.php` and `thanks.php`.
- **Save to a database instead of CSV:** only `save_responses()` in `includes/functions.php`
  needs to change.

## Keeping the data private

The `data` and `includes` folders have an `.htaccess` file that blocks browser access on
Apache. If the host uses Nginx (which ignores `.htaccess`), or you simply want to be safer,
move `data` outside the public web folder and update the three paths in `config.php`.

## Good to know

- Login only checks that the email is on the list. It does not prove the person owns that
  email. If that matters for the client, an emailed code could be added later.
- The correct answers stay on the server and are never sent to the browser.
