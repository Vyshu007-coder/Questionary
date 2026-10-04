<?php
// Main settings. Most requirement changes should only need edits in here
// or in the two files inside /data.

return [

    // Title shown at the top of every page
    'site_title' => 'Questionnaire',

    // Timezone used for the timestamps in the responses file
    'timezone' => 'Asia/Kolkata',

    // How many questions to pick from each level for every attempt
    'questions_per_level' => [
        'low'    => 1,
        'medium' => 2,
        'severe' => 3,
    ],

    // Shuffle the order of the 6 questions? Shuffle the answer options?
    'shuffle_questions' => true,
    'shuffle_options'   => true,

    // Data files (keep these out of public reach, see README)
    'allowed_emails_file' => __DIR__ . '/data/allowed_emails.txt',
    'questions_file'      => __DIR__ . '/data/questions.json',
    'responses_file'      => __DIR__ . '/data/responses.csv',

    // Text shown on the thank you page
    'thanks_message' => 'Your answers have been saved.',
];
