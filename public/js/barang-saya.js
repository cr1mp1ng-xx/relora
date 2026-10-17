const grid = document.getElementById('barang-grid');
const tabs = [...document.querySelectorAll('[data-tab]')];
const sortEl = document.getElementById('sort-barang');
const uploadCard = grid.querySelector('[data-upload]');
const kosong = document.getElementById('tab-kosong');

let aktif = 'semua';

function terapkan() {
    const items = [...grid.querySelectorAll('[data-item]')];
    let tampil = 0;
    items.forEach((item) => {
        const sembunyi = aktif !== 'semua' && item.dataset.status !== aktif;
        item.classList.toggle('hidden', sembunyi);
        if (!sembunyi) tampil++;
    });
    kosong.classList.toggle('hidden', tampil > 0);
}

tabs.forEach((tab) => {
    tab.addEventListener('click', () => {
        aktif = tab.dataset.tab;
        tabs.forEach((t) => t.classList.toggle('is-on', t === tab));
        terapkan();
    });
});

sortEl.addEventListener('change', () => {
    const items = [...grid.querySelectorAll('[data-item]')];
    const by = sortEl.value;

    items.sort((a, b) => {
        if (by === 'murah') return a.dataset.price - b.dataset.price;
        if (by === 'mahal') return b.dataset.price - a.dataset.price;
        if (by === 'lama') return a.dataset.time - b.dataset.time;
        return b.dataset.time - a.dataset.time; // terbaru diunggah
    });

    items.forEach((item) => grid.appendChild(item));
    grid.appendChild(uploadCard);
});