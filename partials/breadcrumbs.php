<?php
/**
 * PARTIAL: breadcrumbs
 * Expects $crumbs = [['name'=>'Home','url'=>url('index.php')], ..., ['name'=>'Current']]
 * The final item is rendered as plain text (no link).
 */
$crumbs = $crumbs ?? [];
if (!$crumbs) return;
$last = array_key_last($crumbs);
?>
<nav class="phero__crumbs" aria-label="Breadcrumb">
  <?php foreach ($crumbs as $i => $c): ?>
    <?php if ($i === $last || empty($c['url'])): ?>
      <b><?= e($c['name']) ?></b>
    <?php else: ?>
      <a href="<?= e($c['url']) ?>"><?= e($c['name']) ?></a><span>/</span>
    <?php endif; ?>
  <?php endforeach; ?>
</nav>
