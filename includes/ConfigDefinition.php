<?php

namespace VolunteersGuide;

class ConfigDefinition
{
	protected static $fieldsCache = null;
	protected static $sections = [
		'base'            => [
			'title' => 'Base settings',
			'page'  => 'volunGFields0',
		],
		'plato'           => [
			'title' => 'PLATO',
			'page'  => 'volunGFields0',
			'sub'   => 'I you don\'t know about PLATO, this plugin wo\'t be of use for you.',
		],
		'mapBase'         => [
			'title' => 'Map settings',
			'page'  => 'volunGFields1',
		],
		'continentColors' => [
			'title' => 'Continent colors',
			'page'  => 'volunGFields1',
			'sub'   => 'Set the colors of the continents on the map',
		],
		'continents'      => [
			'title' => 'Enable Continents (and set color intensity)',
			'page'  => 'volunGFields1',
			'sub'   => 'Enable map zoom in when a user clicks on the continent. Set the color intensity in percent.',
		],
		'countryColors'   => [
			'title' => 'Countries colors',
			'page'  => 'volunGFields2',
		],
		'countries'       => [
			'title' => 'Enable Countries',
			'page'  => 'volunGFields2',
			'sub'   => 'Set what will be shown when a user clicks on the country on the map and set the color intensity in percent. "disabled" will always have minimum (zero) intensity.',
		],
	];
	protected static $fields = [
		[
			'uid'          => 'platoOrgId',
			'label'        => 'Plato org ID',
			'section'      => 'plato',
			'type'         => 'text',
			'supplemental' => 'Plato ID of your organisation',
		],
		[
			'uid'          => 'inlineConfig',
			'label'        => 'Inline config',
			'section'      => 'mapBase',
			'type'         => 'checkbox',
			'supplemental' => 'Include the config on the page instead of loading it later by AJAX (worse performance for first page load after config change or new client language, might be fine on fast servers)',
		],
		[
			'uid'          => 'colorContinent1',
			'label'        => 'Color weak intensity',
			'section'      => 'continentColors',
			'type'         => 'colorpicker',
			'supplemental' => '',
		],
		[
			'uid'          => 'colorContinent2',
			'label'        => 'Color strong intensity',
			'section'      => 'continentColors',
			'type'         => 'colorpicker',
			'supplemental' => '',
		],
		[
			'uid'     => 'colorContinentHover',
			'label'   => 'Color on mouse over',
			'section' => 'continentColors',
			'type'    => 'colorpicker',
		],
		[
			'uid'     => 'colorCountry1',
			'label'   => 'Color weak intensity',
			'section' => 'countryColors',
			'type'    => 'colorpicker',
		],
		[
			'uid'     => 'colorCountry2',
			'label'   => 'Color strong intensity',
			'section' => 'countryColors',
			'type'    => 'colorpicker',
		],
		[
			'uid'     => 'colorCountryHover',
			'label'   => 'Color on mouse over',
			'section' => 'countryColors',
			'type'    => 'colorpicker',
		],
	];

	public static function getField($id)
	{
		$fields = ConfigDefinition::getFields();
		$key    = array_search($id, array_column($fields, 'uid'));
		if ($key === false) {
			Core::logMessage('Option field "' . $id . '" does not exist.');
			return null;
		}
		$field = $fields[$key];
		return $field;
	}

	public static function getFields()
	{
		if (self::$fieldsCache !== null) {
			return self::$fieldsCache;
		}
		$fields   = self::$fields;
		$fields[] = [
			'uid'          => 'button_default_text',
			'label'        => 'Project button - default text',
			'section'      => 'base',
			'type'         => 'text',
			'default'      => __('Apply here!', 'volunG'),
			'supplemental' => 'If you don\'t specify a label for a project button, this text will be used',
		];

		$allMapContinents = Countries::getAllMapContinents();
		foreach ($allMapContinents as $codeLong) {
			$fields[] = [
				'uid'     => 'continent_' . $codeLong,
				'label'   => Countries::getContinentName($codeLong),
				'section' => 'continents',
				'default' => '1',
				'type'    => 'mapContinent',
			];
		}

		$allMapCountries = Countries::getAllMapCountries();
		foreach ($allMapCountries as $country) {
			$fields[] = [
				'uid'     => 'country_' . $country,
				'label'   => Countries::getCountryName($country),
				'section' => 'countries',
				'default' => '',
				'type'    => 'mapCountry',
			];
		}

		self::$fieldsCache = $fields;
		return $fields;
	}

	/**
	 * @return array
	 */
	public static function getSections()
	{
		$sections = self::$sections;
		return $sections;
	}

	/**
	 * Access config values from this class without creating loops.
	 * Use Config::getValue() from other locations!
	 *
	 * @param $id      string
	 * @param $valPart null|string
	 * @param $default null|mixed Default value (ignore value in ConfigDefinition, for example to avoid function nesting)
	 *
	 * @return mixed Value set for the config.
	 */
	protected static function getConfigValue($id, $valPart = null, $default = null)
	{
		$fullId = $id . ($valPart ? config::GLUE_PART . $valPart : '');
		$value  = Core::getOption($fullId);
		if ($value !== false) {
			return $value;
		}
		return $default;
	}
}