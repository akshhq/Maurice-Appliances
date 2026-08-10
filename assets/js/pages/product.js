/* Product detail — thumbnail active-state + gentle 3D tilt on the stage. */

document.addEventListener('maurice:ready', () => {
  const thumbs = document.querySelectorAll('.pdp__thumb');
  thumbs.forEach((t) => {
    t.addEventListener('click', () => {
      thumbs.forEach((x) => { x.classList.remove('active'); x.style.opacity = '.5'; });
      t.classList.add('active'); t.style.opacity = '1';
    });
  });

  // Subtle tilt on the product stage (desktop, fine pointer, motion allowed)
  const reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  const fine = window.matchMedia('(hover: hover) and (pointer: fine)').matches;
  const stage = document.querySelector('.pdp__stage');
  if (stage && fine && !reduce) {
    const svg = stage.querySelector('.pframe');
    stage.addEventListener('mousemove', (e) => {
      const r = stage.getBoundingClientRect();
      const rx = ((e.clientY - r.top) / r.height - 0.5) * -8;
      const ry = ((e.clientX - r.left) / r.width - 0.5) * 8;
      if (svg) svg.style.transform = `perspective(800px) rotateX(${rx}deg) rotateY(${ry}deg)`;
    });
    stage.addEventListener('mouseleave', () => { if (svg) svg.style.transform = ''; });
  }
});
