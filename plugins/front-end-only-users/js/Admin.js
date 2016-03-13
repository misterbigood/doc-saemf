/* Used to show and hide the admin tabs for FEUP */
function ShowTab(TabName) {
		jQuery(".OptionTab").each(function() {
				jQuery(this).addClass("HiddenTab");
				jQuery(this).removeClass("ActiveTab");
		});
		jQuery("#"+TabName).removeClass("HiddenTab");
		jQuery("#"+TabName).addClass("ActiveTab");
		
		jQuery(".nav-tab").each(function() {
				jQuery(this).removeClass("nav-tab-active");
		});
		jQuery("#"+TabName+"_Menu").addClass("nav-tab-active");
}

function ShowMoreOptions() {
	jQuery(".feup-email-advanced-settings").toggle();
	jQuery(".feup-email-toggle-show").toggle();
	jQuery(".feup-email-toggle-hide").toggle();

	return false;
}

function ShowOptionTab(TabName) {
	jQuery(".feup-option-set").each(function() {
		jQuery(this).addClass("feup-hidden");
	});
	jQuery("#"+TabName).removeClass("feup-hidden");
	
	jQuery(".options-subnav-tab").each(function() {
		jQuery(this).removeClass("options-subnav-tab-active");
	});
	jQuery("#"+TabName+"_Menu").addClass("options-subnav-tab-active");
}

jQuery(document).ready(function() {	
	jQuery('.ewd-feup-one-click-install-div-load').on('click', function() {
		jQuery('#ewd-feup-one-click-install-div').removeClass('ewd-feup-oci-no-show');
		jQuery('#ewd-feup-one-click-install-div').addClass('ewd-feup-oci-main-event');
		jQuery('#ewd-feup-one-click-blur').addClass('ewd-feup-grey-out');
		jQuery('#ewd-feup-one-click-blur').width(jQuery('#ewd-feup-one-click-blur').width() + 180);
	});

	jQuery('#ewd-feup-one-click-blur').on('click', function() {
		jQuery('#ewd-feup-one-click-install-div').addClass('ewd-feup-oci-no-show');
		jQuery('#ewd-feup-one-click-install-div').removeClass('ewd-feup-oci-main-event');
		jQuery('#ewd-feup-one-click-blur').removeClass('ewd-feup-grey-out');
	});

});

/* This code is required to make changing the field order a drag-and-drop affair */
jQuery(document).ready(function() {	
	jQuery('.fields-list').sortable({
		items: '.list-item',
		opacity: 0.6,
		cursor: 'move',
		axis: 'y',
		update: function() {
			var order = jQuery(this).sortable('serialize') + '&action=ewd_feup_update_field_order';
			jQuery.post(ajaxurl, order, function(response) {});
		}
	});

	/*jQuery('.levels-list').sortable({
		items: '.list-item',
		opacity: 0.6,
		cursor: 'move',
		axis: 'y',
		update: function() {
			var order = jQuery(this).sortable('serialize') + '&action=ewd_feup_update_levels_order';
			alert(order);
			jQuery.post(ajaxurl, order, function(response) {alert(response);});
		}
	});*/
});

jQuery(document).ready(function() {
	SetDiscountDeleteHandlers();

	jQuery('.ewd-feup-add-discount-code').on('click', function(event) {
		var ID = jQuery(this).data('nextid');

		var HTML = "<tr id='ewd-feup-discount-code-row-" + ID + "'>";
		HTML += "<td><a class='ewd-feup-delete-discount-code' data-reminderID='" + ID + "'>Delete</a></td>";
		HTML += "<td><input type='text' name='Discount_Code_" + ID + "_Code'></td>";
		HTML += "<td><input type='text' name='Discount_Code_" + ID + "_Amount'></td>";
		HTML += "<td><select name='Discount_Code_" + ID + "_Recurring'>";
		HTML += "<option value='No'>No</option>";
		HTML += "<option value='Yes'>Yes</option>";
		HTML += "</select></td>";
		HTML += "<td><select name='Discount_Code_" + ID + "_Applicable'>";
		HTML += "<option value='Membership'>Membership</option>";
		//HTML += "<option value='Level'>Hour(s)</option>";
		//HTML += "<option value='Days'>Day(s)</option>";
		HTML += "</select></td>";
		HTML += "<td><input type='datetime-local' name='Discount_Code_" + ID + "_Expiry'></td>";
		HTML += "</tr>";

		//jQuery('table > tr#ewd-uasp-add-reminder').before(HTML);
		jQuery('#ewd-feup-discount-codes-table tr:last').before(HTML);

		ID++;
		jQuery(this).data('nextid', ID); //updates but doesn't show in DOM

		SetDiscountDeleteHandlers();

		event.preventDefault();
	});
});

