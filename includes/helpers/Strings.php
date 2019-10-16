<?php

namespace VolunteersGuide;

class Strings
{
	/**
	 *
	 * @param array $options
	 * @param string $value
	 * @param string $name
	 * @param string $id
	 * @return string select id
	 */
	public static function createSelect($options, $value, $name, $id = null, $attributes = [])
	{
		$id = $id === null ? $name : $id;
		$optionsMarkup = '’';
		foreach ($options as $key => $label) {
			$optionsMarkup .= sprintf(
				'<option value="%s" %s>%s</option>',
				$key,
				selected($value, $key, false),
				$label
			);
		}
		$addAttribs = '';
		foreach ($attributes as $name => $value) {
			$addAttribs .= ' ' . $name . '="' . $value . '"';
		}
		printf('<select name="%1$s" id="%2$s"%4$s>%3$s</select>', $name, $id, $optionsMarkup, $addAttribs);

		return $id;
	}
}