/* FAQ accordion — accessible disclosure pattern.
   One panel open at a time; height animated from measured content. */

document.addEventListener('maurice:ready', () => {
  const items = document.querySelectorAll('.faq__item');
  if (!items.length) return;

  const close = (item) => {
    const btn = item.querySelector('.faq__q');
    const panel = item.querySelector('.faq__a');
    item.classList.remove('open');
    btn.setAttribute('aria-expanded', 'false');
    panel.style.maxHeight = '';
  };

  const open = (item) => {
    const btn = item.querySelector('.faq__q');
    const panel = item.querySelector('.faq__a');
    item.classList.add('open');
    btn.setAttribute('aria-expanded', 'true');
    panel.style.maxHeight = panel.scrollHeight + 'px';
  };

  items.forEach((item) => {
    const btn = item.querySelector('.faq__q');
    btn.addEventListener('click', () => {
      const isOpen = item.classList.contains('open');
      items.forEach(close);
      if (!isOpen) open(item);
    });
  });

  // Keep an open panel correctly sized if the viewport changes
  let rt;
  window.addEventListener('resize', () => {
    clearTimeout(rt);
    rt = setTimeout(() => {
      document.querySelectorAll('.faq__item.open .faq__a').forEach((p) => {
        p.style.maxHeight = p.scrollHeight + 'px';
      });
    }, 150);
  });
});
