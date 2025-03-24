<?php
/*
Plugin Name: JW Members
Description: A members plugin depending on WooCommerce, Subscriptions, and QuickPay.
Version: 1.0
Author: WebJax
*/

defined('ABSPATH') or die('No script kiddies please!');

// Include the necessary files for the plugin
include_once plugin_dir_path(__FILE__) . 'includes/class-jw-members-activator.php';
include_once plugin_dir_path(__FILE__) . 'includes/class-jw-members-deactivator.php';
include_once plugin_dir_path(__FILE__) . 'includes/class-jw-members.php';

// Activation hook: Create tables and roles
function activate_jw_members() {
    JW_Members_Activator::activate();
}
register_activation_hook(__FILE__, 'activate_jw_members');

// Deactivation hook: Delete tables and roles
function deactivate_jw_members() {
    JW_Members_Deactivator::deactivate();
}
register_deactivation_hook(__FILE__, 'deactivate_jw_members');

// Initialize the plugin
function run_jw_members() {
    $plugin = new JW_Members();
    $plugin->run();
}
run_jw_members();
