<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Notify Service — API</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/css/welcome.css">
</head>
<body>

    <div class="bg-grid"></div>
    <div class="bg-glow bg-glow--top"></div>
    <div class="bg-glow bg-glow--bottom"></div>
    <canvas class="particles" id="particles"></canvas>

    <nav class="nav">
        <div class="nav__logo">
            <span class="nav__logo-icon">&#9889;</span>
            <span class="nav__logo-text">Notify<span class="nav__logo-accent">Service</span></span>
        </div>
        <div class="nav__links">
            <a href="/docs" class="nav__link">Docs</a>
            <a href="/docs" class="btn btn--sm">API Panel</a>
        </div>
    </nav>

    <section class="hero">
        <div class="hero__badge">
            <span class="hero__badge-dot"></span>
            API v1.0 &nbsp;&middot;&nbsp; Laravel {{ app()->version() }} &nbsp;&middot;&nbsp; PHP {{ PHP_VERSION }}
        </div>

        <h1 class="hero__title">
            Message Queue<br>
            <span class="hero__title-gradient">at full speed</span>
        </h1>

        <p class="hero__subtitle">
            High-performance REST API for accepting and processing messages.<br>
            Auto queue, protected docs, real-time monitoring.
        </p>

        <div class="hero__actions">
            <a href="/docs" class="btn btn--primary btn--lg">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                Open Swagger UI
            </a>
        </div>

        <div class="hero__stats">
            <div class="stat">
                <div class="stat__value">99.9%</div>
                <div class="stat__label">Uptime</div>
            </div>
            <div class="stat__divider"></div>
            <div class="stat">
                <div class="stat__value">&lt;50ms</div>
                <div class="stat__label">Latency</div>
            </div>
            <div class="stat__divider"></div>
            <div class="stat">
                <div class="stat__value">20/2min</div>
                <div class="stat__label">Queue rate</div>
            </div>
            <div class="stat__divider"></div>
            <div class="stat">
                <div class="stat__value stat__value--green">&#9679; Online</div>
                <div class="stat__label">Status</div>
            </div>
        </div>
    </section>

    <section class="features">
        <div class="features__grid">
            <div class="feature-card">
                <div class="feature-card__icon feature-card__icon--orange">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"/></svg>
                </div>
                <h3 class="feature-card__title">Instant intake</h3>
                <p class="feature-card__desc">POST /api/message accepts channel and body, instantly writes to queue with timestamp.</p>
            </div>
            <div class="feature-card">
                <div class="feature-card__icon feature-card__icon--blue">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                </div>
                <h3 class="feature-card__title">Auto processing</h3>
                <p class="feature-card__desc">Cron every 2 minutes picks up to 20 unprocessed records and marks them complete.</p>
            </div>
            <div class="feature-card">
                <div class="feature-card__icon feature-card__icon--green">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                </div>
                <h3 class="feature-card__title">Secured docs</h3>
                <p class="feature-card__desc">Swagger UI accessible only after login. Account lockout after 5 failed attempts.</p>
            </div>
            <div class="feature-card">
                <div class="feature-card__icon feature-card__icon--purple">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><polyline points="16 18 22 12 16 6"/><polyline points="8 6 2 12 8 18"/></svg>
                </div>
                <h3 class="feature-card__title">OpenAPI 3.0</h3>
                <p class="feature-card__desc">Full spec via PHP attributes (swagger-php v6). Documentation always in sync with code.</p>
            </div>
        </div>
    </section>

    <footer class="footer">
        <div class="footer__content">
            <span class="footer__brand">&#9889; Notify Service</span>
            <span class="footer__sep">&middot;</span>
            <span class="footer__tech">Laravel {{ app()->version() }}</span>
            <span class="footer__sep">&middot;</span>
            <span class="footer__tech">PHP {{ PHP_VERSION }}</span>
            <span class="footer__sep">&middot;</span>
            <span class="footer__tech">MySQL 5.6</span>
            <span class="footer__sep">&middot;</span>
            <a href="/docs" class="footer__link">Swagger UI &rarr;</a>
        </div>
    </footer>

<script>
/* ---- Particle network ---- */
(function () {
    const canvas = document.getElementById('particles');
    const ctx    = canvas.getContext('2d');
    let W, H, dots = [];

    function resize() {
        W = canvas.width  = window.innerWidth;
        H = canvas.height = window.innerHeight;
    }

    function init() {
        dots = [];
        for (let i = 0; i < 70; i++) {
            dots.push({
                x:  Math.random() * W,
                y:  Math.random() * H,
                r:  Math.random() * 1.4 + 0.4,
                dx: (Math.random() - 0.5) * 0.35,
                dy: (Math.random() - 0.5) * 0.35,
                op: Math.random() * 0.45 + 0.1
            });
        }
    }

    function draw() {
        ctx.clearRect(0, 0, W, H);
        for (let i = 0; i < dots.length; i++) {
            const d = dots[i];
            ctx.beginPath();
            ctx.arc(d.x, d.y, d.r, 0, Math.PI * 2);
            ctx.fillStyle = `rgba(249,115,22,${d.op})`;
            ctx.fill();
            d.x += d.dx; d.y += d.dy;
            if (d.x < 0 || d.x > W) d.dx *= -1;
            if (d.y < 0 || d.y > H) d.dy *= -1;

            for (let j = i + 1; j < dots.length; j++) {
                const e    = dots[j];
                const dist = Math.hypot(d.x - e.x, d.y - e.y);
                if (dist < 130) {
                    ctx.beginPath();
                    ctx.moveTo(d.x, d.y);
                    ctx.lineTo(e.x, e.y);
                    ctx.strokeStyle = `rgba(249,115,22,${0.07 * (1 - dist / 130)})`;
                    ctx.lineWidth   = 0.5;
                    ctx.stroke();
                }
            }
        }
        requestAnimationFrame(draw);
    }

    window.addEventListener('resize', () => { resize(); init(); });
    resize(); init(); draw();
})();
</script>
</body>
</html>
