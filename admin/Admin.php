<?php

namespace VolunteersGuide;
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://github.com/Horlacher/VolunteersGuide
 * @since      1.0.0
 *
 * @package    VolunteersGuide
 * @subpackage VolunteersGuide/admin
 */

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
class Admin
{

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $pluginName The ID of this plugin.
	 */
	private $pluginName;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $version The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @param string $pluginName The name of this plugin.
	 * @param string $version The version of this plugin.
	 * @since    1.0.0
	 */
	public function __construct($pluginName, $version)
	{
		$this->pluginName = $pluginName;
		$this->version = $version;
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueueStyles()
	{
		wp_enqueue_style($this->pluginName, plugin_dir_url(__FILE__) . 'css/volunG-admin.min.css', [], $this->version, 'all');
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueueScripts()
	{
		wp_enqueue_script($this->pluginName . '_admin', plugin_dir_url(__FILE__) . 'js/volunG-admin.min.js', ['jquery'], $this->version, false);
	}

	public function setupAdminMenu()
	{
		require_once Infos::getPluginDir() . 'admin/AdminPages.php';
		$adminPages = new AdminPages();
		require_once Infos::getPluginDir() . 'admin/AdminSettings.php';
		$adminSettings = new AdminSettings();

		// Add the menu item and page
		$page_title = 'Overview';
		$slug = 'volunG';
		$icon = 'dashicons-location-alt';
		$position = 75;

		$capabilityOverview = 'volunG_overview';
		$capabilitySettings = 'manage_options';

		$menuTitle = 'Volunteer\'s Guide';
		$callback = [$adminPages, 'pageOverview'];
		add_menu_page($page_title, $menuTitle, $capabilityOverview, $slug, $callback, $icon, $position);

		$menuTitle = 'Overview';
		$callback = [$adminPages, 'pageOverview'];
		add_submenu_page($slug, $menuTitle, $menuTitle, $capabilitySettings, $slug . 'Overview', $callback);

		$menuTitle = 'Settings';
		$callback = [$adminSettings, 'pageSettings'];
		add_submenu_page($slug, $menuTitle, $menuTitle, $capabilitySettings, $slug . 'Settings', $callback);
	}
}