var Utilities = (function() {
	var my = {};
	
	// Public functions
	my.parsePrice = function(priceStr, currencyStr) {
		return parseFloat(priceStr.replace(/ /g, "").replace(",", ".").replace(currencyStr, "").trim());
	};
	
	my.formatPrice = function(priceFloat, currencyStr) {
		return priceFloat.toFixed(2).replace(".", ",") + currencyStr;
	};
	
	return my;
})();