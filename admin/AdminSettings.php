<?php

namespace VolunteersGuide;

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    VolunteersGuide
 * @subpackage VolunteersGuide/admin
 * @author     Fabian Horlacher
 */
class AdminSettings
{
	public function updatedOption($option_name, $old_value, $value)
	{
		if (substr($option_name, 0, 7) !== 'volunG_') {
			return;
		}
		if ($option_name == Core::getWpId('inlineConfig')) {
			return;
		}
		MapConfig::resetCache();
	}

	public function pageSettings()
	{
		$tabs       = [
			'General',
			'World Map',
			'World Map - countries',
		];
		$firstTab   = array_keys($tabs);
		$currentTab = !empty($_GET['tab']) && array_key_exists($_GET['tab'], $tabs) ? sanitize_title($_GET['tab']) : $firstTab[0];
		$page       = 'volunGSettings';
		include Infos::getPluginDir() . 'admin/partials-settings/settings-tabs.php';
	}

	public function pageSettings0()
	{
		$page = 'volunGFields0';
		include Infos::getPluginDir() . 'admin/partials-settings/settings-0.php';
	}

	public function pageSettings1()
	{
		$page = 'volunGFields1';
		include Infos::getPluginDir() . 'admin/partials-settings/settings-1.php';
	}

	public function pageSettings2()
	{
		$page = 'volunGFields2';
		include Infos::getPluginDir() . 'admin/partials-settings/settings-2.php';
	}

	protected function setupSections()
	{
		$areas = ConfigDefinition::getSections();
		foreach ($areas as $name => $section) {
			add_settings_section($name, $section['title'], null, $section['page']);
		}
	}

	/**
	 * Replacement for do_settings_sections()
	 * Supports to add HTML in 'addPre', 'addPost', and 'sub'
	 *
	 * @param $page
	 */
	protected function doSettingsSections($page)
	{
		global $wp_settings_sections, $wp_settings_fields;

		if (!isset($wp_settings_sections[$page])) {
			return;
		}

		$sections = ConfigDefinition::getSections();

		foreach ((array)$wp_settings_sections[$page] as $section) {
			if (isset($sections[$section['id']]['addPre'])) {
				echo $sections[$section['id']]['addPre'];
			}

			if ($section['title']) {
				echo "<h2>{$section['title']}</h2>\n";
			}

			if (isset($sections[$section['id']]['sub'])) {
				echo $sections[$section['id']]['sub'];
			}
			if ($section['callback']) {
				call_user_func($section['callback'], $section);
			}

			if (!isset($wp_settings_fields) || !isset($wp_settings_fields[$page]) || !isset($wp_settings_fields[$page][$section['id']])) {
				continue;
			}
			echo '<table class="form-table">';
			do_settings_fields($page, $section['id']);
			echo '</table>';

			if (isset($sections[$section['id']]['addPost'])) {
				echo $sections[$section['id']]['addPost'];
			}
		}
	}

	public function initSettings()
	{
		$this->setupFields();
		$this->setupSections();
	}

	protected function setupFields()
	{
		$sections = ConfigDefinition::getSections();
		$fields   = ConfigDefinition::getFields();
		$callback = [$this, 'fieldCallback',];
		foreach ($fields as $field) {
			$page      = $sections[$field['section']]['page'];
			$id        = Core::getWpId($field['uid']);
			$fieldType = isset($field['type']) ? $field['type'] : null;
			$default   = isset($field['default']) ? ['default' => $field['default']] : [];
			switch ($fieldType) {
				case 'mapContinent':
					add_settings_field($id, $field['label'], $callback, $page, $field['section'], $field);
					register_setting($page, $id, $default);
					register_setting($page, $id . Config::GLUE_PART . Config::PART_INTENSITY, ['default' => 100]);
					break;
				case 'mapCountry':
					add_settings_field($id, $field['label'], $callback, $page, $field['section'], $field);
					register_setting($page, $id, $default);
					register_setting($page, $id . Config::GLUE_PART . Config::PART_INTENSITY, ['default' => 100]);
					register_setting($page, $id . Config::GLUE_PART . Config::PART_PAGE);
					register_setting($page, $id . Config::GLUE_PART . Config::PART_URL);
					break;
				default:
					add_settings_field($id, $field['label'], $callback, $page, $field['section'], $field);
					register_setting($page, $id, $default);
					break;
			}
		}
	}

