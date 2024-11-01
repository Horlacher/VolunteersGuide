<?php
namespace VolunteersGuide;
/**
 * @var string $platoOrgId
 */
?>

<div id="volunG-world-map"><div class="loading"></div></div>
<script>
	jQuery(document).ready(function ($) {
		var language          = "<?= Infos::getUserLanguage() ?>",
			ajaxUrl           = "<?= admin_url('admin-ajax.php') ?>";
		var continentMap = new volunG_continentMap(language, ajaxUrl);
	});
</script>                                                                                                                                               