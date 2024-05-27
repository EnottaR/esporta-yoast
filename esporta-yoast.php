<?php
/*
Plugin Name: Esporta Yoast Meta
Description: Esporta i meta title e meta description di Yoast in un file CSV.
Version: 0.0.1
Author: MGVision
*/

define('ESPYOAST_PLUGIN_DIR', plugin_dir_path(__FILE__));

require_once ESPYOAST_PLUGIN_DIR . 'includes/functions.php';
require_once ESPYOAST_PLUGIN_DIR . 'includes/endpoints.php';
require_once ESPYOAST_PLUGIN_DIR . 'includes/activation.php';
require_once ESPYOAST_PLUGIN_DIR . 'includes/admin-page.php';

register_activation_hook(__FILE__, 'espy_flush_rewrite_rules_on_activation');

register_deactivation_hook(__FILE__, 'espy_flush_rewrite_rules_on_deactivation');
