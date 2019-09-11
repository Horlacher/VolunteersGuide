<?php

namespace Plato;
/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://github.com/horlacher/wp-plugin-plato
 * @since      1.0.0
 *
 * @package    Plato
 * @subpackage Plato/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Plato
 * @subpackage Plato/includes
 * @author     Fabian Horlacher
 */
class i18n
{
	public static $defaultCountry = 'CH';

	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function loadPluginTextdomain()
	{
		load_plugin_textdomain(
			'plato',
			false,
			dirname(dirname(plugin_basename(__FILE__))) . '/languages/'
		);
	}

}
