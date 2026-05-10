<?php
/**
 * Entry Point for PasarKita
 */

// Load Configuration
require_once '../config/db.php';

// Load Core Libraries (Manual Autoload)
require_once '../app/core/App.php';
require_once '../app/core/Controller.php';

// Load Helpers
require_once '../app/helpers/session_helper.php';

// Initialize App
$app = new App();
?>
