const $ = (id) => document.getElementById(id);

const modal = $('filter-modal');
const form = $('filter-form');
const range = $('filter-jarak');
const jarakLabel = $('filter-jarak-label');
const hargaView = $('filter-harga-view');
const hargaHidden = $('filter-harga');
const clearBtn = $('filter-harga-clear');
const jarakPills = [...modal.querySelectorAll('[data-jarak]')];
const hargaPills = [...modal.querySelectorAll('[data-harga]')];

function openModal() {
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    document.body.classList.add('overflow-hidden');
}

function closeModal() {
    modal.classList.add('hidden');
    modal.classList.remove('flex');
    document.body.classList.remove('overflow-hidden');
}

document.querySelectorAll('[data-open-filter]').forEach((b) => b.addEventListener('click', openModal));
modal.querySelectorAll('[data-filter-close]').forEach((el) => el.addEventListener('click', closeModal));
addEventListener('keydown', (e) => { if (e.key === 'Escape') closeModal(); });

function setJarak(v) {
    v = Math.min(50, Math.max(1, Number(v) || 50));
    range.value = v;
    jarakLabel.textContent = v + ' km';
    jarakPills.forEach((p) => p.classList.toggle('is-on', Number(p.dataset.jarak) === v));
}
range.addEventListener('input', () => setJarak(range.value));
jarakPills.forEach((p) => p.addEventListener('click', () => setJarak(p.dataset.jarak)));

function setHarga(raw) {
    const digits = String(raw || '').replace(/\D/g, '').slice(0, 9);
    hargaHidden.value = digits;
    hargaView.value = digits ? Number(digits).toLocaleString('id-ID') : '';
    clearBtn.classList.toggle('invisible', !digits);
    hargaPills.forEach((p) => p.classList.toggle('is-on', String(p.dataset.harga) === (digits || '0')));
}

hargaView.addEventListener('input', () => setHarga(hargaView.value));
hargaPills.forEach((p) => p.addEventListener('click', () => setHarga(p.dataset.harga === '0' ? '' : p.dataset.harga)));
clearBtn.addEventListener('click', () => { setHarga(''); hargaView.focus(); });

form.addEventListener('submit', () => {
    if (!hargaHidden.value) hargaHidden.disabled = true;
    if (Number(range.value) === 50) range.disabled = true;
});

addEventListener('pageshow', () => { 
    hargaHidden.disabled = false; range.disabled = false; 
});

setJarak(range.value);
setHarga(hargaHidden.value);