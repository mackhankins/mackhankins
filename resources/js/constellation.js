/**
 * Constellation mesh — animated particle network for the hero background.
 * Renders scattered nodes that drift and draw faint connecting lines
 * when within proximity, using the site's accent color palette.
 */
export function initConstellation(canvas) {
    const ctx = canvas.getContext('2d');
    const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    // Read CSS custom properties for theme-aware colors
    function getAccentColors() {
        const style = getComputedStyle(document.documentElement);
        return [
            style.getPropertyValue('--color-amber-accent').trim(),
            style.getPropertyValue('--color-teal-accent').trim(),
            style.getPropertyValue('--color-indigo-accent').trim(),
        ];
    }

    function hexToRgb(hex) {
        const result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hex);
        return result
            ? { r: parseInt(result[1], 16), g: parseInt(result[2], 16), b: parseInt(result[3], 16) }
            : { r: 200, g: 200, b: 200 };
    }

    // --- Configuration ---
    const CONNECTION_DISTANCE = 140;
    const NODE_COUNT_FACTOR = 0.000045; // nodes per px² — keeps density consistent
    const MIN_NODES = 30;
    const MAX_NODES = 90;
    const BASE_SPEED = 0.15;

    let width, height, nodes, colors, dpr;

    function resize() {
        dpr = window.devicePixelRatio || 1;
        const rect = canvas.parentElement.getBoundingClientRect();
        width = rect.width;
        height = rect.height;
        canvas.width = width * dpr;
        canvas.height = height * dpr;
        canvas.style.width = `${width}px`;
        canvas.style.height = `${height}px`;
        ctx.setTransform(dpr, 0, 0, dpr, 0, 0);
    }

    function createNodes() {
        colors = getAccentColors().map(hexToRgb);
        const area = width * height;
        const count = Math.max(MIN_NODES, Math.min(MAX_NODES, Math.round(area * NODE_COUNT_FACTOR)));

        nodes = Array.from({ length: count }, () => {
            const colorIndex = Math.floor(Math.random() * colors.length);
            const bright = Math.random() < 0.12; // ~12% are "bright" pulsing nodes
            return {
                x: Math.random() * width,
                y: Math.random() * height,
                vx: (Math.random() - 0.5) * BASE_SPEED,
                vy: (Math.random() - 0.5) * BASE_SPEED,
                radius: bright ? 2 : (0.8 + Math.random() * 1.2),
                color: colors[colorIndex],
                bright,
                phase: Math.random() * Math.PI * 2,
            };
        });
    }

    function draw(time) {
        ctx.clearRect(0, 0, width, height);

        const t = time * 0.001; // seconds

        // Move nodes
        if (!prefersReducedMotion) {
            for (const n of nodes) {
                n.x += n.vx;
                n.y += n.vy;

                // Wrap around edges with padding
                if (n.x < -20) n.x = width + 20;
                if (n.x > width + 20) n.x = -20;
                if (n.y < -20) n.y = height + 20;
                if (n.y > height + 20) n.y = -20;
            }
        }

        // Draw connections
        for (let i = 0; i < nodes.length; i++) {
            for (let j = i + 1; j < nodes.length; j++) {
                const dx = nodes[i].x - nodes[j].x;
                const dy = nodes[i].y - nodes[j].y;
                const dist = Math.sqrt(dx * dx + dy * dy);

                if (dist < CONNECTION_DISTANCE) {
                    const opacity = (1 - dist / CONNECTION_DISTANCE) * 0.12;
                    const c = nodes[i].color;
                    ctx.strokeStyle = `rgba(${c.r}, ${c.g}, ${c.b}, ${opacity})`;
                    ctx.lineWidth = 0.5;
                    ctx.beginPath();
                    ctx.moveTo(nodes[i].x, nodes[i].y);
                    ctx.lineTo(nodes[j].x, nodes[j].y);
                    ctx.stroke();
                }
            }
        }

        // Draw nodes
        for (const n of nodes) {
            let alpha;
            if (n.bright) {
                // Pulsing bright nodes
                alpha = 0.4 + 0.3 * Math.sin(t * 1.5 + n.phase);
            } else {
                alpha = 0.2 + 0.08 * Math.sin(t * 0.8 + n.phase);
            }

            ctx.fillStyle = `rgba(${n.color.r}, ${n.color.g}, ${n.color.b}, ${alpha})`;
            ctx.beginPath();
            ctx.arc(n.x, n.y, n.radius, 0, Math.PI * 2);
            ctx.fill();

            // Glow ring on bright nodes
            if (n.bright) {
                const glowAlpha = 0.08 + 0.06 * Math.sin(t * 1.5 + n.phase);
                ctx.strokeStyle = `rgba(${n.color.r}, ${n.color.g}, ${n.color.b}, ${glowAlpha})`;
                ctx.lineWidth = 1;
                ctx.beginPath();
                ctx.arc(n.x, n.y, n.radius + 3, 0, Math.PI * 2);
                ctx.stroke();
            }
        }
    }

    // --- Lifecycle ---
    let animFrame;

    function loop(time) {
        draw(time);
        animFrame = requestAnimationFrame(loop);
    }

    function init() {
        resize();
        createNodes();
        animFrame = requestAnimationFrame(loop);
    }

    // Debounced resize
    let resizeTimer;
    window.addEventListener('resize', () => {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(() => {
            resize();
            createNodes();
        }, 200);
    });

    // Re-read colors when theme changes
    const themeObserver = new MutationObserver(() => {
        const newColors = getAccentColors().map(hexToRgb);
        if (nodes) {
            nodes.forEach((n) => {
                n.color = newColors[Math.floor(Math.random() * newColors.length)];
            });
        }
    });
    themeObserver.observe(document.documentElement, { attributes: true, attributeFilter: ['class'] });

    init();

    return () => {
        cancelAnimationFrame(animFrame);
        themeObserver.disconnect();
        clearTimeout(resizeTimer);
    };
}
