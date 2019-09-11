<?php

namespace Plato;
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://github.com/horlacher/wp-plugin-plato
 * @since      1.0.0
 *
 * @package    Plato
 * @subpackage Plato/admin
 */

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

		// Hook into the admin menu
		add_action('admin_menu', [$this, 'setupAdminMenu']);

		// AJAX
		$this->setupAdminAjaxActions();
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueueStyles()
	{
		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style($this->pluginName, plugin_dir_url(__FILE__) . 'css/plato-admin.min.css', [], $this->version, 'all');
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueueScripts()
	{
		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		//wp_enqueue_script($this->pluginName . '_admin', plugin_dir_url(__FILE__) . 'js/plato-admin.min.js', ['jquery'], $this->version, false);

		wp_enqueue_media();
		$platoJsArr = [
			'uploader' => [
				'title' => 'Select signature sheet',
				'text'  => 'Select',
			],
		];
		wp_localize_script($this->pluginName . '_admin', 'platoAdmin', $platoJsArr);
	}

	public function setupAdminAjaxActions()
	{
		require_once Infos::getPluginDir() . 'admin/AdminPages.php';
		$adminPages = new AdminPages();
		$prefix = 'admin_post_plato_';

		//add_action($prefix . 'mail_test', [$adminPages, 'testMail']);
	}

	public function setupAdminMenu()
	{
		require_once Infos::getPluginDir() . 'admin/AdminPages.php';
		$adminPages = new AdminPages();
		require_once Infos::getPluginDir() . 'admin/AdminSettings.php';
		$adminSettings = new AdminSettings();

		// Add the menu item and page
		$page_title = 'Overview';
		$slug = 'plato';
		$icon = 'dashicons-location-alt';
		$position = 75;

		$capabilityOverview = 'plato_overview';
		$capabilitySettings = 'manage_options';

		$menuTitle = 'plato';
		$callback = [$adminPages, 'pageOverview'];
		add_menu_page($page_title, $menuTitle, $capabilityOverview, $slug, $callback, $icon, $position);

		$menuTitle = 'Overview';
		$callback = [$adminPages, 'pageOverview'];
		add_submenu_page($slug, $menuTitle, $menuTitle, $capabilitySettings, $slug . 'Overview', $callback);

		$menuTitle = 'Settings';
		$callback = [$adminSettings, 'pageSettings'];
		add_submenu_page($slug, $menuTitle, $menuTitle, $capabilitySettings, $slug . 'Settings', $callback);
	}

	static protected $messages = [];
	static protected $errors = [];

	/**
	 * Add a message.
	 *
	 * @param string $text Message.
	 */
	public static function addMessage($text)
	{
		self::$messages[] = $text;
	}

	/**
	 * Add an error.
	 *
	 * @param string $text Message.
	 */
	public static function addError($text)
	{
		self::$errors[] = $text;
	}

	/**
	 * Output messages + errors.
	 */
	public static function showMessages()
	{
		if (count(self::$errors) > 0) {
			foreach (self::$errors as $error) {
				echo '<div id="message" class="error inline"><p><strong>' . esc_html($error) . '</strong></p></div>';
			}
		} elseif (count(self::$messages) > 0) {
			foreach (self::$messages as $message) {
				echo '<div id="message" class="updated inline"><p><strong>' . esc_html($message) . '</strong></p></div>';
			}
		}
	}

	public static function checkAccess($capability)
	{
		Core::checkNonce();
		if (!current_user_can($capability)) {
			wp_die(esc_html__('You are not allowed to access this page.', 'wp-control'));
		}
	}
}