<?php

namespace VolunteersGuide;

class Config
{
	const GLUE_PART     = '_';
	const PART_PAGE     = 'page';
	const PART_URL      = 'url';
	const PART_STRENGTH = 'strength';

	/**
	 * @param $id      string
	 * @param $valPart null|string
	 *
	 * @return mixed Value set for the config.
	 */
	public static function getValue($id, $valPart = null)
	{
		$fullId = $id . ($valPart ? self::GLUE_PART . $valPart : '');
		$fieldDefinition  = ConfigDefinition::getField($id);
		if ($fieldDefinition === null) {
			return null;
		}
		$value = Core::getOption($fullId);

		return self::valueFormat($value, $fieldDefinition, $valPart);
	}

	protected static function valueFormat($value, $field = null, $valPart = null)
	{
		if ($valPart) {
			if ($valPart == self::PART_STRENGTH) {
				if ($value === false) {
					$value = 100;
				}
				$value = intval($value);
			}
			return $value;
		}
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
		foreach (ConfigDefinition::getFields() as $field) {
			Core::delOption($field);
		}
	}
}