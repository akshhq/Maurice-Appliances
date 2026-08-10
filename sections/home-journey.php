<?php
/** SECTION: journey preview timeline */
$milestones = [
  ['2012', 'Registered the maurice brand; launched our first Heat Pillar and water heaters.'],
  ['2015', 'Added irons, mixer grinders and gas room heaters; opened Bawana &amp; Kullu units.'],
  ['2017', 'Obtained BIS (ISI) certification and upgraded our testing labs.'],
  ['2019', 'Supplied 9,600 Rado heat pillars to the Himachal Government.'],
  ['2022', 'Introduced Atta Chakki, sandwich toaster and ventilation fans.'],
];
?>
<section class="section journey-p">
  <div class="wrap journey-p__wrap">
    <div class="si">
      <span class="eyebrow">Our journey</span>
      <h2>Innovation, quality and trust — since 2012.</h2>
      <p>What began with a single heat pillar has grown into a nationwide home-appliance brand, earning BIS certification and the confidence of dealers, government buyers and families alike.</p>
      <a href="<?= e(url('pages/journey.php')) ?>" class="btn btn--ghost" style="margin-top:var(--s-6)" data-cursor="Timeline">
        <span>See the full timeline</span>
        <svg class="arrow" width="14" height="14" viewBox="0 0 14 14" fill="none" aria-hidden="true"><path d="M2 7h10M8 3l4 4-4 4" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
      </a>
    </div>
    <div class="journey-p__line">
      <?php foreach ($milestones as $i => $m): ?>
      <div class="jp-node reveal reveal-d<?= $i % 3 ?>">
        <span class="jp-node__year"><?= e($m[0]) ?></span>
        <p class="jp-node__text"><?= $m[1] ?></p>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
