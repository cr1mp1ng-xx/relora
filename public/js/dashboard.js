document.querySelectorAll('[data-fav]').forEach((btn) => {
    btn.addEventListener('click', () => btn.classList.toggle('is-saved'));
});