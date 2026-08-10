/* Homepage behaviour:
   1. Hero title line reveal (GSAP)
   2. Mouse + scroll parallax on hero stage
   3. Count-up stats
   4. Lightweight ember particle canvas (2D — feature-detected, cheap, capped)
   Everything degrades cleanly under prefers-reduced-motion. */

const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

document.addEventListener('maurice:ready', () => {
  heroReveal();
  heroParallax();
  countUp();
  emberField();
});

/* ---- 1. Hero title ---- */
function heroReveal() {
  if (reduceMotion || !window.gsap) return;
  const lines = document.querySelectorAll('#heroTitle .line > span');
  gsap.set(lines, { yPercent: 110 });
  gsap.to(lines, {
    yPercent: 0, duration: 1, ease: 'power4.out', stagger: 0.09, delay: 0.1,
  });
  const lead = document.querySelector('.hero__lead');
  const cta = document.querySelector('.hero__cta');
  const trust = document.querySelector('.hero__trust');
  gsap.from([lead, cta, trust], {
    opacity: 0, y: 24, duration: 0.9, ease: 'power3.out', stagger: 0.1, delay: 0.5,
  });
}

/* ---- 2. Parallax ---- */
function heroParallax() {
  if (reduceMotion) return;
  const stage = document.getElementById('heroStage');
  if (!stage) return;
  const items = stage.querySelectorAll('[data-parallax]');

  // Mouse
  let tx = 0, ty = 0, cx = 0, cy = 0;
  window.addEventListener('mousemove', (e) => {
    const nx = (e.clientX / window.innerWidth - 0.5);
    const ny = (e.clientY / window.innerHeight - 0.5);
    tx = nx; ty = ny;
  }, { passive: true });

  function loop() {
    cx += (tx - cx) * 0.06;
    cy += (ty - cy) * 0.06;
    items.forEach((el) => {
      const d = parseFloat(el.dataset.parallax) || 0.1;
      el.style.transform = `translate(${cx * d * 120}px, ${cy * d * 120}px)`;
    });
    requestAnimationFrame(loop);
  }
  requestAnimationFrame(loop);

  // Scroll drift on the whole stage
  if (window.gsap && window.ScrollTrigger) {
    gsap.to(stage, {
      yPercent: 12, ease: 'none',
      scrollTrigger: { trigger: '.hero', start: 'top top', end: 'bottom top', scrub: true },
    });
  }
}

/* ---- 3. Count up ---- */
function countUp() {
  const els = document.querySelectorAll('[data-count]');
  if (!els.length) return;
  if (reduceMotion || !('IntersectionObserver' in window)) {
    els.forEach((el) => { el.textContent = el.dataset.count + (el.dataset.suffix || ''); });
    return;
  }
  const io = new IntersectionObserver((entries) => {
    entries.forEach((entry) => {
      if (!entry.isIntersecting) return;
      const el = entry.target;
      const end = parseInt(el.dataset.count, 10) || 0;
      const suffix = el.dataset.suffix || '';
      const dur = 1400; const start = performance.now();
      function step(now) {
        const p = Math.min(1, (now - start) / dur);
        const eased = 1 - Math.pow(1 - p, 3);
        el.textContent = Math.round(eased * end) + suffix;
        if (p < 1) requestAnimationFrame(step);
      }
      requestAnimationFrame(step);
      io.unobserve(el);
    });
  }, { threshold: 0.6 });
  els.forEach((el) => io.observe(el));
}

/* ---- 4. Ember particle field (2D canvas) ---- */
function emberField() {
  const canvas = document.getElementById('emberCanvas');
  if (!canvas || reduceMotion) return;
  // Skip on low-power / small screens to protect performance
  if (window.innerWidth < 720 || navigator.hardwareConcurrency <= 4) return;

  const ctx = canvas.getContext('2d');
  let w, h, dpr, particles, raf;

  function size() {
    dpr = Math.min(window.devicePixelRatio || 1, 2);
    w = canvas.clientWidth; h = canvas.clientHeight;
    canvas.width = w * dpr; canvas.height = h * dpr;
    ctx.setTransform(dpr, 0, 0, dpr, 0, 0);
  }

  function seed() {
    const count = Math.min(46, Math.floor(w / 26));
    particles = Array.from({ length: count }, () => ({
      x: Math.random() * w,
      y: h + Math.random() * h,
      r: Math.random() * 2 + 0.6,
      vy: Math.random() * 0.5 + 0.18,
      vx: (Math.random() - 0.5) * 0.25,
      a: Math.random() * 0.4 + 0.12,
      hue: Math.random() > 0.5 ? '255,106,61' : '224,30,38',
    }));
  }

  function frame() {
    ctx.clearRect(0, 0, w, h);
    for (const p of particles) {
      p.y -= p.vy; p.x += p.vx;
      if (p.y < -10) { p.y = h + 10; p.x = Math.random() * w; }
      ctx.beginPath();
      ctx.arc(p.x, p.y, p.r, 0, Math.PI * 2);
      ctx.fillStyle = `rgba(${p.hue},${p.a})`;
      ctx.shadowBlur = 8; ctx.shadowColor = `rgba(${p.hue},${p.a})`;
      ctx.fill();
    }
    ctx.shadowBlur = 0;
    raf = requestAnimationFrame(frame);
  }

  size(); seed(); frame();
  let rt;
  window.addEventListener('resize', () => {
    clearTimeout(rt);
    rt = setTimeout(() => { cancelAnimationFrame(raf); size(); seed(); frame(); }, 200);
  });

  // Pause when hero not visible (battery/perf)
  if ('IntersectionObserver' in window) {
    new IntersectionObserver((e) => {
      if (e[0].isIntersecting) { if (!raf) frame(); }
      else { cancelAnimationFrame(raf); raf = null; }
    }, { threshold: 0.01 }).observe(canvas);
  }
}
