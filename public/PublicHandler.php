<?php

namespace VolunteersGuide;

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://github.com/Horlacher/VolunteersGuide
 * @since      1.0.0
 *
 * @package    VolunteersGuide
 * @subpackage VolunteersGuide/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    VolunteersGuide
 * @subpackage VolunteersGuide/public
 * @author     Fabian Horlacher
 */
class PublicHandler
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

	/** @var $nonceId string */
	private $nonceId = 'volunG_ajax_submit';

	/**
	 * Initialize the class and set its properties.
	 *
	 * @param string $pluginName The name of the plugin.
	 * @param string $version    The version of this plugin.
	 *
	 * @since 1.0.0
	 */
	public function __construct($pluginName, $version)
	{
		$this->pluginName = $pluginName;
		$this->version    = $version;
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueueStyles()
	{
		wp_enqueue_style($this->pluginName, plugin_dir_url(__FILE__) . 'css/volunG-public.min.css', [], $this->version, 'all');
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueueScripts()
	{
		if (!WP_DEBUG) {
			wp_enqueue_script(
				$this->pluginName . 'public',
				plugin_dir_url(__FILE__) . 'js/volunG-public.min.js',
				['jquery',],
				$this->version,
				false
			);
		} else {
			wp_enqueue_script(
				$this->pluginName . 'public.dep',
				plugin_dir_url(__FILE__) . 'js/volunG-public.dep.js',
				['jquery',],
				$this->version,
				false
			);
			wp_enqueue_script(
				$this->pluginName . 'public.dev',
				plugin_dir_url(__FILE__) . 'js/volunG-public.js',
				['jquery',],
				$this->version,
				false
			);
		}
	}

	public function shortcode_projectButton($atts = [], $content = null)
	{
		$this->enqueueStyles();
		// normalize attribute keys, lowercase
		$atts = array_change_key_case((array)$atts, CASE_LOWER);
		if (!isset($atts['code'])) {
			return __('Shortcode must include the attribute "code" with the plato project code as value', 'volunG');
		}

		$projCode   = sanitize_text_field($atts['code']);
		$platoOrgId = Config::getValue('platoOrgId');
		$content    = $content
			? sanitize_text_field($content)
			: sanitize_text_field(__('Apply here!', 'volunG'));

		ob_start();
		include Infos::getPluginDir() . 'public/partials/projectButton.php';
		return ob_get_clean();
	}

	public function shortcode_searchForm()
	{
		$platoOrgId = Config::getValue('platoOrgId');

		ob_start();
		include Infos::getPluginDir() . 'public/partials/searchForm.php';
		return ob_get_clean();
	}

	public function shortcode_worldMap()
	{
		$this->enqueueStyles();
		$this->enqueueScripts();
		$data = Config::getValue('inlineConfig') ? MapConfig::getMapConfig() : null;

		ob_start();
		include Infos::getPluginDir() . 'public/partials/worldMap.php';
		return ob_get_clean();
	}

	public function echoMapConfig()
	{
		header('Content-type: text/json');
		header('Expires: 0');
		header('Cache-Control: must-revalidate');
		header('Pragma: public');

		$locale = isset($_REQUEST['locale']) ? sanitize_file_name(substr($_REQUEST['locale'], 0, 2)) : null;
		MapConfig::getMapConfig($locale, true);

		exit; // exit is required to keep Wordpress from echoing a trailing "0"
		// https://wordpress.stackexchange.com/questions/97502/admin-ajax-is-returning-0
	}

}