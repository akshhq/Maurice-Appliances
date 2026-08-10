<?php
/** PARTIAL: primary navigation — glass sticky nav, mega-menu, mobile drawer */
$cats = get_categories();
$megaFeat = get_product('room-heaters', 'rado-1500-watts-heat-pillar');
?>
<header class="nav" id="nav">
  <div class="nav__bar wrap">
    <a class="nav__logo" href="<?= e(url('index.php')) ?>" data-cursor="Home" aria-label="Maurice — home">
      <span class="nav__logo-word">maurice</span>
      <span class="nav__logo-reg">&reg;</span>
      <span class="nav__logo-line" aria-hidden="true"></span>
    </a>

    <nav class="nav__links" aria-label="Primary">
      <div class="nav__item nav__item--drop">
        <a href="<?= e(url('pages/about.php')) ?>" class="nav__link"<?= is_active('about.php') ?>>
          Company
          <svg class="nav__caret" width="10" height="6" viewBox="0 0 10 6" aria-hidden="true"><path d="M1 1l4 4 4-4" stroke="currentColor" stroke-width="1.5" fill="none" stroke-linecap="round"/></svg>
        </a>
        <div class="drop" role="menu">
          <a href="<?= e(url('pages/about.php')) ?>" role="menuitem">About Maurice</a>
          <a href="<?= e(url('pages/journey.php')) ?>" role="menuitem">Our Journey</a>
          <a href="<?= e(url('pages/vision.php')) ?>" role="menuitem">Vision &amp; Mission</a>
          <a href="<?= e(url('pages/values.php')) ?>" role="menuitem">Core Values</a>
          <a href="<?= e(url('pages/manufacturing.php')) ?>" role="menuitem">Manufacturing &amp; Quality</a>
        </div>
      </div>

      <div class="nav__item nav__item--mega">
        <a href="<?= e(url('products.php')) ?>" class="nav__link"<?= is_active('products.php') ?>>
          Products
          <svg class="nav__caret" width="10" height="6" viewBox="0 0 10 6" aria-hidden="true"><path d="M1 1l4 4 4-4" stroke="currentColor" stroke-width="1.5" fill="none" stroke-linecap="round"/></svg>
        </a>
        <div class="mega" role="menu" aria-label="Product categories">
          <div class="mega__inner wrap">
            <div class="mega__grid">
              <?php foreach ($cats as $c): $slug = $c['id']; $soon = is_coming_soon($slug); ?>
              <?php if ($soon): ?>
              <div class="mega__cat mega__cat--soon" role="menuitem" aria-disabled="true">
                <span class="mega__icon" aria-hidden="true"><?= category_icon_svg($slug, 1.5) ?></span>
                <span class="mega__text">
                  <span class="mega__name"><?= e($c['name']) ?></span>
                  <span class="badge badge--ember mega__soon-badge">Coming Soon</span>
                </span>
              </div>
              <?php else: ?>
              <a class="mega__cat" href="<?= e(url(category_url($slug))) ?>" role="menuitem" data-cursor="View">
                <span class="mega__icon" aria-hidden="true"><?= category_icon_svg($slug, 1.5) ?></span>
                <span class="mega__text">
                  <span class="mega__name"><?= e($c['name']) ?></span>
                  <span class="mega__count"><?= category_count($slug) ?> products</span>
                </span>
              </a>
              <?php endif; ?>
              <?php endforeach; ?>
            </div>
            <div class="mega__aside">
              <?php if ($megaFeat): ?>
              <a class="mega__feat" href="<?= e(url(product_url($megaFeat))) ?>" data-cursor="View">
                <span class="mega__feat-frame"><?= product_visual($megaFeat, 'mega__feat-image') ?></span>
                <span class="mega__feat-body">
                  <span class="mega__feat-label">Featured</span>
                  <span class="mega__feat-name"><?= e($megaFeat['model'] . ' ' . $megaFeat['title']) ?></span>
                  <span class="mega__feat-price"><?= e(inr((int)($megaFeat['mrp'] ?? 0))) ?></span>
                </span>
              </a>
              <?php endif; ?>
              <p class="mega__aside-eyebrow">Explore</p>
              <a href="<?= e(url('products.php')) ?>" class="mega__aside-link">All products &rarr;</a>
              <a href="<?= e(url('products.php#compare')) ?>" class="mega__aside-link">Compare models &rarr;</a>
              <a href="<?= e(url('pages/downloads.php')) ?>" class="mega__aside-link">Catalogue &amp; downloads &rarr;</a>
              <div class="mega__aside-note">
                <span class="badge badge--red">ISI</span>
                <span class="badge badge--ember">ISO 9001:2015</span>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="nav__item nav__item--drop">
        <a href="<?= e(url('pages/dealers.php')) ?>" class="nav__link"<?= is_active('dealers.php') ?>>
          Dealers
          <svg class="nav__caret" width="10" height="6" viewBox="0 0 10 6" aria-hidden="true"><path d="M1 1l4 4 4-4" stroke="currentColor" stroke-width="1.5" fill="none" stroke-linecap="round"/></svg>
        </a>
        <div class="drop" role="menu">
          <a href="<?= e(url('pages/dealers.php')) ?>" role="menuitem">Dealer Network</a>
          <a href="<?= e(url('pages/become-dealer.php')) ?>" role="menuitem">Become a Dealer</a>
        </div>
      </div>

      <div class="nav__item nav__item--drop">
        <a href="<?= e(url('pages/service.php')) ?>" class="nav__link"<?= is_active('service.php') ?>>
          Support
          <svg class="nav__caret" width="10" height="6" viewBox="0 0 10 6" aria-hidden="true"><path d="M1 1l4 4 4-4" stroke="currentColor" stroke-width="1.5" fill="none" stroke-linecap="round"/></svg>
        </a>
        <div class="drop" role="menu">
          <a href="<?= e(url('pages/service.php')) ?>" role="menuitem">After-Sales Service</a>
          <a href="<?= e(url('pages/warranty.php')) ?>" role="menuitem">Warranty</a>
          <a href="<?= e(url('pages/faq.php')) ?>" role="menuitem">FAQ</a>
          <a href="<?= e(url('pages/downloads.php')) ?>" role="menuitem">Downloads</a>
        </div>
      </div>

      <a href="<?= e(url('pages/contact.php')) ?>" class="nav__link"<?= is_active('contact.php') ?>>Contact</a>
    </nav>

    <div class="nav__actions">
      <a href="<?= e(url('pages/become-dealer.php')) ?>" class="btn btn--sm nav__cta" data-cursor="Apply">Become a Dealer</a>
      <button class="nav__burger" id="burger" aria-label="Open menu" aria-expanded="false" aria-controls="mobileMenu">
        <span></span><span></span><span></span>
      </button>
    </div>
  </div>
