/**
 * MAURICE APPLIANCES — Custom Interactive Magnetic Cursor
 * Injects DOM elements dynamically if not present, tracks pointer smoothly,
 * scales on hover over interactive targets, and gracefully disables on touch devices.
 */

export function initCursor() {
  const fine = window.matchMedia('(hover: hover) and (pointer: fine)').matches;
  if (!fine) {
    document.body.classList.remove('has-cursor');
    return;
  }

  let cursor = document.getElementById('cursor');
  if (!cursor) {
    cursor = document.createElement('div');
    cursor.id = 'cursor';
    cursor.className = 'cursor';
    cursor.setAttribute('aria-hidden', 'true');
    cursor.innerHTML = `
      <div class="cursor__ring"></div>
      <div class="cursor__dot"></div>
      <div class="cursor__label"></div>
    `;
    document.body.appendChild(cursor);
  }

  const ring = cursor.querySelector('.cursor__ring');
  const dot = cursor.querySelector('.cursor__dot');
  const label = cursor.querySelector('.cursor__label');

  let mx = window.innerWidth / 2, my = window.innerHeight / 2;
  let rx = mx, ry = my;
  const speed = 0.2;
  let isMoving = false;

  window.addEventListener('mousemove', (e) => {
    mx = e.clientX;
    my = e.clientY;
    if (!isMoving) {
      isMoving = true;
      cursor.style.opacity = '1';
    }
    if (dot) dot.style.transform = `translate(${mx}px, ${my}px) translate(-50%,-50%)`;
    if (label) label.style.transform = `translate(${mx}px, ${my + 2}px) translate(-50%,-50%)`;
  }, { passive: true });

  function render() {
    rx += (mx - rx) * speed;
    ry += (my - ry) * speed;
    if (ring) ring.style.transform = `translate(${rx}px, ${ry}px) translate(-50%,-50%)`;
    requestAnimationFrame(render);
  }
  requestAnimationFrame(render);

  // Hover states on interactive elements
  const hoverSel = 'a, button, [data-cursor], input, textarea, select, .pcard, .service-card, .vcard, .icard, .dealer-card';
  document.addEventListener('mouseover', (e) => {
    const t = e.target.closest(hoverSel);
    if (!t) return;
    const text = t.getAttribute('data-cursor');
    if (text && label) {
      label.textContent = text;
      cursor.classList.add('is-hover');
    } else {
      cursor.classList.add('is-hover-subtle');
    }
  });

  document.addEventListener('mouseout', (e) => {
    const t = e.target.closest(hoverSel);
    if (t) {
      cursor.classList.remove('is-hover');
      cursor.classList.remove('is-hover-subtle');
      if (label) label.textContent = '';
    }
  });

  document.addEventListener('mousedown', () => cursor.classList.add('is-down'));
  document.addEventListener('mouseup', () => cursor.classList.remove('is-down'));
}
