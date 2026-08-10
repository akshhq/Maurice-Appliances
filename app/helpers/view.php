<?php
/**
 * MAURICE — View helpers
 * Locating and rendering layouts, sections, partials and components.
 */

declare(strict_types=1);

/**
 * Render a view file with scoped variables.
 * $type: 'component' | 'section' | 'partial' | 'layout' | 'template'
 */
function view(string $type, string $name, array $data = []): void {
  $map = [
    'component' => COMPONENTS_PATH,
    'section'   => SECTIONS_PATH,
    'partial'   => PARTIALS_PATH,
    'layout'    => LAYOUTS_PATH,
    'template'  => TEMPLATES_PATH,
  ];
  $base = $map[$type] ?? null;
  if (!$base) return;

  $file = $base . '/' . ltrim($name, '/') . '.php';
  if (!is_readable($file)) {
    if (APP_DEBUG) {
      echo '<!-- view not found: ' . e($type . '/' . $name) . ' -->';
    }
    return;
  }
  extract($data, EXTR_SKIP);
  include $file;
}

function component(string $name, array $data = []): void { view('component', $name, $data); }
function section(string $name, array $data = []): void   { view('section',   $name, $data); }
function partial(string $name, array $data = []): void   { view('partial',   $name, $data); }

/** Capture a view's output as a string. */
function view_capture(string $type, string $name, array $data = []): string {
  ob_start();
  view($type, $name, $data);
  return (string) ob_get_clean();
}

/** Company profile array (from config/app.php). */
function get_company(): array { return $GLOBALS['COMPANY'] ?? []; }