</header>

<div class="mobile" id="mobileMenu" aria-hidden="true">
  <div class="mobile__inner">
    <p class="mobile__label">Company</p>
    <a class="mobile__link" href="<?= e(url('pages/about.php')) ?>">About Maurice</a>
    <a class="mobile__link" href="<?= e(url('pages/journey.php')) ?>">Our Journey</a>
    <a class="mobile__link" href="<?= e(url('pages/vision.php')) ?>">Vision &amp; Mission</a>
    <a class="mobile__link" href="<?= e(url('pages/values.php')) ?>">Core Values</a>
    <a class="mobile__link" href="<?= e(url('pages/manufacturing.php')) ?>">Manufacturing &amp; Quality</a>
    <p class="mobile__label">Products</p>
    <div class="mobile__cats">
      <?php foreach ($cats as $c): $soon = is_coming_soon($c['id']); ?>
      <?php if ($soon): ?>
      <span class="mobile__cats-soon" aria-disabled="true"><?= e($c['name']) ?> <span class="badge badge--ember">Soon</span></span>
      <?php else: ?>
      <a href="<?= e(url(category_url($c['id']))) ?>"><?= e($c['name']) ?> <span><?= category_count($c['id']) ?></span></a>
      <?php endif; ?>
      <?php endforeach; ?>
    </div>
    <p class="mobile__label">Dealers &amp; Support</p>
    <a class="mobile__link" href="<?= e(url('pages/dealers.php')) ?>">Dealer Network</a>
    <a class="mobile__link" href="<?= e(url('pages/become-dealer.php')) ?>">Become a Dealer</a>
    <a class="mobile__link" href="<?= e(url('pages/service.php')) ?>">After-Sales Service</a>
    <a class="mobile__link" href="<?= e(url('pages/warranty.php')) ?>">Warranty</a>
    <a class="mobile__link" href="<?= e(url('pages/faq.php')) ?>">FAQ</a>
    <a class="mobile__link" href="<?= e(url('pages/downloads.php')) ?>">Downloads</a>
    <a class="mobile__link" href="<?= e(url('pages/contact.php')) ?>">Contact</a>
    <a class="btn mobile__cta" href="<?= e(url('pages/become-dealer.php')) ?>">Become a Dealer</a>
  </div>
</div>
