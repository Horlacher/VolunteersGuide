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
	$mapCountry = jQuery('.mapCountry');
	showRelativeOnVal($mapCountry, '.mode input', '.details select', 'page',);
	showRelativeOnVal($mapCountry, '.mode input', '.details input.url', 'url',);
	hideRelativeOnVal($mapCountry, '.mode input', '.details', '',);
</script>