/* Custom cursor — ring + dot, magnetic hover, contextual label.
   Falls back gracefully: disabled on touch / coarse pointers. */

export function initCursor() {
  const fine = window.matchMedia('(hover: hover) and (pointer: fine)').matches;
  const cursor = document.getElementById('cursor');
  if (!fine || !cursor) {
    document.body.classList.remove('has-cursor');
    return;
  }

  const ring = cursor.querySelector('.cursor__ring');
  const dot = cursor.querySelector('.cursor__dot');
  const label = cursor.querySelector('.cursor__label');

  let mx = window.innerWidth / 2, my = window.innerHeight / 2;
  let rx = mx, ry = my;          // ring (lerped)
  const speed = 0.18;

  window.addEventListener('mousemove', (e) => {
    mx = e.clientX; my = e.clientY;
    // dot follows instantly
    dot.style.transform = `translate(${mx}px, ${my}px) translate(-50%,-50%)`;
    label.style.transform = `translate(${mx}px, ${my + 2}px) translate(-50%,-50%)`;
  }, { passive: true });

  function render() {
    rx += (mx - rx) * speed;
    ry += (my - ry) * speed;
    ring.style.transform = `translate(${rx}px, ${ry}px) translate(-50%,-50%)`;
    requestAnimationFrame(render);
  }
  requestAnimationFrame(render);

  // Hover states on interactive elements
  const hoverSel = 'a, button, [data-cursor], input, textarea, select, .pcard';
  document.addEventListener('mouseover', (e) => {
    const t = e.target.closest(hoverSel);
    if (!t) return;
    const text = t.getAttribute('data-cursor');
    if (text) { label.textContent = text; cursor.classList.add('is-hover'); }
    else { cursor.classList.remove('is-hover'); label.textContent = ''; }
  });
  document.addEventListener('mouseout', (e) => {
    const t = e.target.closest(hoverSel);
    if (t && t.getAttribute('data-cursor')) { cursor.classList.remove('is-hover'); label.textContent = ''; }
  });

  document.addEventListener('mousedown', () => cursor.classList.add('is-down'));
  document.addEventListener('mouseup', () => cursor.classList.remove('is-down'));

  // Magnetic buttons
  const magnets = document.querySelectorAll('[data-magnetic], .btn, .nav__cta');
  magnets.forEach((el) => {
    el.addEventListener('mousemove', (e) => {
      const r = el.getBoundingClientRect();
      const relX = e.clientX - r.left - r.width / 2;
      const relY = e.clientY - r.top - r.height / 2;
      el.style.transform = `translate(${relX * 0.18}px, ${relY * 0.28}px)`;
    });
    el.addEventListener('mouseleave', () => { el.style.transform = ''; });
  });
}
