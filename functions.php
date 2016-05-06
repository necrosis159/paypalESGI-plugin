<?php 
/*
Plugin Name: paypalESGI-plugin
Description: Plugin wordpress crée par les étudiants ESGI: Mathias-Rui Lopes, Alexandre Lau, Thomas Gauret, Benjamin Montbailly
Plugin URI: http://www.esgi.fr/ecole-informatique.html
Tags: PayPal payment, PayPal, button, payment, online payments, pay now, buy now, ecommerce, gateway, paypal button, paypal buy now button, paypal plugin
Author: Mathias-Rui Lopes
Author URI: http://mrlopes.fr
License: GPL2
Version: 0.1
*/

global $pagenow, $typenow;


// settings page menu link
add_action( "admin_menu", "wpecpp_plugin_menu" );

function wpecpp_plugin_menu() {
add_options_page( "Paypal ESGI", "Paypal ESGI", "manage_options", "paypal-esgi-settings", "pes_plugin_options" );
}
add_filter('plugin_action_links', 'wpecpp_myplugin_plugin_action_links', 10, 2);

function wpecpp_myplugin_plugin_action_links($links, $file) {
static $this_plugin;
if (!$this_plugin) {
$this_plugin = plugin_basename(__FILE__);
}
if ($file == $this_plugin) {
$settings_link = '<a href="' . get_bloginfo('wpurl') . '/wp-admin/admin.php?page=paypal-esgi-settings">Settings</a>';
array_unshift($links, $settings_link);
}
return $links;
}

function wpecpp_plugin_settings_link($links)
{
unset($links['edit']);

$forum_link   = '<a target="_blank" href="https://wordpress.org/support/plugin/wp-ecommerce-paypal">' . __('Support', 'PTP_LOC') . '</a>';
$premium_link = '<a target="_blank" href="https://wpplugin.org/downloads/easy-paypal-buy-now-button/">' . __('Purchase Premium', 'PTP_LOC') . '</a>';
array_push($links, $forum_link);
array_push($links, $premium_link);
return $links; 
}

$plugin = plugin_basename(__FILE__); 
add_filter("plugin_action_links_$plugin", 'wpecpp_plugin_settings_link' );



function pes_plugin_options() {
if ( !current_user_can( "manage_options" ) )  {
wp_die( __( "You do not have sufficient permissions to access this page." ) );
}
}



?>