	public function fieldCallback($arguments)
	{
		$uid         = $arguments['uid'];
		$wpid        = Core::getWpId($uid);
		$type        = $arguments['type'];
		$placeholder = (isset($arguments['placeholder']) && $arguments['placeholder'] !== false && $arguments['placeholder'] !== 0)
			? $arguments['placeholder'] : '';

		// Check which type of field we want
		switch ($type) {
			case 'text': // If it is a text field
			case 'input':
			case 'file':
			case 'number':
			default:
				$value = str_replace('"', '&quot;', Config::getValue($uid));
				printf(
					'<input name="%1$s" id="%1$s" type="%2$s" placeholder="%3$s" value="%4$s" size="40" />',
					$wpid,
					$type,
					$placeholder,
					$value
				);
				break;
			case 'colorpicker':
				$value = str_replace('"', '&quot;', Config::getValue($uid));
				echo '<span class="color-preview-box" style="background-color: #' . ($value ?: 'FFF') . '"></span>';
				printf(
					'<input name="%1$s" id="%1$s" type="%2$s" placeholder="%3$s" value="%4$s" data-wheelcolorpicker class="color-picker" size="6" />',
					$wpid,
					$type,
					$placeholder,
					$value
				);
				break;
			case 'checkbox':
				$value = Config::getValue($uid);
				$this->createInputCheckBox($wpid, $value);
				break;
			case 'mapContinent':
				$value = Config::getValue($uid);
				$this->createInputCheckBox($wpid, $value);
				$valueIntensity = Config::getValue($uid, Config::PART_INTENSITY);
				printf(
					'<input name="%1$s" id="%1$s" type="number" placeholder="%2$s" value="%3$s" min="0" max="100" size="5" />',
					$wpid . Config::GLUE_PART . Config::PART_INTENSITY,
					'Visibility (percent)',
					$valueIntensity
				);
				break;
			case 'mapCountry':
				echo '<div class="mapCountry">';
				$value = Config::getValue($uid);
				echo '<div class="mode">';
				$this->createInputOptionBox($wpid, 'Disabled', '', $value);
				$this->createInputOptionBox($wpid, 'Plato', 'plato', $value);
				$this->createInputOptionBox($wpid, 'Page', 'page', $value);
				$this->createInputOptionBox($wpid, 'URL', 'url', $value);
				echo '</div>';
				echo '<div class="details">';
				echo '<br/> ';
				$valueIntensity = Config::getValue($uid, Config::PART_INTENSITY);
				printf(
					'<input name="%1$s" id="%1$s" type="number" placeholder="%2$s" value="%3$s" min="0" max="100" />',
					$wpid . Config::GLUE_PART . Config::PART_INTENSITY,
					'Visibility (percent)',
					$valueIntensity
				);
				$valuePage = Config::getValue($uid, Config::PART_PAGE);
				$args      = [
					'name'             => $wpid . Config::GLUE_PART . Config::PART_PAGE,
					'selected'         => $valuePage,
					'suppress_filters' => true, // disable WPML language filtering
				];
				wp_dropdown_pages($args);
				$valueUrl = Config::getValue($uid, Config::PART_URL);
				$this->createInputBox($wpid . Config::GLUE_PART . Config::PART_URL, 'text', 'URL', $valueUrl, 'class="url" size="60"');
				echo '</div>';
				echo '</div>';
				break;
			case 'rotate':
				$value = Config::getValue($uid);
				printf(
					'<input name="%1$s" id="%1$s" type="number" placeholder="%2$s" value="%3$s" min="0" max="359" />',
					$wpid,
					$placeholder,
					$value
				);
				break;
			case 'textarea': // If it is a textarea
				$value = str_replace('"', '&quot;', Config::getValue($uid));
				printf(
					'<textarea name="%1$s" id="%1$s" placeholder="%2$s" rows="20" cols="180">%3$s</textarea>',
					$wpid,
					$placeholder,
					$value
				);
				break;
			case 'wysiwyg': // If it is a wysiwyg editor
				// https://developer.wordpress.org/reference/functions/wp_editor/
				$value = Config::getValue($uid);
				wp_editor($value, $wpid);
				break;
			case 'select': // If it is a select dropdown
				if (!empty ($arguments['options']) && is_array($arguments['options'])) {
					Strings::createSelect($arguments['options'], Config::getValue($uid), $wpid);
				}
				break;
			case 'wpMedia':
				$value = Config::getValue($uid);
				printf(
					'<input name="%1$s" id="%1$s" type="text" placeholder="%2$s" value="%3$s" size="35" />',
					$wpid,
					$placeholder,
					$value
				);
				echo '<button class="uploadButton" data-input-id="' . $wpid . '">Select</button>';
				break;
			case 'wpPage': // If it is a select dropdown
				$value = Config::getValue($uid);
				$args  = [
					'name'             => $wpid,
					'selected'         => $value,
					'suppress_filters' => true, // disable WPML language filtering
				];
				if (isset($arguments['optionNone']) && $arguments['optionNone']) {
					$args['show_option_none'] = $arguments['optionNone'];
				}
				wp_dropdown_pages($args);
				break;
		}

		// If there is help text
		if (isset($arguments['helper']) && $helper = $arguments['helper']) {
			printf('<span class="helper"> %s</span>', $helper); // Show it
		}

		// If there is supplemental text
		if (isset($arguments['supplemental']) && $supplemental = $arguments['supplemental']) {
			printf('<p class="description">%s</p>', $supplemental); // Show it
		}
	}

	protected function createInputCheckBox($name, $value)
	{
		printf(
			'<input name="%1$s" id="%1$s" type="%2$s" value="1" %3$s/>',
			$name,
			'checkbox',
			$value ? 'checked="checked"' : ''
		);
	}

	protected function createInputOptionBox($name, $placeholder, $boxValue, $value)
	{
		$id = $name . '_' . $boxValue;
		$this->createInputBox(
			$name,
			'radio',
			'',
			$boxValue,
			($boxValue == $value) ? 'checked="checked"' : '',
			$id
		);
		echo '<label for="' . $id . '">' . $placeholder . '</label>&nbsp;';
	}

	/**
	 * @param $name
	 * @param $type
	 * @param $placeholder
	 * @param $value
	 * @param $append
	 * @param $id
	 */
	protected function createInputBox($name, $type, $placeholder, $value, $append = null, $id = null)
	{
		if ($id === null) {
			$id = $name;
		}
		$append = ($append === null) ? '' : ' ' . $append;
		printf(
			'<input name="%1$s" id="%2$s" type="%3$s" placeholder="%4$s" value="%5$s"%6$s/>',
			$name,
			$id,
			$type,
			$placeholder,
			$value,
			$append
		);
	}
}