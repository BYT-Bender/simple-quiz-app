let currentTime = Math.floor(Date.now() / 1000);
let timeRemaining = duration - (currentTime - startTime);

function formatTime(seconds) {
    let hours = Math.floor(seconds / 3600);
    let minutes = Math.floor((seconds % 3600) / 60);
    let secs = seconds % 60;

    hours = hours.toString().padStart(2, '0');
    minutes = minutes.toString().padStart(2, '0');
    secs = secs.toString().padStart(2, '0');

    document.querySelector('.time-part:nth-child(1) .value').innerText = hours;
    document.querySelector('.time-part:nth-child(2) .value').innerText = minutes;
    document.querySelector('.time-part:nth-child(3) .value').innerText = secs;

    return `${hours}:${minutes}:${secs}`;
}

let timerInterval = setInterval(function() {
    timeRemaining--;
    formatTime(timeRemaining);

    if (timeRemaining <= 0) {
        clearInterval(timerInterval);
        window.location.href = "quiz.php?id="+quizId+"&finish=1";
    }
}, 1000);
