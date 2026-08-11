/* Preloader — resolves once the inline loader script has finished.
   The actual animation is now driven by a plain inline <script> in
   partials/loader.php so it works even if ES module imports fail.
   This module just returns a promise that resolves when the loader
   is done (or immediately if it already finished). */

export function runLoader() {
  const loader = document.getElementById('loader');

  // No loader on this page, or already skipped (repeat visit).
  if (!loader || loader.classList.contains('skip')) return Promise.resolve();

  // If the inline script already finished (loader has .done), resolve now.
  if (loader.classList.contains('done')) return Promise.resolve();

  // Otherwise wait for the inline script to dispatch 'loader:done'.
  return new Promise((resolve) => {
    // Hard cap: don't wait more than 4 s for the loader either way.
    const cap = setTimeout(resolve, 4000);
    loader.addEventListener('loader:done', () => {
      clearTimeout(cap);
      // Small delay so the fade-out CSS transition has time to start.
      setTimeout(resolve, 500);
    }, { once: true });
  });
}
