<?php

namespace VolunteersGuide;

/**
 * @var $this AdminSettings
 * @var $page string
 * @var $tabs array
 * @var $currentTab array
 */
if (!defined('ABSPATH')) {
	exit;
}

$tabExists = isset($tabs[$currentTab]);
$currentTabLabel = isset($tabs[$currentTab]) ? $tabs[$currentTab] : '';

if (!$tabExists) {
	wp_safe_redirect(admin_url('admin.php?page=wc-settings'));
	exit;
}
?>
<div class="wrap volunG">
	<form method="post" id="mainform" action="options.php" enctype="multipart/form-data">
		<?php wp_nonce_field($page); ?>
		<nav class="nav-tab-wrapper woo-nav-tab-wrapper">
			<?php
			foreach ($tabs as $tabId => $label) {
				echo '<a href="' . esc_html(admin_url('admin.php?page=volunGSettings&tab=' . esc_attr($tabId))) . '" class="nav-tab ' . ($currentTab == $tabId ? 'nav-tab-active' : '') . '">' . esc_html($label) . '</a>';
			}
			?>
		</nav>
		<h1 class="screen-reader-text"><?php echo esc_html($currentTabLabel); ?></h1>
		<?php
		$this->{'pageSettings' . $currentTab}();
		?>
	</form>
</div>