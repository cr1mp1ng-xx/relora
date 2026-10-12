const $ = (id) => document.getElementById(id);
const card = $('card');
const panes = { signin: $('pane-signin'), signup: $('pane-signup') };
const promos = { signin: $('promo-signin'), signup: $('promo-signup') };

const DURATION = 850;
const ease = (t) => t < .5 ? 4 * t * t * t : 1 - Math.pow(-2 * t + 2, 3) / 2;

let p = 0, raf = 0, cw = 1100;

function render(v) {
    p = v;
    const s = Math.sin(Math.PI * v);
    const R = cw * .165;
    const extra = 40 * s * (cw / 1100);
    const rl = R * Math.pow(1 - v, 2.2) + extra;
    const rr = R * Math.pow(v, 2.2) + extra;
    const st = card.style;
    st.setProperty('--p', v.toFixed(4));
    st.setProperty('--s', s.toFixed(4));
    st.setProperty('--rl', rl.toFixed(1));
    st.setProperty('--rr', rr.toFixed(1));
}

function measure() {
    cw = card.offsetWidth;
    card.style.setProperty('--cw', cw);
    card.style.setProperty('--h1', panes.signin.offsetHeight);
    card.style.setProperty('--h2', panes.signup.offsetHeight);
    render(p);
}
addEventListener('resize', measure);

function go(mode, animate = true) {
    const up = mode === 'signup';
    const to = up ? 1 : 0;

    for (const k of ['signin', 'signup']) {
        const on = (k === 'signup') === up;
        panes[k].inert = !on;
        promos[k].inert = !on;
    }
    card.dataset.mode = mode;
    document.title = (up ? 'Sign Up' : 'Sign In') + ' - Relora';
    try { history.replaceState(null, '', '#' + mode); } catch (_) {}

    cancelAnimationFrame(raf);
    const from = p;
    const dist = Math.abs(to - from);
    if (!animate || !dist) return render(to);

    const dur = DURATION * dist;
    const t0 = performance.now();
    const tick = (now) => {
        const t = Math.min((now - t0) / dur, 1);
        render(from + (to - from) * ease(t));
        if (t < 1) raf = requestAnimationFrame(tick);
    };
    raf = requestAnimationFrame(tick);

    if (innerWidth < 768) scrollTo({ top: 0, behavior: 'smooth' });
}

document.querySelectorAll('[data-go]').forEach((el) => {
    el.addEventListener('click', (e) => {
        e.preventDefault();
        go(el.dataset.go);
    });
});

measure();
new ResizeObserver(measure).observe(panes.signin);
new ResizeObserver(measure).observe(panes.signup);

go(card.dataset.start === 'signup' ? 'signup' : 'signin', false);
addEventListener('hashchange', () => go(location.hash === '#signup' ? 'signup' : 'signin'));