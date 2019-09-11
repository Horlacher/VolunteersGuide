<?php

namespace Plato;

class ConfigVars
{
	static public $sections = [
		'base'             => [
			'title' => 'Base settings',
			'page'  => 'platoSettings',
		],
		'enabledLanguages' => [
			'title' => 'Enabled languages',
			'page'  => 'platoSettings2',
		],
	];
	static public $fields = [
		[
			'uid'          => 'platoOrgID',
			'label'        => 'platoOrgID',
			'section'      => 'base',
			'type'         => 'text',
			'supplemental' => 'Plato ID of your organisation',
		],
	];

	public static function getField($id)
	{
		$fields = ConfigVars::getFields();
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
		$fields   = self::$fields;
		$fields[] = [
			'uid'          => 'button_default_text',
			'label'        => 'Project button - default text',
			'section'      => 'base',
			'type'         => 'text',
			'default'      => __('Apply now!'),
			'supplemental' => 'If you don\'t specify a label for a project button, this text will be used',
		];
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
	 * @param $default null|mixed Default value (ignore value in ConfigVars, for example to avoid function nesting)
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