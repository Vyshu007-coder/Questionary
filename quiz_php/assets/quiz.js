// Small helpers for the questionnaire page.
// The page still works without this file; the server checks everything again.

var form = document.getElementById('quiz-form');
var progress = document.getElementById('progress');
var button = document.getElementById('submit-btn');

var questions = form.querySelectorAll('.question');

function updateProgress() {
    var answered = 0;

    for (var i = 0; i < questions.length; i++) {
        if (questions[i].querySelector('input:checked')) {
            answered++;
            questions[i].classList.add('answered');
        } else {
            questions[i].classList.remove('answered');
        }
    }

    progress.textContent = answered + ' of ' + questions.length + ' answered';
    button.disabled = (answered < questions.length);
}

form.addEventListener('change', updateProgress);

// stop a double click from sending the form twice
form.addEventListener('submit', function () {
    button.disabled = true;
    button.textContent = 'Saving...';
});

updateProgress();
