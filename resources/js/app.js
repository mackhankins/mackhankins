import './bootstrap';

// Scroll reveal observer
document.addEventListener('DOMContentLoaded', () => {
    const reveals = document.querySelectorAll('.reveal');

    if (reveals.length === 0) return;

    const observer = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('revealed');
                    observer.unobserve(entry.target);
                }
            });
        },
        { threshold: 0.1, rootMargin: '0px 0px -40px 0px' }
    );

    reveals.forEach((el) => observer.observe(el));
});

// Card glow effect — track mouse position
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.card-glow').forEach((card) => {
        card.addEventListener('mousemove', (e) => {
            const rect = card.getBoundingClientRect();
            card.style.setProperty('--mouse-x', `${e.clientX - rect.left}px`);
            card.style.setProperty('--mouse-y', `${e.clientY - rect.top}px`);
        });
    });
});

// Reading progress bar
document.addEventListener('DOMContentLoaded', () => {
    const bar = document.getElementById('reading-progress-bar');
    const article = document.getElementById('article');

    if (!bar || !article) return;

    window.addEventListener('scroll', () => {
        const articleTop = article.offsetTop;
        const articleHeight = article.offsetHeight;
        const scrollY = window.scrollY;
        const windowHeight = window.innerHeight;

        const progress = Math.min(1, Math.max(0, (scrollY - articleTop) / (articleHeight - windowHeight)));
        bar.style.width = `${progress * 100}%`;
    }, { passive: true });
});

// Mobile nav toggle
document.addEventListener('DOMContentLoaded', () => {
    const toggle = document.getElementById('mobile-nav-toggle');
    const menu = document.getElementById('mobile-nav-menu');

    if (!toggle || !menu) return;

    toggle.addEventListener('click', () => {
        menu.classList.toggle('hidden');
        const isOpen = !menu.classList.contains('hidden');
        toggle.setAttribute('aria-expanded', isOpen);
    });
});
