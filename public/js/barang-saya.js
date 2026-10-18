const $ = (id) => document.getElementById(id);

const grid = $('barang-grid');
const tabs = [...document.querySelectorAll('[data-tab]')];
const sortEl = $('sort-barang');
const uploadCard = grid.querySelector('[data-upload]');
const kosong = $('tab-kosong');
const csrf = document.querySelector('meta[name="csrf-token"]').content;

const LABEL = { semua: 'Semua', tersedia: 'Tersedia', dipesan: 'Dipesan', terjual: 'Terjual' };
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

function hitung() {
    const items = [...grid.querySelectorAll('[data-item]')];
    const n = { semua: items.length, tersedia: 0, dipesan: 0, terjual: 0 };
    items.forEach((i) => { if (n[i.dataset.status] !== undefined) n[i.dataset.status]++; });
    tabs.forEach((t) => { t.textContent = LABEL[t.dataset.tab] + ' (' + n[t.dataset.tab] + ')'; });
    $('jumlah-barang').textContent = items.length + ' Barang';
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

async function ubahStatus(card, status) {
    if (card.dataset.id) {
        try {
            const res = await fetch(card.dataset.url, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrf,
                },
                body: JSON.stringify({ status }),
            });
            if (!res.ok) throw new Error('gagal');
        } catch (e) {
            alert('Status barang gagal disimpan. Coba lagi.');
            return false;
        }
    }

    if (status === 'arsip') {
        card.remove();
    } else {
        const cetakan = document.querySelector('template[data-tpl="' + status + '"]');
        const area = card.querySelector('[data-status-area]');
        card.dataset.status = status;
        area.innerHTML = '';
        area.appendChild(cetakan.content.cloneNode(true));
    }

    hitung();
    terapkan();
    return true;
}

const modal = $('status-modal');
const radios = [...modal.querySelectorAll('input[name="status-opsi"]')];
const btnSimpan = $('sm-simpan');
let target = null;

function bukaModal(card) {
    target = card;

    $('sm-judul').textContent = card.dataset.judul;
    $('sm-harga').textContent = 'Rp ' + Number(card.dataset.price).toLocaleString('id-ID');

    const img = card.querySelector('img');
    $('sm-foto').classList.toggle('hidden', !img);
    $('sm-foto-kosong').classList.toggle('hidden', !!img);
    if (img) $('sm-foto').src = img.src;

    radios.forEach((r) => { r.checked = r.value === card.dataset.status; });

    modal.classList.remove('hidden');
    modal.classList.add('flex');
    document.body.classList.add('overflow-hidden');
}

function tutupModal() {
    modal.classList.add('hidden');
    modal.classList.remove('flex');
    document.body.classList.remove('overflow-hidden');
    target = null;
}

modal.querySelectorAll('[data-sm-close]').forEach((el) => el.addEventListener('click', tutupModal));
addEventListener('keydown', (e) => { if (e.key === 'Escape') tutupModal(); });

btnSimpan.addEventListener('click', async () => {
    const pilih = radios.find((r) => r.checked);
    if (!target || !pilih) return;

    btnSimpan.disabled = true;
    btnSimpan.classList.add('opacity-70', 'cursor-not-allowed');
    const berhasil = pilih.value === target.dataset.status ? true : await ubahStatus(target, pilih.value);
    btnSimpan.disabled = false;
    btnSimpan.classList.remove('opacity-70', 'cursor-not-allowed');

    if (berhasil) tutupModal();
});

grid.addEventListener('click', (e) => {
    const ubah = e.target.closest('[data-ubah-status]');
    if (ubah) return bukaModal(ubah.closest('[data-item]'));

    const aksi = e.target.closest('[data-aksi]');
    if (!aksi) return;

    const card = aksi.closest('[data-item]');
    if (aksi.dataset.aksi === 'arsip') {
        if (confirm('Arsipkan barang ini? Barang tidak akan tampil lagi di daftar.')) ubahStatus(card, 'arsip');
    } else {
        ubahStatus(card, 'tersedia');
    }
});