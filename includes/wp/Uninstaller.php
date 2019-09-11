<?php

namespace Plato;

/**
 * Fired before plugin uninstall
 *
 * @link       https://github.com/horlacher/wp-plugin-plato
 * @since      1.0.0
 *
 * @package    Plato
 * @subpackage Plato/includes
 */

/**
 * Fired during plugin Uninstall.
 *
 * This class defines all code necessary to run during the plugin's uninstall.
 * More about when to use deactivation or uninstall hook:
 * https://developer.wordpress.org/plugins/plugin-basics/uninstall-methods/
 *
 * @since      1.0.0
 * @package    Plato
 * @subpackage Plato/includes
 * @author     Fabian Horlacher
 */
class Uninstaller
{

	/**
	 * Short Description. (use period)
	 *
	 * Removes database table.
	 *
	 * @since    1.0.0
	 */
	public static function uninstall()
	{
	}
}
