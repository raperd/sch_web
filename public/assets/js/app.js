/* sch_web — Public JS */
'use strict';

document.addEventListener('DOMContentLoaded', () => {

    // ---- Navbar scroll effect ----
    const navbar = document.querySelector('.navbar-main');
    if (navbar) {
        const onScroll = () => navbar.classList.toggle('scrolled', window.scrollY > 60);
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
