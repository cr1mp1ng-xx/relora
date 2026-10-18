// ini yaitu untuk edit barang: titik pada angka harga, penghitungan terhadap karakter deskripsi, pencegahan terhadap klik ganda
const $ = (id) => document.getElementById(id);

const harga = $('harga');
function formatHarga() {
    const digits = harga.value.replace(/\D/g, '').slice(0, 10);
    harga.value = digits ? Number(digits).toLocaleString('id-ID') : '';
}

harga.addEventListener('input', formatHarga);
formatHarga();

const desc = $('deskripsi');
const counter = $('desc-counter');
function hitung() {
    counter.textContent = desc.value.length + '/500';
}

desc.addEventListener('input', hitung);
hitung();

const form = $('form-edit');
const btn = $('btn-simpan');
form.addEventListener('submit', () => {
    btn.disabled = true;
    btn.textContent = 'Menyimpan...';
    btn.classList.add('opacity-70', 'cursor-not-allowed');
});

addEventListener('pageshow', () => {
    btn.disabled = false;
    btn.textContent = 'Simpan Perubahan';
    btn.classList.remove('opacity-70', 'cursor-not-allowed');
});