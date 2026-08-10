<?php
/**
 * CRON: regenerate the static sitemap.xml.
 * Suggested schedule: daily at 04:00
 */
require_once dirname(__DIR__, 1) . '/scripts/utilities/generate-sitemap.php';
