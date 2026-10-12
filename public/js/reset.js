const form = document.getElementById('form-reset');
const msg = document.getElementById('msg-reset');
const btn = document.getElementById('btn-reset');
const btnText = btn.textContent.trim();

function message(text, type) {
    msg.textContent = text;
    msg.className = 'msg ' + type;
    msg.hidden = false;
}

function setLoading(on) {
    btn.disabled = on;
    btn.textContent = on ? 'MEMPROSES...' : btnText;
    btn.classList.toggle('opacity-70', on);
    btn.classList.toggle('cursor-not-allowed', on);
}

form.addEventListener('submit', (e) => {
    const email = form.email.value.trim();
    const pass = form.password.value;
    const pass2 = form.password_confirmation.value;

    let error = '';
    if (!email) error = 'Isi email kamu dulu.';
    else if (!/^\S+@\S+\.\S+$/.test(email)) error = 'Format email belum benar.';
    else if (pass.length < 8) error = 'Password minimal 8 karakter.';
    else if (pass !== pass2) error = 'Konfirmasi password tidak sama.';

    if (error) {
        e.preventDefault();
        return message(error, 'error');
    }

    setLoading(true); 
});

addEventListener('pageshow', () => setLoading(false));