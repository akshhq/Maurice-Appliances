<?php
/**
 * LAYOUT: full page closing — </main>, footer, scripts, </body>.
 * Expects (optional): $pageJs[]  e.g. ['home.js'] -> assets/js/pages/home.js
 */
$pageJs = $pageJs ?? [];
$noCollage = ['page-home', 'page-products', 'page-category', 'page-product'];
?>
<?php if (!in_array($bodyClass ?? '', $noCollage, true)): ?>
<?php partial('product-collage'); ?>
<?php endif; ?>
</main>

<?php require_once PARTIALS_PATH . '/footer.php'; ?>

<!-- Vendor libraries (CDN, deferred) -->
<script defer src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
<script defer src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js"></script>
<script defer src="https://cdn.jsdelivr.net/npm/split-type@0.3.4/umd/index.min.js"></script>
<script defer src="https://cdn.jsdelivr.net/gh/studio-freight/lenis@1.0.42/dist/lenis.min.js"></script>

<!-- Application -->
<script type="module" src="<?= e(url('assets/js/app.js')) ?>?v=<?= e(ASSET_VERSION) ?>"></script>
<?php foreach ((array)$pageJs as $js): ?>
<script type="module" src="<?= e(url('assets/js/pages/' . $js)) ?>?v=<?= e(ASSET_VERSION) ?>"></script>
<?php endforeach; ?>

</body>
</html>
