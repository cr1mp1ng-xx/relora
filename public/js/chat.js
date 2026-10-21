const $ = (id) => document.getElementById(id);

const area = $('area-pesan');
const input = $('input-pesan');
const btn = $('btn-kirim');
const csrf = document.querySelector('meta[name="csrf-token"]').content;

const cari = $('cari-obrolan');
if (cari) {
    cari.addEventListener('input', () => {
        const kata = cari.value.trim().toLowerCase();
        document.querySelectorAll('[data-cari]').forEach((el) => {
            el.classList.toggle('hidden', kata !== '' && !el.dataset.cari.includes(kata));
        });
    });
}

if (area) {
    const tunggu = (ms) => new Promise((r) => setTimeout(r, ms));
    const keBawah = () => { area.scrollTop = area.scrollHeight; };
    const klon = (id) => $(id).content.firstElementChild.cloneNode(true);
    const jamSekarang = () => new Date().toLocaleTimeString('en-GB', { hour: '2-digit', minute: '2-digit', timeZone: 'Asia/Jakarta' });

    let sedang = false;
    keBawah();

    function sisipkan(el) {
        const kosong = $('kosong-pesan');
        if (kosong) kosong.remove();
        area.appendChild(el);
        keBawah();
        return el;
    }

    function pesanSaya(teks, jam) {
        const el = klon('tpl-saya');
        el.querySelector('[data-isi]').textContent = teks;
        el.querySelector('[data-jam]').textContent = jam;
        return sisipkan(el);
    }

    function pesanPenjual(teks, jam) {
        const el = klon('tpl-penjual');
        el.querySelector('[data-isi]').textContent = teks;
        el.querySelector('[data-jam]').textContent = jam;
        return sisipkan(el);
    }

    function pesanRingkasan(d) {
        const el = klon('tpl-ringkasan');
        el.querySelector('[data-lokasi]').textContent = d.lokasi;
        el.querySelector('[data-waktu]').textContent = d.waktu;
        el.querySelector('[data-uang]').textContent = d.uang;
        el.querySelector('[data-status]').textContent = d.status;
        return sisipkan(el);
    }

    function pesanKetik() {
        return sisipkan(klon('tpl-ketik'));
    }

    function perbaruiDaftar(data) {
        const item = document.querySelector('.chat-item.is-on');
        if (!item) return;
        item.querySelector('[data-preview]').textContent = data.preview;
        item.querySelector('[data-label]').textContent = data.label;
    }

    async function kirim() {
        const teks = input.value.trim();
        if (!teks || sedang) return;

        sedang = true;
        btn.disabled = true;
        btn.classList.add('opacity-70', 'cursor-not-allowed');
        input.value = '';

        const elSaya = pesanSaya(teks, jamSekarang());
        const ketik = pesanKetik();
        const mulai = Date.now();

        try {
            const res = await fetch(area.dataset.url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrf,
                },
                body: JSON.stringify({ teks }),
            });
            if (!res.ok) throw new Error('gagal');
            const data = await res.json();

            elSaya.querySelector('[data-jam]').textContent = data.saya.jam;

            await tunggu(Math.max(0, 1100 - (Date.now() - mulai)));
            ketik.remove();
            pesanPenjual(data.balasan.teks, data.balasan.jam);

            if (data.ringkasan) {
                await tunggu(450);
                pesanRingkasan(data.ringkasan);
            }
            perbaruiDaftar(data);
        } catch (e) {
            ketik.remove();
            elSaya.remove();
            input.value = teks;
            alert('Pesan gagal dikirim. Coba lagi.');
        } finally {
            sedang = false;
            btn.disabled = false;
            btn.classList.remove('opacity-70', 'cursor-not-allowed');
            input.focus();
        }
    }

    btn.addEventListener('click', kirim);
    input.addEventListener('keydown', (e) => {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            kirim();
        }
    });
}