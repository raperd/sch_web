/* sch_web — Public JS */
'use strict';

/* =====================================================================
   APP DIALOG — Custom themed confirm / alert (pengganti native dialog)
   Penggunaan:
     AppDialog.confirm('Yakin hapus?')               → Promise<boolean>
     AppDialog.confirm('Pesan', {title:'Konfirmasi', okLabel:'Hapus', okClass:'btn-danger'})
     AppDialog.alert('Berhasil!', {type:'success'})  → Promise<void>
     AppDialog.alert('Error!', {type:'danger'})
   ===================================================================== */
const AppDialog = (() => {
    let _modal = null;

    function _getModal() {
        if (_modal) return _modal;
        const el = document.createElement('div');
        el.className = 'modal fade';
        el.id = '_appDialog';
        el.tabIndex = -1;
        el.setAttribute('aria-hidden', 'true');
        el.innerHTML = `
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header border-0 pb-0" id="_dlgHeader">
                    <div class="d-flex align-items-center gap-2">
                        <i class="fs-5" id="_dlgIcon"></i>
                        <h6 class="modal-title fw-bold mb-0" id="_dlgTitle"></h6>
                    </div>
                    <button type="button" class="btn-close btn-sm" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body pt-2" id="_dlgBody"></div>
                <div class="modal-footer border-0 pt-0 gap-2" id="_dlgFooter"></div>
            </div>
        </div>`;
        document.body.appendChild(el);
        _modal = new bootstrap.Modal(el, { backdrop: 'static', keyboard: false });
        return _modal;
    }

    const _icons = {
        primary: 'bi bi-info-circle-fill text-primary',
        success: 'bi bi-check-circle-fill text-success',
        danger:  'bi bi-exclamation-triangle-fill text-danger',
        warning: 'bi bi-exclamation-circle-fill text-warning',
        info:    'bi bi-info-circle-fill text-info',
    };

    function confirm(message, opts = {}) {
        const { title = 'Konfirmasi', okLabel = 'Ya, Lanjutkan', okClass = 'btn-primary',
                cancelLabel = 'Batal', type = 'primary' } = (typeof opts === 'string')
            ? { title: opts } : opts;
        const m = _getModal();
        const el = document.getElementById('_appDialog');
        el.querySelector('#_dlgTitle').textContent = title;
        el.querySelector('#_dlgIcon').className = `fs-5 ${_icons[type] || _icons.primary}`;
        el.querySelector('#_dlgBody').innerHTML =
            `<p class="mb-0 text-secondary" style="font-size:.92rem;">${message}</p>`;
        el.querySelector('#_dlgFooter').innerHTML =
            `<button type="button" class="btn btn-sm btn-light" data-bs-dismiss="modal">${cancelLabel}</button>
             <button type="button" class="btn btn-sm ${okClass}" id="_dlgOk">${okLabel}</button>`;
        return new Promise(resolve => {
            const okBtn = el.querySelector('#_dlgOk');
            const onOk = () => { cleanup(); resolve(true); };
            const onHide = () => { cleanup(); resolve(false); };
            const onHidden = () => el.removeEventListener('hidden.bs.modal', onHide);
            function cleanup() {
                okBtn.removeEventListener('click', onOk);
                el.removeEventListener('hide.bs.modal', onHide);
                m.hide();
            }
            okBtn.addEventListener('click', onOk, { once: true });
            el.addEventListener('hide.bs.modal', onHide, { once: true });
            m.show();
        });
    }

    function alert(message, opts = {}) {
        const { title = 'Informasi', okLabel = 'OK', type = 'info' } = (typeof opts === 'string')
            ? { title: opts } : opts;
        const m = _getModal();
        const el = document.getElementById('_appDialog');
        el.querySelector('#_dlgTitle').textContent = title;
        el.querySelector('#_dlgIcon').className = `fs-5 ${_icons[type] || _icons.info}`;
        el.querySelector('#_dlgBody').innerHTML =
            `<p class="mb-0 text-secondary" style="font-size:.92rem;">${message}</p>`;
        el.querySelector('#_dlgFooter').innerHTML =
            `<button type="button" class="btn btn-sm btn-primary" id="_dlgOk">${okLabel}</button>`;
        return new Promise(resolve => {
            el.querySelector('#_dlgOk').addEventListener('click', () => { m.hide(); resolve(); }, { once: true });
            m.show();
        });
    }

    return { confirm, alert };
})();

/* Intercept semua <form data-confirm="..."> dan <a data-confirm="..."> */
document.addEventListener('submit', function(e) {
    const form = e.target;
    const msg = form.dataset.confirm;
    if (!msg) return;
    if (form._dlgConfirmed) { form._dlgConfirmed = false; return; }
    e.preventDefault();
    AppDialog.confirm(msg, { okLabel: form.dataset.confirmOk || 'Lanjutkan',
        okClass: form.dataset.confirmClass || 'btn-primary',
        type: form.dataset.confirmType || 'primary' })
        .then(ok => { if (ok) { form._dlgConfirmed = true; form.submit(); } });
}, true);

document.addEventListener('click', function(e) {
    const a = e.target.closest('a[data-confirm]');
    if (!a) return;
    e.preventDefault();
    const msg = a.dataset.confirm;
    AppDialog.confirm(msg, { okLabel: a.dataset.confirmOk || 'Lanjutkan',
        okClass: a.dataset.confirmClass || 'btn-primary',
        type: a.dataset.confirmType || 'primary' })
        .then(ok => { if (ok) window.location.href = a.href; });
}, true);

document.addEventListener('DOMContentLoaded', () => {

    // ---- Navbar scroll effect ----
    const navbar = document.querySelector('.navbar-main');
    if (navbar) {
        const onScroll = () => {
            const isScrolled = window.scrollY > 60;
            navbar.classList.toggle('scrolled', isScrolled);
            document.body.classList.toggle('scrolled', isScrolled);
        };
        window.addEventListener('scroll', onScroll, { passive: true });
        onScroll();
    }

    // ---- Back to top ----
    const btn = document.getElementById('back-to-top');
    if (btn) {
        window.addEventListener('scroll', () => btn.classList.toggle('show', window.scrollY > 300), { passive: true });
        btn.addEventListener('click', () => window.scrollTo({ top: 0, behavior: 'smooth' }));
    }

    // ---- Hero carousel auto-play (if present) ----
    const heroCarousel = document.getElementById('heroCarousel');
    if (heroCarousel) {
        new bootstrap.Carousel(heroCarousel, { interval: 5000, ride: 'carousel' });
    }

    // ---- Lazy-load images ----
    if ('IntersectionObserver' in window) {
        const imgs = document.querySelectorAll('img[data-src]');
        const io = new IntersectionObserver((entries) => {
            entries.forEach(e => {
                if (e.isIntersecting) {
                    e.target.src = e.target.dataset.src;
                    e.target.removeAttribute('data-src');
                    io.unobserve(e.target);
                }
            });
        }, { rootMargin: '200px' });
        imgs.forEach(img => io.observe(img));
    }

    // ---- Smooth anchor scroll ----
    document.querySelectorAll('a[href^="#"]').forEach(a => {
        a.addEventListener('click', e => {
            const target = document.querySelector(a.getAttribute('href'));
            if (target) {
                e.preventDefault();
                target.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        });
    });

    // ---- Auto-dismiss alerts ----
    document.querySelectorAll('.alert-auto-dismiss').forEach(el => {
        setTimeout(() => {
            const bsAlert = bootstrap.Alert.getOrCreateInstance(el);
            if (bsAlert) bsAlert.close();
        }, 5000);
    });
});
