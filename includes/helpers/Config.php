<?php

namespace VolunteersGuide;

class Config
{
	const GLUE_PART      = '_';
	const PART_PAGE      = 'page';
	const PART_URL       = 'url';
	const PART_INTENSITY = 'intensity';

	/**
	 * @param $id      string
	 * @param $valPart null|string
	 *
	 * @return mixed Value set for the config.
	 */
	public static function getValue($id, $valPart = null)
	{
		$fullId = $id . ($valPart ? self::GLUE_PART . $valPart : '');
		return Core::getOption($fullId);
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