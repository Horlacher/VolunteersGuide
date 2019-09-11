<?php

namespace Plato;

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Plato
 * @subpackage Plato/admin
 * @author     Fabian Horlacher
 */
class AdminSettings
{
	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 */
	public function __construct()
	{
		// Hook into the admin menu
		add_action('admin_init', [$this, 'setupFields']);
		add_action('admin_init', [$this, 'setupSections']);
	}

	public function pageSettings()
	{
		$page = 'platoSettings';
		include Infos::getPluginDir() . 'admin/partials/settings.php';
	}

	public function setupSections()
	{
		$areas = ConfigVars::getSections();
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

		$sections = ConfigVars::getSections();

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

	public function setupFields()
	{
		$sections = ConfigVars::getSections();
		$fields = ConfigVars::getFields();
		$callback = [$this, 'fieldCallback',];
		foreach ($fields as $field) {
			$page = $sections[$field['section']]['page'];
			$id = Core::getWpId($field['uid']);
			$fieldType = isset($field['type']) ? $field['type'] : null;
			switch ($fieldType) {
				default:
					add_settings_field($id, $field['label'], $callback, $page, $field['section'], $field);
					register_setting($page, $id);
					break;
			}
		}
	}

	public function fieldCallback($arguments)
	{
		$uid = $arguments['uid'];
		$wpid = Core::getWpId($uid);
		$type = $arguments['type'];
		$placeholder = (isset($arguments['placeholder']) && $arguments['placeholder'] !== false && $arguments['placeholder'] !== 0)
			? $arguments['placeholder'] : '';

		// Check which type of field we want
		switch ($type) {
			case 'text': // If it is a text field
			case 'input':
			case 'file':
			case 'number':
				$value = str_replace('"', '&quot;', Config::getValue($uid));
				printf(
					'<input name="%1$s" id="%1$s" type="%2$s" placeholder="%3$s" value="%4$s" size="40" />',
					$wpid,
					$type,
					$placeholder,
					$value
				);
				break;
			case 'checkbox':
				$value = Config::getValue($uid);
				printf(
					'<input name="%1$s" id="%1$s" type="%2$s" value="1" %3$s/>',
					$wpid,
					$type,
					$value ? 'checked="checked"' : ''
				);
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
				$args = [
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
}