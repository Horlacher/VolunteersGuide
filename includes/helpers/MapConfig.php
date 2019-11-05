<?php

namespace VolunteersGuide;

class MapConfig
{
	public static function getMapConfig($locale = null, $echo = false)
	{
		$locale = $locale ?: Infos::getUserLanguage();
		if ($echo) {
			$json = self::echoCache($locale);
		} else {
			$json = self::getCache($locale);
		}
		if (!$json) {
			$json = self::generateMapConfig($locale);
			self::setCache($locale, $json);
			if ($echo) {
				echo $json;
			}
		}
		if (!$echo) {
			return $json;
		}
	}

	public static function resetCache()
	{
		$dir = self::getCacheDirPath();
		foreach (glob($dir . '*.*') as $v) {
			unlink($v);
		}
	}

	/**
	 * @param $locale
	 *
	 * @return false|string
	 */
	protected static function getCache($locale)
	{
		$filePath = self::getCachePath($locale);
		if (!file_exists($filePath)) {
			return false;
		}
		$fp = fopen($filePath, 'rb');
		if ($fp) {
			$data = fread($fp, filesize($filePath));
			fclose($fp);
			return $data;
		}
		return false;
	}

	/**
	 * @param $locale
	 *
	 * @return false|int bytes echoed
	 */
	protected static function echoCache($locale)
	{
		$filePath = self::getCachePath($locale);
		if (!file_exists($filePath)) {
			return false;
		}
		return readfile($filePath);
	}

	protected static function setCache($locale, $json)
	{
		$filePath = self::getCachePath($locale);
		if (!file_exists(dirname($filePath))) {
			mkdir(dirname($filePath), 0777, true);
		}
		if (false !== ($f = @fopen($filePath, 'w'))) {
			fwrite($f, $json);
			fclose($f);
			return true;
		}
		return false;
	}

	protected static function getCachePath($locale)
	{
		return self::getCacheDirPath() . $locale . '.json';
	}

	protected static function getCacheDirPath()
	{
		return Infos::getPluginDir() . 'configCache' . DIRECTORY_SEPARATOR;
	}

	protected static function generateMapConfig($locale)
	{
		$platoOrgId = Config::getValue('platoOrgId');

		// platoUrl
		$platoUrl = '"platoUrl":"https://frontend.workcamp-plato.org/searchresult.352.aspx?platoorgid=' . $platoOrgId
					. '&countries={countries}",';

		// colors
		$colors = '"colors":{' .
				  '"continent":["' . Config::getValue('colorContinent1') . '", "' . Config::getValue('colorContinent2') . '"],' .
				  '"country":["' . Config::getValue('colorCountry1') . '", "' . Config::getValue('colorCountry2') . '"],' .
				  '"continentHover":"' . Config::getValue('colorCountryHover') . '",' .
				  '"countryHover":"' . Config::getValue('colorCountryHover') . '"' .
				  '},';

		// continents
		$allMapContinents = Countries::getAllMapContinents();
		$continents       = '';
		foreach ($allMapContinents as $code) {
			$continentEnabled = Config::getValue('continent_' . $code);
			if (!$continentEnabled) {
				continue;
			}
			$continents .= '"' . $code . '": ' . Config::getValue('continent_' . $code, Config::PART_INTENSITY) . ',';
		}
		$continents = '"continents":{' . substr($continents, 0, -1) . '},';

		// countries
		$allMapCountries = Countries::getAllMapCountries();
		$countries       = '';
		foreach ($allMapCountries as $code => $country) {
			$countryMode = Config::getValue('country_' . $country);
			if (!$countryMode) {
				continue;
			}
			$countries .= '"' . $country . '":{';
			$countries .= '"name":"' . Countries::getCountryName($country, $locale) . '",';
			$countries .= '"intensity":' . intval(Config::getValue('country_' . $country, Config::PART_INTENSITY)) . ',';
			switch ($countryMode) {
				case '':
					continue;
					break;
				case 'plato':
					$countries .= '"plato":"' . Countries::getCountryLongCode($country) . '"';
					break;
				case 'page':
					$pageId    = Config::getValue('country_' . $country, Config::PART_PAGE);
					$url       = get_permalink($pageId);
					$countries .= '"url":"' . $url . '"';
					break;
				case 'url':
					$url       = Config::getValue('country_' . $country, Config::PART_URL);
					$countries .= '"url":"' . $url . '"';
					break;
			}
			$countries .= '},';
		}
		$countries = '"countries":{' . substr($countries, 0, -1) . '}';
		return '{' . $platoUrl . $colors . $continents . $countries . '}';
	}
}