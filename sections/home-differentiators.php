<?php
/** SECTION: what makes us different */
$diffs = [
  ['Manufacturing excellence', 'Precision engineering, modern processes and rigorous quality control — from material selection to final testing — for lasting performance.'],
  ['Quality assurance',        'Every appliance undergoes comprehensive testing for safety, durability, energy efficiency and consistent performance before it reaches you.'],
  ['Innovation driven',        'We combine evolving technology with real customer insight to build smarter, more efficient appliances that simplify everyday living.'],
  ['Customer commitment',      'Our relationship extends beyond the sale — dependable after-sales support and an expanding service network across key markets.'],
];
?>
<section class="section diff">
  <div class="diff__glow" aria-hidden="true"></div>
  <div class="diff__product" aria-hidden="true"><?= category_product_visual('chimneys', 'diff__product-image', true) ?></div>
  <div class="wrap">
    <div class="si">
      <span class="eyebrow">What makes us different</span>
      <h2>Advanced technology. Honest value. Built in India.</h2>
      <p>Unlike conventional manufacturers, we start with how families actually live — then engineer practical, dependable products around it.</p>
    </div>
    <div class="diff__grid">
      <?php foreach ($diffs as $i => $d): ?>
      <div class="dcard reveal reveal-d<?= $i % 2 + 1 ?>">
        <span class="dcard__num"><?= str_pad((string)($i + 1), 2, '0', STR_PAD_LEFT) ?></span>
        <h3 class="dcard__title"><?= e($d[0]) ?></h3>
        <p><?= e($d[1]) ?></p>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
