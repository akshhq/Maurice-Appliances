<?php /** SECTION: scrolling category strip */ $cats = get_categories(); ?>
<div class="strip" aria-hidden="true">
  <div class="strip__track">
    <?php for ($i = 0; $i < 2; $i++): foreach ($cats as $c): ?>
    <span class="strip__item"><?= e($c['name']) ?></span>
    <?php endforeach; endfor; ?>
  </div>
</div>
