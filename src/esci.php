<?php
require_once __DIR__ . '/includes/resources.php';

if (is_logged_in()) {
    logout_user();
}

flash_set('success', 'Hai effettuato il logout con successo.');
header('Location: ' . app_route('accedi'));
exit;
