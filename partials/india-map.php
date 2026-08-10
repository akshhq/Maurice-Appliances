<?php
/**
 * PARTIAL: India state-boundary illustration — glowing outline map.
 * Highlights Himachal Pradesh (#hp) and Delhi (#dl), where Maurice
 * manufactures, via CSS in assets/css/components/india-map.css.
 *
 * Map data: @svg-maps/india by Victor Cazanave, CC BY 4.0
 * https://github.com/VictorCazanave/svg-maps/tree/master/packages/india
 *
 * Usage: partial('india-map', ['size' => 'hero'|'compact', 'legend' => true]);
 */
$size   = $size   ?? 'compact';
$legend = $legend ?? true;

$mapFile = PUBLIC_PATH . '/assets/images/misc/india-map.svg';
$mapSvg  = is_readable($mapFile) ? file_get_contents($mapFile) : '';
?>
<div class="indiamap indiamap--<?= e($size) ?>">
  <div class="indiamap__glow" aria-hidden="true"></div>
  <div class="indiamap__art" role="img" aria-label="Map of India highlighting Maurice Appliances' manufacturing presence in Himachal Pradesh and Delhi">
    <?= $mapSvg ?>
  </div>
  <?php if ($legend): ?>
  <div class="indiamap__legend">
    <div class="indiamap__item">
      <span class="indiamap__dot indiamap__dot--ember" aria-hidden="true"></span>
      <div><b>Himachal Pradesh</b><span>Kullu manufacturing unit &amp; Himachal Government rate contracts</span></div>
    </div>
    <div class="indiamap__item">
      <span class="indiamap__dot indiamap__dot--red" aria-hidden="true"></span>
      <div><b>Delhi</b><span>Bawana manufacturing unit</span></div>
    </div>
    <div class="indiamap__item">
      <span class="indiamap__dot indiamap__dot--ring" aria-hidden="true"></span>
      <div><b>Pan-India</b><span>Retail dealer &amp; distributor network, expanding steadily</span></div>
    </div>
  </div>
  <?php endif; ?>
  <p class="indiamap__credit">Map data: @svg-maps/india (CC BY 4.0)</p>
</div>
