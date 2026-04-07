<?php
require_once __DIR__ . '/includes/resources.php';

$context = admin_panel_bootstrap_context($pdo);
extract($context, EXTR_SKIP);

$destination = 'admin_forniture.php';
if ($isGeneralAdmin && $selectedBranchSlug !== '') {
    $destination .= '?sede=' . rawurlencode($selectedBranchSlug);
}

header('Location: ' . $destination);
exit;
