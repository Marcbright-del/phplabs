document.addEventListener('DOMContentLoaded', function() {
    const avatar = document.querySelector('.user-avatar');
    if (avatar) {
        const letter = avatar.textContent.trim();
        avatar.setAttribute('data-letter', letter);
    }
});