const $ = (id) => document.getElementById(id);

const MAX_FOTO = 5;
const MAX_MB = 2;

const form = $('form-jual');
const input = $('foto');
const picker = $('picker');
const drop = $('drop');
const emptyState = $('empty-state');
const previewState = $('preview-state');
const actions = $('photo-actions');
const mainPreview = $('main-preview');
const thumbs = $('thumbs');
const msg = $('foto-msg');
const btnTambah = $('btn-tambah');

let files = [];
let urls = [];
let current = 0;
let mode = 'add';

function showMsg(list) {
    msg.textContent = list.join(' | ');
    msg.classList.toggle('hidden', list.length === 0);
}

function filterFiles(list) {
    const ok = [], errors = [];
    for (const f of list) {
        if (!['image/jpeg', 'image/png'].includes(f.type)) { errors.push(f.name + ': format harus JPG/PNG'); continue; }
        if (f.size > MAX_MB * 1024 * 1024) { errors.push(f.name + ': maksimal ' + MAX_MB + ' MB'); continue; }
        ok.push(f);
    }
    return { ok, errors };
}

function sync() {
    const dt = new DataTransfer();
    files.forEach((f) => dt.items.add(f));
    input.files = dt.files;
    render();
}

function render() {
    urls.forEach((u) => URL.revokeObjectURL(u));
    urls = files.map((f) => URL.createObjectURL(f));

    const has = files.length > 0;
    emptyState.classList.toggle('hidden', has);
    previewState.classList.toggle('hidden', !has);
    actions.classList.toggle('hidden', !has);
    if (!has) return;

    if (current >= files.length) current = 0;
    mainPreview.src = urls[current];

    thumbs.innerHTML = '';
    files.forEach((f, i) => {
        const wrap = document.createElement('div');
        wrap.className = 'relative';

        const img = document.createElement('img');
        img.src = urls[i];
        img.alt = 'Foto ' + (i + 1);
        img.className = 'w-14 h-14 rounded-xl object-cover cursor-pointer border-2 ' + (i === current ? 'border-[#076653]' : 'border-transparent');
        img.addEventListener('click', () => { current = i; render(); });

        const del = document.createElement('button');
        del.type = 'button';
        del.setAttribute('aria-label', 'Hapus foto');
        del.className = 'absolute -top-1.5 -right-1.5 w-5 h-5 rounded-full bg-slate-900 text-white text-[11px] leading-none flex items-center justify-center';
        del.textContent = '×';
        del.addEventListener('click', () => { files.splice(i, 1); showMsg([]); sync(); });

        wrap.append(img, del);
        thumbs.appendChild(wrap);
    });

    const penuh = files.length >= MAX_FOTO;
    btnTambah.disabled = penuh;
    btnTambah.classList.toggle('opacity-50', penuh);
    btnTambah.classList.toggle('cursor-not-allowed', penuh);
}

function openPicker(m) {
    mode = m;
    picker.click();
}
$('btn-pilih').addEventListener('click', () => openPicker('add'));
$('btn-ganti').addEventListener('click', () => openPicker('replace'));
btnTambah.addEventListener('click', () => openPicker('add'));

picker.addEventListener('change', () => {
    const { ok, errors } = filterFiles([...picker.files]);
    files = mode === 'replace' ? ok.slice(0, MAX_FOTO) : files.concat(ok).slice(0, MAX_FOTO);
    if (mode === 'replace') current = 0;
    if (files.length >= MAX_FOTO && ok.length) errors.push('Maksimal ' + MAX_FOTO + ' foto');
    picker.value = '';
    showMsg(errors);
    sync();
});

drop.addEventListener('dragover', (e) => { e.preventDefault(); drop.classList.add('ring-4', 'ring-emerald-200'); });
drop.addEventListener('dragleave', () => drop.classList.remove('ring-4', 'ring-emerald-200'));
drop.addEventListener('drop', (e) => {
    e.preventDefault();
    drop.classList.remove('ring-4', 'ring-emerald-200');
    const { ok, errors } = filterFiles([...e.dataTransfer.files]);
    files = files.concat(ok).slice(0, MAX_FOTO);
    showMsg(errors);
    sync();
});

const desc = $('deskripsi');
const counter = $('desc-counter');
function updateCounter() {
    const n = desc.value.trim().length;
    counter.textContent = n >= 30 ? n + ' karakter' : 'Minimal 30 karakter (' + n + '/30)';
    counter.classList.toggle('text-[#076653]', n >= 30);
    counter.classList.toggle('text-slate-400', n < 30);
}
desc.addEventListener('input', updateCounter);
updateCounter();

$('harga').addEventListener('input', (e) => { e.target.value = e.target.value.replace(/\D/g, ''); });
$('jarak').addEventListener('input', (e) => {
    e.target.value = e.target.value.replace(',', '.').replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');
});

const tombol = form.querySelectorAll('button[type="submit"]');
form.addEventListener('submit', (e) => {
    const aksi = (e.submitter && e.submitter.value) || 'upload';

    if (aksi === 'upload' && files.length === 0) {
        e.preventDefault();
        showMsg(['Tambahkan minimal 1 foto produk.']);
        drop.scrollIntoView({ behavior: 'smooth', block: 'center' });
        return;
    }

    const hidden = document.createElement('input');
    hidden.type = 'hidden';
    hidden.name = 'aksi';
    hidden.value = aksi;
    form.appendChild(hidden);

    tombol.forEach((b) => { b.disabled = true; b.classList.add('opacity-70', 'cursor-not-allowed'); });
});

addEventListener('pageshow', () => {
    tombol.forEach((b) => { b.disabled = false; b.classList.remove('opacity-70', 'cursor-not-allowed'); });
});