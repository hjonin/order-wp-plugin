<?php
/*
Plugin Name: Order plugin
Description: Plugin to send an order request by E-mail.
Version: 0.1
Author: helenej
*/
include_once plugin_dir_path( __FILE__ ) . '/inc/email_functions.php';
include_once plugin_dir_path( __FILE__ ) . '/add_item.php';
include_once plugin_dir_path( __FILE__ ) . '/show_cart.php';

// E-mail sending
add_action('wp_ajax_nopriv_devis', '_ajax_devis');
add_action('wp_ajax_devis', '_ajax_devis');

// Load JS
add_action('wp_enqueue_scripts', 'order_plugin_scripts');
function order_plugin_scripts() {
	wp_enqueue_style('order-plugin-css', plugins_url('style.css', __FILE__ ));

	wp_register_script('utilities', plugins_url('js/utilities.js', __FILE__ ), array(), '1.0', true);

	// Register a script that has jquery and jQuery UI calendar, underscore and utilities as dependency
	wp_register_script('order', plugins_url('js/order.js', __FILE__ ), array('jquery', 'jquery-ui-datepicker', 'underscore', 'utilities'), '1.0', true);
	// load as well jQuery stylesheet for calendar
	wp_enqueue_style('jquery-ui-css',
			'https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.21/themes/ui-darkness/jquery-ui.css',
			false,
			'1.0',
			false);

	// Enqueue a script that has jquery and order as dependency
	wp_enqueue_script('addItem', plugins_url('js/add_item.js', __FILE__ ), array('jquery', 'order'), '1.0', true);

	// Send strings to the order script
	$devisMessages = array(
			'success' => 'Votre message a bien été envoyé, nous vous contacterons dans les plus brefs délais.',
			'error' => 'Nous sommes désolés, une erreur est survenue lors de l\'envoi de votre message.'
	);
	wp_localize_script('order', 'devisMessages', $devisMessages);
}

class Order_Plugin
{
	public function __construct()
	{
		new Order_Add_Item();
        new Order_Show_Cart();
	}

}

new Order_Plugin();