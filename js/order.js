var order = {}; // Namespace
jQuery(function($) {

	// Some initialization
	$("#devis_date").datepicker({ dateFormat : 'dd/mm/yy' }); // TODO with localisation (after i18n)?

	// Register event handlers
	// set click listener for the footer button, to display or hide the form
	$("#devis_footer_button").click(showHideBtnClickHandler);
	// set click listener for the form send button
	$("#devis_send_button").click(submitBtnClickHandler);

	// Hide the devis form when the user clicks out of the box
	$(".content").click(function() { // FIXME more generic than .content
		hideDevisForm($("#devis_footer_button"));
	});

	// Private variables
	var selectedItems = [];
	var total;
	var busy = null;

	var putItem = function(itemName, quantity, price) {
		var selectedItem = _.find(selectedItems, function(item) { return item.itemName == itemName });
		if (selectedItem) {
			selectedItem.quantity = quantity;
		} else {
			selectedItems.push({
				itemName : itemName,
				quantity : quantity,
				price : price
			});
		}
		selectedItemsChange();
	}

	var removeItem = function(itemName) {
		selectedItems = _.reject(selectedItems, function(item) { return item.itemName == itemName; });
		selectedItemsChange();
	}

	var getTotal = function() {
		var res = 0;
		// Compute result from selected item map
		_.each(selectedItems, function(item) {
			res += item.quantity * item.price;
		});
		return res;
	}

	// Private functions
	function selectedItemsChange() {
		total = getTotal();

		// set footer button text with nbItem and res
		var $devisFooterButton = $("#devis_footer_button");
		$devisFooterButton.find(".itemCount").text(selectedItems.length);
		$devisFooterButton.find(".itemTotalPrice").text(Utilities.formatPrice(total, "€"));

		if (total > 0) {
			$("#devis_footer_div").fadeIn();
		} else {
			$("#devis_footer_div").fadeOut();
		}
	}

	function showHideBtnClickHandler() {
		if ($("#devis_div").css('display') == 'none') {
			showDevisForm($(this));
		} else {
			hideDevisForm($(this));
		}
	}

	function showDevisForm($btn) {
		$("#devis_form input[type=\"submit\"]").css("visibility", 'visible');
		$("#devis_div").slideDown();
		$btn.html($btn.html().replace('^', 'v'));

		renderSummaryTable();
	}

	function renderSummaryTable() {
		var $devisSummaryTable = $("#devis_summary table");

		var $devisSummaryTableBody = $devisSummaryTable.find('tbody');

		// for each item add it to summary message
		$devisSummaryTableBody.empty(); // Clear all items because we will add them again
		_.each(selectedItems, function(item) {
			// increment html body message
			$devisSummaryTableBody.append(
				'<tr>'
				+ '<td>' + item.itemName + '</td>'
				+ '<td>' + item.quantity + '</td>'
				+ '<td>' + item.price + '</td>'
				+ '</tr>');
		});

		// end the summary
		$devisSummaryTable.find('tfoot td:nth-child(2)').text(Utilities.formatPrice(total, "€"));
	}

	function hideDevisForm($btn) {
		$("#devis_form input[type=\"submit\"]").css("visibility", 'hidden');
		$("#devis_div").slideUp();
		$btn.html($btn.html().replace('v', '^'));
	}

	function submitBtnClickHandler() {
		var error = false;

		// check every input, if required and empty an error is raised
		$("#devis_form").find("*").filter(':input').each(function() {
			if ($(this).prop('required')) {
				if (!$(this).val()) {

					// if the input is required and empty, display red border
					$(this).addClass('input-required');
					error = true;
				} else {
					// otherwise remove border
					$(this).removeClass('input-required');
				}
			}
		});

		// if there is no error, send the ajax request
		if (!error) {
			postAjaxMailRequest();
		}

		return false; // return false so the page is not reloaded
	}

	function postAjaxMailRequest() {
		if (busy) {
			// if there is already a running mail request and the user has
			// pressed the send button, abort the previous request
			busy.abort();
			// $('#devis_notification').slideUp();
		}

		// create new ajax request
		busy = $.ajax({
			url : ajaxUrl,
			type : 'POST',
			data : {
				devis_name : $("#devis_name").val(),
				devis_email : $("#devis_email").val(),
				devis_summary : $("#devis_summary").html(),
				devis_notes : $("#devis_notes").val(),
				devis_date : $("#devis_date").val(),
				devis_copy : $("#devis_copy").prop("checked"),
				action : $("#action").val()
			},
			success : function(response) {
				if (response == 'success') {
					$('#devis_notification').attr('class', 'devis-notification-success').html(devisMessages.success);
					document.getElementById("devis_form").reset();
					hideDevisForm($("#devis_footer_button"));

					// Reset all input numbers and row results TODO
					// var $input = $( ".caterer-services table tbody input[type='number']" );
					// $input.removeAttr('value');
					// $input.trigger("change");
				} else {
					$('#devis_notification').attr('class', 'devis-notification-error').html(devisMessages.error);
				}

				$('#devis_notification').slideDown();
				$('#devis_notification').delay(5000).slideUp();
			}
		});
	}

	// Public functions
	order.putItem = putItem;
	order.removeItem = removeItem;
	order.getTotal = getTotal;
});