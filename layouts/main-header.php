<?php
/**
 * LAYOUT: full page opening — head, loader, cursor, navbar, <main>.
 * Include at the top of every page after the bootstrap.
 */
require_once LAYOUTS_PATH . '/head.php';
if (($bodyClass ?? '') === 'page-home') {
  require_once PARTIALS_PATH . '/loader.php';
}
require_once PARTIALS_PATH . '/cursor.php';
require_once PARTIALS_PATH . '/navbar.php';
?>
<main id="main">
