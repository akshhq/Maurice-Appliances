<?php
/**
 * COMPONENT: product card
 * Usage:  component('product-card', ['p' => $product, 'compare' => true]);
 * Expects: $p (enriched product), $compare (bool, show compare checkbox)
 */
$p = $p ?? null;
if (!$p) return;
$compare = $compare ?? false;

$wShort = warranty_short($p);
$band   = price_band((int)($p['mrp'] ?? 0));
$uid    = $p['cat'] . '::' . $p['slug'];
?>
<article class="pcard reveal"
         data-cat="<?= attr($p['cat']) ?>"
         data-price="<?= (int)($p['mrp'] ?? 0) ?>"
         data-band="<?= attr($band) ?>"
         data-warranty="<?= warranty_years($p) ?>"
         data-uid="<?= attr($uid) ?>"
         data-model="<?= attr($p['model'] ?? '') ?>"
         data-title="<?= attr($p['title'] ?? '') ?>"
         data-name="<?= attr(strtolower(($p['model'] ?? '') . ' ' . ($p['title'] ?? '') . ' ' . implode(' ', $p['specs'] ?? []))) ?>">
  <div class="pcard__media">
    <div class="pcard__badges">
      <span class="badge badge--red">ISI</span>
      <?php if ($wShort): ?><span class="badge"><?= e($wShort) ?></span><?php endif; ?>
    </div>
    <?php if ($compare): ?>
    <label class="pcard__compare" data-no-nav>
      <input type="checkbox" class="compareCheck" value="<?= attr($uid) ?>" aria-label="Add <?= attr($p['title'] ?? '') ?> to compare">
      Compare
    </label>
    <?php endif; ?>
    <?= product_visual($p, 'pcard__frame') ?>
  </div>

  <div class="pcard__body">
    <span class="pcard__model"><?= e($p['model'] ?? '') ?></span>
    <h3 class="pcard__title"><?= e($p['title'] ?? '') ?></h3>
    <div class="pcard__spec">
      <?php foreach (array_slice($p['specs'] ?? [], 0, 2) as $s): ?>
      <span>
        <svg width="14" height="14" viewBox="0 0 14 14" fill="none" aria-hidden="true"><path d="M2 7.5l3 3 7-7.5" stroke="var(--red)" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
        <?= e($s) ?>
      </span>
      <?php endforeach; ?>
    </div>
  </div>

  <div class="pcard__foot">
    <div class="pcard__price">
      <span class="l">MRP</span>
      <span class="v tabular"><?= e(inr((int)($p['mrp'] ?? 0))) ?></span>
    </div>
    <span class="pcard__link">
      View
      <svg width="14" height="14" viewBox="0 0 14 14" fill="none" aria-hidden="true"><path d="M2 7h10M8 3l4 4-4 4" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
    </span>
  </div>

  <a class="pcard__stretch" href="<?= e(url(product_url($p))) ?>" data-cursor="View"
     aria-label="<?= attr(trim(($p['model'] ?? '') . ' ' . ($p['title'] ?? ''))) ?>"></a>
</article>
