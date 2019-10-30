var $ = jQuery.noConflict();

function showRelativeOnVal($containers, check, showHideSel, value, invert) {
	var invert = (invert !== undefined) ? invert : false;
	console.log('showRelativeOnVal', [$containers, check, showHideSel, value, invert]);
	$check = $containers.find(check);

	if ($check.is("input")) {
		$check.keyup(function () {
			var $showHide = $(this).closest($containers).find(showHideSel);
			showHideEl($showHide, isIn($(this).val(), value), invert);
		});
	}
	$check.change(function () {
		var $showHide = $(this).closest($containers).find(showHideSel);
		showHideEl($showHide, isIn($(this).val(), value), invert);
	});
	$containers.each(function () {
		var $showHide = $(this).find(showHideSel);
		showHideEl($showHide, isIn($(this).find(check).filter(':checked').val(), value), invert);
	});
}
function hideRelativeOnVal($containers, check, showHideSel, value) {
	showRelativeOnVal($containers, check, showHideSel, value, true);
}

function showOnVal($check, $showHide, value, invert) {
	var invert = (invert !== undefined) ? invert : false;
	if ($check.is("input")) {
		$check.keyup(function () {
			showHideEl($showHide, isIn($(this).val(), value));
		});
	}
	$check.change(function () {
		showHideEl($showHide, isIn($(this).val(), value));
	});
	showHideEl($showHide, $check.val() === value);
}

function hideOnVal($check, $showHide, value) {
	showOnVal($check, $showHide, value, true)
}

function hideOnChecked($check, $showHide) {
	$check.change(function () {
		showHideEl($showHide, !$(this).is(':checked'));
	});
	showHideEl($showHide, !$check.is(':checked'));
}

function showOnChecked($check, $showHide) {
	$check.change(function () {
		showHideEl($showHide, $(this).is(':checked'));
	});
	showHideEl($showHide, $check.is(':checked'));
}

function isIn(needle, haystack) {
	if (Array.isArray(haystack)) {
		return haystack.indexOf(needle) !== -1;
	} else {
		return needle === haystack;
	}
}

function showHideEl($els, show, invert) {
	var invert = (invert !== undefined) ? invert : false;
	if ((show && !invert) || (!show && invert)) {
		var $el;
		$els.each(function () {
			$el = $(this);
			if (!$el.hasClass('hidden')) {
				$el.show();
			}
		});
	} else {
		$els.hide();
	}
}

jQuery(document).ready(function ($) {
	$('.color-preview-box').click(function(){
		$(this).closest('td').find('.color-picker').wheelColorPicker('show');
	});
});