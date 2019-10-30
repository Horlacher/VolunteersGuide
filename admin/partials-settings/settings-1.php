<?php

namespace VolunteersGuide;

/**
 * @var $this AdminSettings
 * @var $page string
 */
?>
<?php
submit_button();
settings_fields($page);
$this->doSettingsSections($page);
submit_button();
?>
<script>
	$('.color-picker').on('colorchange', function (e) {
		var color = $(this).wheelColorPicker('value');
		$(this).parent().find('.color-preview-box').css('background-color', '#' + color);
	});
</script>