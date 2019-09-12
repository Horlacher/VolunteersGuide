<?php

namespace Plato;

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://github.com/horlacher/wp-plugin-plato
 * @since      1.0.0
 *
 * @package    Plato
 * @subpackage Plato/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Plato
 * @subpackage Plato/public
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
	private $nonceId = 'plato_ajax_submit';

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
		add_shortcode('plato_projectbutton', [$this, 'shortcode_projectButton',]);
		add_shortcode('plato_searchform', [$this, 'shortcode_searchForm',]);
		add_shortcode('plato_worldmap', [$this, 'shortcode_worldMap',]);
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
		wp_enqueue_style($this->pluginName, plugin_dir_url(__FILE__) . 'css/plato-public.min.css', [], $this->version, 'all');
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
			plugin_dir_url(__FILE__) . 'js/plato-public.min.js',
			['jquery',],
			$this->version,
			false
		);
	}

	public function shortcode_projectButton($atts = [], $content = null)
	{
		// normalize attribute keys, lowercase
		$atts = array_change_key_case((array)$atts, CASE_LOWER);
		if (!isset($atts['code'])) {
			return __('Shortcode must include the attribute "code" with the plato project code aas value');
		}

		$projCode = sanitize_text_field($atts['code']);

		ob_start();
		$platoOrgID = Config::getValue('platoOrgID');
		$content    = $content
			? sanitize_text_field($content)
			: sanitize_text_field(Config::getValue('button_default_text'));

		include Infos::getPluginDir() . 'public/partials/projectButton.php';

		return ob_get_clean();
	}

	public function shortcode_searchForm()
	{
		ob_start();
		$platoOrgID = Config::getValue('platoOrgID');

		include Infos::getPluginDir() . 'public/partials/searchForm.php';

		return ob_get_clean();
	}

	public function shortcode_worldMap()
	{
		wp_enqueue_script(
			$this->pluginName . '-platoMap',
			plugin_dir_url(__FILE__) . 'js/plato-worldMap.min.js',
			['jquery',],
			$this->version,
			false
		);
		$demovoxJsArr = [
			'language'          => Infos::getUserLanguage(),
			'ajaxUrl'           => admin_url('admin-ajax.php'),
			'nonce'             => Core::createNonce($this->nonceId),
			'apiAddressEnabled' => '',
		];
		wp_localize_script($this->pluginName, 'platoMap', $demovoxJsArr);
		wp_add_inline_script(
			$this->pluginName . '-platoMap',
			'jQuery(function(){
  jQuery(\'#world-map-gdp\').vectorMap({
    map: \'world_mill\',
    series: {
      regions: [{
        values: gdpData,
        scale: [\'#C8EEFF\', \'#0071A4\'],
        normalizeFunction: \'polynomial\'
      }]
    },
    onRegionTipShow: function(e, el, code){
      el.html(el.html()+\' (GDP - \'+gdpData[code]+\')\');
    }
  });
});'
		);

		ob_start();
		$platoOrgID = Config::getValue('platoOrgID');

		include Infos::getPluginDir() . 'public/partials/worldMap.php';

		return ob_get_clean();
	}
}