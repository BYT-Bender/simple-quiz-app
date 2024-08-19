function filterQuizzes() {
    const searchInput = document.getElementById('search-input').value.toLowerCase();
    const quizzes = document.querySelectorAll('.quiz');

    quizzes.forEach(quiz => {
        const title = quiz.querySelector('h2').textContent.toLowerCase();
        const description = quiz.querySelector('p').textContent.toLowerCase();
        if (title.includes(searchInput) || description.includes(searchInput)) {
            quiz.style.display = '';
        } else {
            quiz.style.display = 'none';
        }
    });
}