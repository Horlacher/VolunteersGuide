<?php

namespace VolunteersGuide;

class Config
{
	const GLUE_LANG = '_';
	const GLUE_PART = '_';

	/**
	 * @param $id string
	 * @param $valPart null|string
	 * @return mixed Value set for the config.
	 */
	public static function getValue($id, $valPart = null)
	{
		$fullId = $id . ($valPart ? self::GLUE_PART . $valPart : '');
		$field = ConfigVars::getField($id);
		if ($field === null) {
			return null;
		}
		$value = Core::getOption($fullId);

		return self::valueFormat($valPart, $value, $field);
	}

	protected static function valueFormat($valPart, $value, $field = null)
	{
		if (isset($field['type']) && $field['type'] === 'checkbox') {
			if ($value === false) {
				$value = isset($field['default']) ? $field['default'] : false; // Set to our default
			} else {
				$value = !!$value;
			}
		} else {
			if ($value === false) {
				$value = isset($field['default']) ? $field['default'] : false; // Set to our default
			}
		}
		return $value;
	}

	/**
	 * Delete all fields (plugin settings)
	 */
	public static function deleteAll()
	{
		foreach (ConfigVars::$fields as $field) {
			Core::delOption($field);
		}
	}
}