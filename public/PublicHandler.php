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
	private $nonceId = 'voluG_ajax_submit';

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

		add_action('init', [$this, 'initPlugin']);

		// Shortcodes
		add_shortcode('volug_projectbutton', [$this, 'shortcode_projectButton',]);
		add_shortcode('volug_searchform', [$this, 'shortcode_searchForm',]);
		add_shortcode('volug_worldmap', [$this, 'shortcode_worldMap',]);
	}

	public function initPlugin()
	{
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueueStyles()
	{
		wp_enqueue_style($this->pluginName, plugin_dir_url(__FILE__) . 'css/voluG-public.min.css', [], $this->version, 'all');
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueueScripts()
	{
		wp_enqueue_script(
			$this->pluginName,
			plugin_dir_url(__FILE__) . 'js/voluG-public.min.js',
			['jquery',],
			$this->version,
			false
		);
	}

	public function shortcode_projectButton($atts = [], $content = null)
	{
		$this->enqueueStyles();
		// normalize attribute keys, lowercase
		$atts = array_change_key_case((array)$atts, CASE_LOWER);
		if (!isset($atts['code'])) {
			return __('Shortcode must include the attribute "code" with the plato project code as value');
		}

		$projCode = sanitize_text_field($atts['code']);
		$platoOrgID = Config::getValue('platoOrgID');
		$content    = $content
			? sanitize_text_field($content)
			: sanitize_text_field(Config::getValue('button_default_text'));

		ob_start();
		include Infos::getPluginDir() . 'public/partials/projectButton.php';
		return ob_get_clean();
	}

	public function shortcode_searchForm()
	{
		$platoOrgID = Config::getValue('platoOrgID');

		ob_start();
		include Infos::getPluginDir() . 'public/partials/searchForm.php';
		return ob_get_clean();
	}

	public function shortcode_worldMap()
	{
		$this->enqueueStyles();
		$this->enqueueScripts();
		/*
		$demovoxJsArr = [
			'language'          => Infos::getUserLanguage(),
			'ajaxUrl'           => admin_url('admin-ajax.php'),
			'nonce'             => Core::createNonce($this->nonceId),
		];
		wp_localize_script($this->pluginName, 'voluGMap', $demovoxJsArr);
		*/

		$platoOrgID = Config::getValue('platoOrgID');

		ob_start();
		include Infos::getPluginDir() . 'public/partials/worldMap.php';
		return ob_get_clean();
	}
}