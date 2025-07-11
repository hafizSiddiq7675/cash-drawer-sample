<?php
/**
 * Plugin Name: Cash Drawer Sample Plugin
 * Description: A sample plugin that demonstrates custom table and REST API.
 * Version: 1.0
 * Author: Your Name
 */

if (!defined('ABSPATH')) exit;

require_once plugin_dir_path(__FILE__) . 'includes/installer.php';
require_once plugin_dir_path(__FILE__) . 'includes/rest-routes.php';

register_activation_hook(__FILE__, 'cash_drawer_create_table');
add_action('rest_api_init', 'cash_drawer_register_routes');
