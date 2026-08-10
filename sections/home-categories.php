<?php /** SECTION: category grid */ $cats = get_categories(); ?>
<section class="section cats">
  <div class="wrap">
    <div class="section-head">
      <span class="eyebrow">The range</span>
      <h2>One brand for the whole home.</h2>
      <p class="lead">From the first heat pillar in 2012 to a full home portfolio today — <?= total_products() ?> models across eleven categories, each ISI-tested for safety and built to last.</p>
      <div class="ember-rule"></div>
    </div>
    <div class="cats__grid">
      <?php foreach ($cats as $i => $c): $slug = $c['id']; $soon = is_coming_soon($slug); ?>
      <?php if ($soon): ?>
      <div class="ccard ccard--soon reveal reveal-d<?= ($i % 3) + 1 ?>" aria-disabled="true">
        <span class="ccard__icon" aria-hidden="true"><?= category_icon_svg($slug) ?></span>
        <div class="ccard__foot">
          <p class="ccard__name"><?= e($c['name']) ?></p>
          <div class="ccard__meta">
            <span class="badge badge--ember">Coming Soon</span>
          </div>
        </div>
      </div>
      <?php else: ?>
      <a class="ccard reveal reveal-d<?= ($i % 3) + 1 ?>" href="<?= e(url(category_url($slug))) ?>" data-cursor="Browse">
        <span class="ccard__icon" aria-hidden="true"><?= category_icon_svg($slug) ?></span>
        <div class="ccard__foot">
          <p class="ccard__name"><?= e($c['name']) ?></p>
          <div class="ccard__meta">
            <span class="ccard__count"><?= category_count($slug) ?> products</span>
            <svg class="ccard__arrow" width="18" height="18" viewBox="0 0 18 18" fill="none" aria-hidden="true"><path d="M3 9h11M9 4l5 5-5 5" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/></svg>
          </div>
        </div>
      </a>
      <?php endif; ?>
      <?php endforeach; ?>
    </div>
  </div>
</section>
