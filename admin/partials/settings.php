<?php

namespace VolunteersGuide;

/**
 * @var $this      AdminSettings
 * @var $page      string
 * @var $languages array
 */
?>

<div class="wrap demovox">
	<form method="post" id="mainform" action="options.php" enctype="multipart/form-data">
		<?php wp_nonce_field($page); ?>
		<h1 class="screen-reader-text"><?php echo esc_html('Bla'); ?></h1>
		<?php
		Admin::showMessages();
		?>
		<?php
		settings_fields($page);
		$this->doSettingsSections($page);
		submit_button();
		?>
	</form>
</div>