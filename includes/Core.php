<?php

namespace VolunteersGuide;

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://github.com/Horlacher/VolunteersGuide
 * @since      1.0.0
 *
 * @package    VolunteersGuide
 * @subpackage VolunteersGuide/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    VolunteersGuide
 * @subpackage VolunteersGuide/includes
 * @author     Fabian Horlacher
 */
class Core
{

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string $pluginName The string used to uniquely identify this plugin.
	 */
	protected $pluginName;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string $version The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct()
	{
		if (defined('volunteersGuide_VERSION')) {
			$this->version = volunteersGuide_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->pluginName = 'VolunteersGuide';
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Loader. Orchestrates the hooks of the plugin.
	 * - i18n. Defines internationalization functionality.
	 * - Admin. Defines all hooks for the admin area.
	 * - PublicHandler. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function loadDependencies()
	{
		$pluginDir = self::getPluginDir();
		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once $pluginDir . 'includes/helpers/Loader.php';

		/**
		 * The helper classes
		 */
		require_once $pluginDir . 'includes/helpers/Infos.php';
		require_once $pluginDir . 'includes/helpers/Countries.php';
		require_once $pluginDir . 'includes/helpers/Strings.php';
		require_once $pluginDir . 'includes/helpers/MapConfig.php';
		require_once $pluginDir . 'includes/helpers/Config.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once $pluginDir . 'includes/i18n.php';

		/**
		 * The class responsible for defining all config fields.
		 */
		require_once $pluginDir . 'includes/ConfigDefinition.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once $pluginDir . 'admin/Admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once $pluginDir . 'public/PublicHandler.php';
	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function setLocale()
	{
		$plugin_i18n = new i18n();

		Loader::addAction('plugins_loaded', $plugin_i18n, 'loadPluginTextdomain');
	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function defineAdminHooks()
	{
		$pluginAdmin = new Admin($this->getPluginName(), $this->getVersion());

		Loader::addAction('admin_enqueue_scripts', $pluginAdmin, 'enqueueStyles');
		Loader::addAction('admin_enqueue_scripts', $pluginAdmin, 'enqueueScripts');
		Loader::addAction('admin_menu', $pluginAdmin, 'setupAdminMenu');

		require_once Infos::getPluginDir() . 'admin/AdminSettings.php';
		$adminSettings = new AdminSettings();
		add_action('admin_init', [$adminSettings, 'initSettings']);

		// option update actions
		Loader::addAction('updated_option', $adminSettings, 'updatedOption', 10, 3);
	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function definePublicHooks()
	{
		$pluginPublic = new PublicHandler($this->getPluginName(), $this->getVersion());

		Loader::addAction('public_enqueue_styles', $pluginPublic, 'enqueueStyles');
		Loader::addAction('public_enqueue_scripts', $pluginPublic, 'enqueueScripts');
		Loader::addAction('public_define_posttype', $pluginPublic, 'definePosttype');

		// AJAX
		Loader::addAjaxPublic('volunG_MapConfig', $pluginPublic, 'echoMapConfig');
	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function defineShortcodes()
	{
		$pluginPublic = new PublicHandler($this->getPluginName(), $this->getVersion());
		Loader::addShortcode('volung_projectbutton', $pluginPublic, 'shortcode_projectButton');
		Loader::addShortcode('volung_searchform', $pluginPublic, 'shortcode_searchForm');
		Loader::addShortcode('volung_worldmap', $pluginPublic, 'shortcode_worldMap');
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run()
	{
		$this->loadDependencies();
		$this->setLocale();
		if (is_admin()) {
			$this->defineAdminHooks();
		}
		$this->definePublicHooks();
		$this->defineShortcodes();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @return    string    The name of the plugin.
	 * @since     1.0.0
	 */
	public function getPluginName()
	{
		return $this->pluginName;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @return    string    The version number of the plugin.
	 * @since     1.0.0
	 */
	public function getVersion()
	{
		return $this->version;
	}

	private static $optionPrefix = 'volunG_';

	/**
	 * @param $id
	 *
	 * @return string
	 */
	public static function getWpId($id)
	{
		$fieldUidPrefix = self::$optionPrefix;
		return $fieldUidPrefix . $id;
	}

	/**
	 * @param string $id
	 *
	 * @return mixed Value set for the option. False if not set.
	 */
	public static function getOption($id)
	{
		$wpId = self::getWpId($id);
		return get_option($wpId);
	}

	/**
	 * Update or set option
	 *
	 * @param string      $id       Option name. Expected to not be SQL-escaped.
	 * @param mixed       $value    Option value. Must be serializable if non-scalar. Expected to not be SQL-escaped.
	 * @param string|bool $autoload Optional. Whether to load the option when WordPress starts up
	 *
	 * @return bool False if value was not updated and true if value was updated.
	 */
	public static function setOption($id, $value, $autoload = null)
	{
		$wpId = self::getWpId($id);
		return update_option($wpId, $value, $autoload);
	}

	/**
	 * @param string $id
	 *
	 * @return bool True, if option is successfully deleted. False on failure.
	 */
	public static function delOption($id)
	{
		return delete_option(Core::getWpId($id));
	}

	static function showError($error, $statusCode = null)
	{
		$isError = !(substr($statusCode, 0, 1) == 2 || substr($statusCode, 0, 1) == 3);
		$string  = self::logMessage($statusCode . ' - ' . $error, $isError ? 'error' : 'info');
		if (WP_DEBUG) {
			echo $string;
		}
		if ($statusCode !== null) {
			http_response_code($statusCode);
			switch ($statusCode) {
				default:
					$msg = 'unknown error';
					break;
				case 400:
					$msg = 'Invalid form values received';
					break;
				case 404:
					$msg = 'Resource not found';
					break;
				case 405:
					$msg = 'Requested resource does not support this operation';
					break;
				case 500:
					$msg = 'Internal server error';
					break;
			}
			wp_die($msg, $statusCode);
		}
		return;
	}

	static function logMessage($message, $level = 'error', $type = null)
	{
		if (!WP_DEBUG) {
			return;
		}
		$trace  = debug_backtrace();
		$source = $trace[1];
		if ($source['function'] == 'showError' && $source['function'] == 'VolunteersGuide\Core') {
			$source = $trace[2];
		}
		$date   = date('Y-m-d G:i:s', time());
		$string = $date . ' [' . $level . '] ' . $source['file'] . ':' . $source['line'] . "\n" . $message . "\n";

		$fn = Infos::getPluginDir() . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'debug.VolunteersGuide'
			  . ($type ? '.' . $type : '') . '.php';
		if (!file_exists($fn)) {
			$string = '<?php exit;' . "\n" . $string;
		}
		$fp = fopen($fn, 'a');
		fputs($fp, $string);
		fclose($fp);
		return $string;
	}

	public static function getPluginDir()
	{
		return plugin_dir_path(dirname(__FILE__));
	}
}