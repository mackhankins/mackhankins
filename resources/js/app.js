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

// Theme toggle
document.addEventListener('DOMContentLoaded', () => {
    function toggleTheme() {
        const isDark = document.documentElement.classList.contains('dark');

        if (isDark) {
            document.documentElement.classList.remove('dark');
            localStorage.setItem('theme', 'light');
        } else {
            document.documentElement.classList.add('dark');
            localStorage.setItem('theme', 'dark');
        }
    }

    document.querySelectorAll('#theme-toggle, #theme-toggle-mobile').forEach((btn) => {
        btn.addEventListener('click', toggleTheme);
    });
});

// Hero terminal — reveal lines sequentially
document.addEventListener('DOMContentLoaded', () => {
    const lines = document.querySelectorAll('.terminal-line');
    if (lines.length === 0) return;

    const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    if (prefersReducedMotion) {
        lines.forEach((line) => line.classList.add('visible'));
        return;
    }

    lines.forEach((line) => {
        const delay = parseInt(line.dataset.delay, 10) || 0;
        setTimeout(() => line.classList.add('visible'), delay);
    });
});

// Hero word rotation
document.addEventListener('DOMContentLoaded', () => {
    const el = document.querySelector('.hero-rotate-word');
    if (!el) return;

    const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    const words = ['tools', 'dashboards', 'pipelines', 'systems', 'applications'];
    let index = 0;

    setInterval(() => {
        if (prefersReducedMotion) {
            index = (index + 1) % words.length;
            el.textContent = words[index];
        } else {
            el.style.opacity = '0';
            setTimeout(() => {
                index = (index + 1) % words.length;
                el.textContent = words[index];
                el.style.opacity = '1';
            }, 300);
        }
    }, 3500);
});

// Mobile nav toggle
document.addEventListener('DOMContentLoaded', () => {
    const toggle = document.getElementById('mobile-nav-toggle');
    const menu = document.getElementById('mobile-nav-menu');

    if (!toggle || !menu) return;

    toggle.addEventListener('click', () => {
        const isOpen = menu.classList.toggle('grid-rows-[1fr]');
        menu.classList.toggle('grid-rows-[0fr]', !isOpen);
        menu.setAttribute('aria-hidden', !isOpen);
        toggle.setAttribute('aria-expanded', isOpen);
    });
});
