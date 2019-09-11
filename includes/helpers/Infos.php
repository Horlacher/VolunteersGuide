<?php

namespace Plato;

class Infos
{
	public static function getUserLanguage($raw = false)
	{
		$lang = get_user_locale();
		if ($raw) {
			return $lang;
		}
		$lang = strtolower(substr($lang, 0, 2));

		return $lang;
	}

	public static function getPluginDir()
	{
		return Core::getPluginDir();
	}
}