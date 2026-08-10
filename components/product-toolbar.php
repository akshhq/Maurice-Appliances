<?php
/**
 * COMPONENT: search + sort toolbar
 * Expects: $q, $sort, $placeholder
 */
$q = $q ?? '';
$sort = $sort ?? 'featured';
$placeholder = $placeholder ?? 'Search products, models, features…';
?>
<div class="ptoolbar">
  <button class="pfilter-btn" id="openFilters" type="button">
    <svg width="16" height="16" viewBox="0 0 16 16" fill="none" aria-hidden="true"><path d="M2 4h12M4 8h8M6 12h4" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/></svg>
    Filters
  </button>
  <div class="psearch">
    <svg viewBox="0 0 20 20" fill="none" aria-hidden="true"><circle cx="9" cy="9" r="6" stroke="currentColor" stroke-width="1.6"/><path d="M14 14l3 3" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/></svg>
    <input type="search" id="productSearch" placeholder="<?= attr($placeholder) ?>" value="<?= attr($q) ?>" aria-label="Search products">
  </div>
  <div class="psort">
    <label for="sortSelect">Sort</label>
    <select id="sortSelect" aria-label="Sort products">
      <option value="featured"   <?= $sort === 'featured'   ? 'selected' : '' ?>>Featured</option>
      <option value="price-asc"  <?= $sort === 'price-asc'  ? 'selected' : '' ?>>Price: low to high</option>
      <option value="price-desc" <?= $sort === 'price-desc' ? 'selected' : '' ?>>Price: high to low</option>
      <option value="name"       <?= $sort === 'name'       ? 'selected' : '' ?>>Name: A–Z</option>
    </select>
  </div>
</div>
