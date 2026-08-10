<?php
/**
 * COMPONENT: compare tray + modal + client data payload
 * Expects: $results (products currently listed), $catNameFallback (string|null)
 */
$results = $results ?? [];
$catNameFallback = $catNameFallback ?? null;
?>
<div class="ctray" id="compareTray" aria-live="polite">
  <span class="ctray__label"><span id="compareCount">0</span> selected to compare</span>
  <div class="ctray__items" id="compareItems"></div>
  <div class="ctray__actions">
    <button class="ctray__clear" id="compareClear" type="button">Clear</button>
    <button class="btn btn--sm" id="compareOpen" type="button" data-cursor="Compare">Compare</button>
  </div>
</div>

<div class="cmodal" id="compareModal" role="dialog" aria-modal="true" aria-label="Compare products">
  <div class="cmodal__scrim" data-close></div>
  <div class="cmodal__panel">
    <div class="cmodal__head">
      <h2>Compare products</h2>
      <button class="cmodal__close" data-close aria-label="Close">
        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" aria-hidden="true"><path d="M5 5l10 10M15 5L5 15" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"/></svg>
      </button>
    </div>
    <div id="compareTableWrap"></div>
  </div>
</div>

<?php
$compareMap = [];
foreach ($results as $p) {
  $compareMap[$p['cat'] . '::' . $p['slug']] = [
    'model'    => $p['model'] ?? '',
    'title'    => $p['title'] ?? '',
    'mrp'      => (int)($p['mrp'] ?? 0),
    'mrpFmt'   => inr((int)($p['mrp'] ?? 0)),
    'warranty' => $p['warranty'] ?? '',
    'dim'      => $p['dim'] ?? '',
    'weight'   => $p['weight'] ?? '',
    'moq'      => $p['moq'] ?? '',
    'cat'      => $catNameFallback ?? (get_category($p['cat'])['name'] ?? $p['cat']),
    'specs'    => array_values($p['specs'] ?? []),
    'url'      => url(product_url($p)),
  ];
}
?>
<script id="compareData" type="application/json"><?= json_encode($compareMap, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP) ?></script>
