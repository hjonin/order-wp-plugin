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
		include_once plugin_dir_path( __FILE__ ).'/add_item.php';
		include_once plugin_dir_path( __FILE__ ).'/show_cart.php';
		new Order_Add_Item();
        new Order_Show_Cart();
        
        add_action('wp_enqueue_scripts', array($this, 'order_plugin_scripts'));
	}
	
	public function order_plugin_scripts() {
		wp_enqueue_style('order-plugin-css', plugins_url('style.css', __FILE__ ));
		
		wp_enqueue_script('order',  plugins_url('js/order.js', __FILE__ ), array('jquery', 'underscore'), '1.0', true);
	}

}

new Order_Plugin();