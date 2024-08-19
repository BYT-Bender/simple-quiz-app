document.querySelectorAll('.option').forEach(option => {
    option.addEventListener('click', function() {
        this.querySelector('input[type="radio"]').click();
    });
});