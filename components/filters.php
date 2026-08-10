<?php
/**
 * COMPONENT: filter sidebar (price + warranty)
 * Expects: $items (products used for counts), $bandsSel[], $warrSel[], $hiddenCat (string|null)
 */
$items     = $items     ?? [];
$bandsSel  = $bandsSel  ?? [];
$warrSel   = $warrSel   ?? [];
$hiddenCat = $hiddenCat ?? null;

$warrCounts = [];
foreach ([1, 2, 5] as $yr) {
  $warrCounts[$yr] = count(array_filter($items, fn($p) => warranty_years($p) >= $yr));
}
?>
<aside class="filters" id="filters" aria-label="Product filters">
  <div class="filters__head">
    <h2>Filters</h2>
    <button class="filters__clear" id="clearFilters" type="button">Clear all</button>
    <button class="filters__close" id="closeFilters" type="button" aria-label="Close filters">
      <svg width="18" height="18" viewBox="0 0 18 18" fill="none" aria-hidden="true"><path d="M4 4l10 10M14 4L4 14" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"/></svg>
    </button>
  </div>

  <form id="filterForm" method="get">
    <?php if ($hiddenCat): ?><input type="hidden" name="cat" value="<?= attr($hiddenCat) ?>"><?php endif; ?>

    <div class="fgroup">
      <p class="fgroup__title">Price</p>
      <?php foreach (price_bands() as $band):
        $count = count(array_filter($items, fn($p) => price_band((int)($p['mrp'] ?? 0)) === $band));
        if ($count === 0) continue; ?>
      <label class="fopt">
        <input type="checkbox" name="band[]" value="<?= attr($band) ?>" data-filter="band" <?= in_array($band, $bandsSel, true) ? 'checked' : '' ?>>
        <span class="fopt__box"><svg viewBox="0 0 12 12" fill="none" aria-hidden="true"><path d="M2 6.5l2.5 2.5L10 3" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg></span>
        <span><?= e(band_label($band)) ?></span>
        <span class="fopt__count"><?= $count ?></span>
      </label>
      <?php endforeach; ?>
    </div>

    <div class="fgroup">
      <p class="fgroup__title">Warranty</p>
      <?php foreach ([1, 2, 5] as $yr): if ($warrCounts[$yr] === 0) continue; ?>
      <label class="fopt">
        <input type="checkbox" name="warranty[]" value="<?= $yr ?>" data-filter="warranty" <?= in_array((string)$yr, array_map('strval', $warrSel), true) ? 'checked' : '' ?>>
        <span class="fopt__box"><svg viewBox="0 0 12 12" fill="none" aria-hidden="true"><path d="M2 6.5l2.5 2.5L10 3" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg></span>
        <span><?= $yr ?> year<?= $yr > 1 ? 's' : '' ?> &amp; above</span>
        <span class="fopt__count"><?= $warrCounts[$yr] ?></span>
      </label>
      <?php endforeach; ?>
    </div>

    <noscript><button type="submit" class="btn btn--sm" style="width:100%;justify-content:center;margin-top:1rem">Apply filters</button></noscript>
  </form>
</aside>
