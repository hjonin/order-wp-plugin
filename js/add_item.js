jQuery(function($) {

	// Reset all input number and register event handler
	$(".addItemInput").removeAttr('value').change(addItemInputChangeHandler);

	function addItemInputChangeHandler() {
		var quantity = $(this).val();
		var itemName = $(this).data("name");
		var price = $(this).data("price");
		if (!isNaN(quantity) && quantity > 0) {
			order.putItem(itemName, quantity, price);
		} else {
			order.removeItem(itemName, quantity, price);
		}
	}

});
