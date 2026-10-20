const csrf = document.querySelector('meta[name="csrf-token"]').content;

document.querySelectorAll('[data-fav]').forEach((btn) => {
    btn.addEventListener('click', async () => {
        const sebelum = btn.classList.contains('is-saved');
        btn.classList.toggle('is-saved', !sebelum);
        btn.disabled = true;

        try {
            const res = await fetch(btn.dataset.url, {
                method: 'POST',
                headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': csrf },
            });
            if (!res.ok) throw new Error('gagal');
            const data = await res.json();
            btn.classList.toggle('is-saved', data.disimpan);
        } catch (e) {
            btn.classList.toggle('is-saved', sebelum);
            alert('Wishlist gagal diperbarui. Coba lagi.');
        } finally {
            btn.disabled = false;
        }
    });
});