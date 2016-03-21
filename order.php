<?php
/*
Plugin Name: Order plugin
Description: Plugin to send an order request by E-mail.
Version: 0.1
Author: helenej
*/
class Order_Plugin
{
	public function __construct()
	{
		include_once plugin_dir_path( __FILE__ ) . '/add_item.php';
		include_once plugin_dir_path( __FILE__ ) . '/show_cart.php';
		new Order_Add_Item();
        new Order_Show_Cart();
        
        add_action('wp_enqueue_scripts', array($this, 'order_plugin_scripts'));
	}
	
	public function order_plugin_scripts() {
		wp_enqueue_style('order-plugin-css', plugins_url('style.css', __FILE__ ));
		
		wp_register_script('utilities', get_stylesheet_directory_uri() . '/js/utilities.js', array(), '1.0', true);
		
		// Register a script that has jquery and underscore as dependency
		wp_register_script('order', plugins_url('js/order.js', __FILE__ ), array('jquery', 'underscore'), '1.0', true);
		
		// Enqueue a script that has jquery and order as dependency
		wp_enqueue_script('addItem', plugins_url('js/add_item.js', __FILE__ ), array('jquery', 'order'), '1.0', true);
	}

}

new Order_Plugin();