(function () {
    var KUNCI = 'tema';

    function baca() {
        try { return localStorage.getItem(KUNCI) || 'default'; } catch (e) { return 'default'; }
    }
    function simpan(tema) {
        try { localStorage.setItem(KUNCI, tema); } catch (e) {}
    }
    function terapkan(tema) {
        var gelap = tema === 'gelap' ||
            (tema === 'default' && window.matchMedia && matchMedia('(prefers-color-scheme: dark)').matches);
        document.documentElement.setAttribute('data-theme', gelap ? 'dark' : 'light');
    }

    terapkan(baca());

    document.addEventListener('DOMContentLoaded', function () {
        var tombol = document.querySelectorAll('[data-tema]');
        function tandai(tema) {
            tombol.forEach(function (b) { b.classList.toggle('is-on', b.dataset.tema === tema); });
        }

        tandai(baca());
        tombol.forEach(function (b) {
            b.addEventListener('click', function () {
                simpan(b.dataset.tema);
                terapkan(b.dataset.tema);
                tandai(b.dataset.tema);
            });
        });
    });
})();