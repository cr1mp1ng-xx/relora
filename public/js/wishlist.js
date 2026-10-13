const grid = document.getElementById('wishlist-grid');
const sortEl = document.getElementById('sort');
const countEl = document.getElementById('wishlist-count');
const emptyEl = document.getElementById('wishlist-empty');

function updateCount() {
    const total = grid.querySelectorAll('[data-card]').length;
    countEl.textContent = total;
    emptyEl.classList.toggle('hidden', total > 0);
}

sortEl.addEventListener('change', () => {
    const cards = [...grid.querySelectorAll('[data-card]')];
    const by = sortEl.value;

    cards.sort((a, b) => {
        if (by === 'murah') return a.dataset.price - b.dataset.price;
        if (by === 'mahal') return b.dataset.price - a.dataset.price;
        return b.dataset.saved - a.dataset.saved;
    });

    cards.forEach((card) => grid.appendChild(card));
});

grid.addEventListener('click', (e) => {
    const btn = e.target.closest('[data-remove]');
    if (!btn) return;
    btn.closest('[data-card]').remove();
    updateCount();
});