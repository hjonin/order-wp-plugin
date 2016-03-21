<?php
class Order_Add_Item
{
    public function __construct()
    {
        add_shortcode('order_add_item', array($this, 'add_item_html'));
    }
    
    public function add_item_html($atts, $content)
    {
    	$atts = shortcode_atts(array('price' => 0), $atts);
    	echo '<input class="addItemInput" type="number" name="quantity" min="0" placeholder="0"'
    			. ' data-name="' . $content
    			. '" data-price="' . $atts['price']
    			. '" style="width: 50px;" />';
    }

}