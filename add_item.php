<?php
class Order_Add_Item
{
    public function __construct()
    {
        add_shortcode('order_add_item', array($this, 'add_item_html'));
    }
    
    public function add_item_html($atts, $content)
    {
    	$atts = shortcode_atts(array('price' => 0), $atts); // TODO use $atts
    	echo '<input class="w50p" type="number" name="quantity" min="0" placeholder="0" />';
    }

}