function SetDiscountDeleteHandlers() {
	jQuery('.ewd-feup-delete-discount-code').on('click', function(event) {
		var ID = jQuery(this).data('reminderid');
		var tr = jQuery('#ewd-feup-discount-code-row-'+ID);

		tr.fadeOut(400, function(){
            tr.remove();
        });

		event.preventDefault();
	});
}

jQuery(document).ready(function() {
	SetLevelDeleteHandlers();

	jQuery('.ewd-feup-add-level-payment').on('click', function(event) {
		var ID = jQuery(this).data('nextid');

		var HTML = "<tr id='ewd-feup-level-payment-row-" + ID + "'>";
		HTML += "<td><a class='ewd-feup-delete-level-payment' data-levelpaymentid='" + ID + "'>Delete</a></td>";
		HTML += "<td><select class='ewd-feup-insert-levels' name='Level_Payment_" + ID + "_Level'></select></td>";
		HTML += "<td><input type='text' name='Level_Payment_" + ID + "_Amount'><input type='hidden' name='Level_Payment_" + ID + "_Cumulative' value='No'></td>";
		//HTML += "<td><select name='Level_Payment_" + ID + "_Cumulative'>";
		//HTML += "<option value='No'>No</option>";
		//HTML += "<option value='Yes'>Yes</option>";
		//HTML += "</select></td>";
		HTML += "</tr>";

		jQuery('#ewd-feup-level-payments-table tr:last').before(HTML);

		ID++;
		jQuery(this).data('nextid', ID); //updates but doesn't show in DOM

		SetLevelDeleteHandlers();
		GetEWDFEUPLevelOptions();

		event.preventDefault();
	});
});

function SetLevelDeleteHandlers() {
	jQuery('.ewd-feup-delete-level-payment').on('click', function(event) {
		var ID = jQuery(this).data('levelpaymentid');
		var tr = jQuery('#ewd-feup-level-payment-row-'+ID);

		tr.fadeOut(400, function(){
            tr.remove();
        });

		event.preventDefault();
	});
}

function GetEWDFEUPLevelOptions() {
	var data = 'action=get_ewd_feup_levels';
	jQuery.post(ajaxurl, data, function(response) {
		Levels = jQuery.parseJSON(response);
		jQuery(Levels).each(function() {
			jQuery('.ewd-feup-insert-levels').append("<option value='"+this.Level_ID+"'>"+this.Level_Name+"</option>");
		});
		jQuery('.ewd-feup-insert-levels').removeClass('ewd-feup-insert-levels');
	});
}

jQuery(document).ready(function() {
	jQuery('.ewd-feup-spectrum').spectrum({
		showInput: true,
		showInitial: true,
		preferredFormat: "hex",
		allowEmpty: true
	});

	jQuery('.ewd-feup-spectrum').css('display', 'inline');

	jQuery('.ewd-feup-spectrum').on('change', function() {
		if (jQuery(this).val() != "") {
			jQuery(this).css('background', jQuery(this).val());
			var rgb = EWD_FEUP_hexToRgb(jQuery(this).val());
			var Brightness = (rgb.r * 299 + rgb.g * 587 + rgb.b * 114) / 1000;
			if (Brightness < 100) {jQuery(this).css('color', '#ffffff');}
			else {jQuery(this).css('color', '#000000');}
		}
		else {
			jQuery(this).css('background', 'none');
		}
	});

	jQuery('.ewd-feup-spectrum').each(function() {
		if (jQuery(this).val() != "") {
			jQuery(this).css('background', jQuery(this).val());
			var rgb = EWD_FEUP_hexToRgb(jQuery(this).val());
			var Brightness = (rgb.r * 299 + rgb.g * 587 + rgb.b * 114) / 1000;
			if (Brightness < 100) {jQuery(this).css('color', '#ffffff');}
			else {jQuery(this).css('color', '#000000');}
		}
	});
});

function EWD_FEUP_hexToRgb(hex) {
    var result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hex);
    return result ? {
        r: parseInt(result[1], 16),
        g: parseInt(result[2], 16),
        b: parseInt(result[3], 16)
    } : null;
}
